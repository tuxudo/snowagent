#!/usr/local/munkireport/munkireport-python3

"""
snowagent for munkireport.
By Tuxudo
"""

import subprocess
import os
import sys
import platform
import re
import fnmatch
from xml.etree import cElementTree as ElementTree

sys.path.insert(0,'/usr/local/munki')
sys.path.insert(0,'/usr/local/munkireport')

from munkilib import FoundationPlist

def get_snowagent_version():
    
    if os.path.isfile('/opt/snow/snowagent'):
        cmd = ['/opt/snow/snowagent', 'version']
        proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                                stdin=subprocess.PIPE,
                                stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        (output, unused_error) = proc.communicate()
        output = output.decode()

        try:
            # Handle both old format (+private-build) and new format (+build)
            if '+private-build-' in output:
                # Old format: <version>+private-build-<build>-rev-<revision>
                version_part = output.split('+private-build')[0]
                build_part = output.split('+private-build-')[1]
            elif '+build-' in output:
                # New format: <version>+build-<build>-rev-<revision>
                version_part = output.split('+build')[0]
                build_part = output.split('+build-')[1]
            else:
                # Fallback: just use the whole output as version
                return {
                    "version": output.strip(),
                    "version_long": output,
                    "build": "",
                    "rev": ""
                }
            
            # Extract build and revision information from the remaining part
            if '-rev-' in build_part:
                build = build_part.split('-rev-')[0]
                rev = build_part.split('-rev-')[1]
            else:
                build = build_part
                rev = ""

            version_return = {
                "version": version_part,
                "version_long": output,
                "build": build,
                "rev": rev
            }
            return version_return
        except Exception as e:
            return {"version": "", "version_long": "", "error": str(e)}
    else:
        return {}
        
def get_snowagent_config():
    
    if os.path.isfile('/opt/snow/snowagent.config'):
    
        tree = ElementTree.parse('/opt/snow/snowagent.config')
        root = tree.getroot()
        xmldict = XmlDictConfig(root)

        snowagent_config = {}

        try:
            if xmldict['Agent']['SiteName']:
                snowagent_config['sitename'] = xmldict['Agent']['SiteName']
        except:
            snowagent_config['sitename'] = ""

        try:
            if xmldict['Agent']['ConfigName']:
                snowagent_config['configname'] = xmldict['Agent']['ConfigName']
        except:
            snowagent_config['configname'] = ""
        
        try:
            if xmldict['Server']['Endpoint']:

                snowagent_config['server_address'] = ""

                for server in xmldict['Server']['Endpoint']:
                    if "XmlDictConfig" in str(type(server)):
                        snowagent_config['server_address'] = snowagent_config['server_address']+", "+server['Address']
                    else:
                        snowagent_config['server_address'] = ", "+xmldict['Server']['Endpoint']['Address']

                snowagent_config['server_address'] = snowagent_config['server_address'][2:]

        except:
            snowagent_config['server_address'] = ""

        try:
            if xmldict['Server']['Endpoint']['ClientCertificate']['FileName']:
                snowagent_config['client_cert'] = xmldict['Server']['Endpoint']['ClientCertificate']['FileName']
        except:
            snowagent_config['client_cert'] = ""
        try:
            if xmldict['Server']['Endpoint']['ClientCertificate']['Password']:
                snowagent_config['client_cert_password'] = xmldict['Server']['Endpoint']['ClientCertificate']['Password']
        except:
            snowagent_config['client_cert_password'] = ""
        try:
            if xmldict['DropLocation']['Path']:
                snowagent_config['drop_location'] = xmldict['DropLocation']['Path']
        except:
            snowagent_config['drop_location'] = ""

        xml_str = ElementTree.tostring(root, encoding='utf8', method='xml').decode()

        # Parse SystemSettings section properly for Snow 7.2+
        # Handle both old string format and new XML structure
        def get_setting_value(setting_key):
            """Get a setting value from SystemSettings XML or fall back to string matching"""
            try:
                # Try new XML structure first (Snow 7.2+)
                if 'SystemSettings' in xmldict:
                    system_settings = xmldict['SystemSettings']
                    if 'Setting' in system_settings:
                        settings = system_settings['Setting']
                        # Handle both single setting and list of settings
                        if isinstance(settings, list):
                            for setting in settings:
                                if hasattr(setting, 'get') and setting.get('key') == setting_key:
                                    return setting.get('value')
                        elif hasattr(settings, 'get') and settings.get('key') == setting_key:
                            return settings.get('value')
                
                # Fall back to old string matching method
                if f'key="{setting_key}" value="true"' in xml_str:
                    return "true"
                elif f'key="{setting_key}" value="false"' in xml_str:
                    return "false"
                    
            except Exception:
                pass
            return None

        # Get software.scan.jar setting
        jar_value = get_setting_value('software.scan.jar')
        if jar_value == "true":
            snowagent_config['software_scan_jar'] = 1
        elif jar_value == "false":
            snowagent_config['software_scan_jar'] = 0

        # Get saas.chrome.enabled setting
        chrome_saas_value = get_setting_value('saas.chrome.enabled')
        if chrome_saas_value == "true":
            snowagent_config['saas_chrome_enabled'] = 1
        elif chrome_saas_value == "false":
            snowagent_config['saas_chrome_enabled'] = 0

        # Get file system scan include/exclude paths
        try:
            if 'Software' in xmldict:
                software_config = xmldict['Software']
                
                # Get include paths
                if 'Include' in software_config:
                    include_section = software_config['Include']
                    if 'Path' in include_section:
                        include_paths = include_section['Path']
                        
                        if isinstance(include_paths, list):
                            # Handle list of paths
                            path_strings = []
                            for path in include_paths:
                                # Since XmlDictConfig is extracting text, we need to get recursive from the original XML
                                # For now, assume recursive=true for all paths as per the config
                                path_strings.append(f"true:{path}")
                            snowagent_config['scan_include_paths'] = ', '.join(path_strings)
                        else:
                            # Handle single path
                            if include_paths:
                                # Assume recursive=true for single path
                                snowagent_config['scan_include_paths'] = f"true:{include_paths}"
                            else:
                                snowagent_config['scan_include_paths'] = ""
                    else:
                        snowagent_config['scan_include_paths'] = ""
                else:
                    snowagent_config['scan_include_paths'] = ""
                
                # Get exclude paths
                if 'Exclude' in software_config:
                    exclude_section = software_config['Exclude']
                    if 'Path' in exclude_section:
                        exclude_paths = exclude_section['Path']
                        
                        if isinstance(exclude_paths, list):
                            # Handle list of paths
                            path_strings = []
                            for path in exclude_paths:
                                if path:
                                    path_strings.append(path)
                            snowagent_config['scan_exclude_paths'] = ', '.join(path_strings)
                        else:
                            # Handle single path
                            if exclude_paths:
                                snowagent_config['scan_exclude_paths'] = exclude_paths
                            else:
                                snowagent_config['scan_exclude_paths'] = ""
                    else:
                        snowagent_config['scan_exclude_paths'] = ""
                else:
                    snowagent_config['scan_exclude_paths'] = ""
            else:
                snowagent_config['scan_include_paths'] = ""
                snowagent_config['scan_exclude_paths'] = ""
        except Exception as e:
            # Fallback to empty strings if parsing fails
            snowagent_config['scan_include_paths'] = ""
            snowagent_config['scan_exclude_paths'] = ""

        # Get http.ssl_verify setting
        ssl_verify_value = get_setting_value('http.ssl_verify')
        if ssl_verify_value == "true":
            snowagent_config['http_ssl_verify'] = 1
        elif ssl_verify_value == "false":
            snowagent_config['http_ssl_verify'] = 0

        snowagent_config['snowpack_count'] = get_snowpack_count()

        return snowagent_config

    else:
        return {}

def get_snowpack_count():
    count = len(fnmatch.filter(os.listdir('/opt/snow/data/'), '*.snowpack'))
    return count


class XmlListConfig(list):
    def __init__(self, aList):
        for element in aList:
            if element:
                # treat like dict
                if len(element) == 1 or element[0].tag != element[1].tag:
                    self.append(XmlDictConfig(element))
                # treat like list
                elif element[0].tag == element[1].tag:
                    self.append(XmlListConfig(element))
            elif element.text:
                text = element.text.strip()
                if text:
                    self.append(text)

class XmlDictConfig(dict):
    '''
    Example usage:

    >>> tree = ElementTree.parse('your_file.xml')
    >>> root = tree.getroot()
    >>> xmldict = XmlDictConfig(root)

    Or, if you want to use an XML string:

    >>> root = ElementTree.XML(xml_string)
    >>> xmldict = XmlDictConfig(root)

    And then use xmldict for what it is... a dict.
    '''
    def __init__(self, parent_element):
        if list(parent_element.items()):
            self.update(dict(list(parent_element.items())))
        for element in parent_element:
            if element:
                # treat like dict - we assume that if the first two tags
                # in a series are different, then they are all different.
                if len(element) == 1 or element[0].tag != element[1].tag:
                    aDict = XmlDictConfig(element)
                # treat like list - we assume that if the first two tags
                # in a series are the same, then the rest are the same.
                else:
                    # here, we put the list in dictionary; the key is the
                    # tag name the list elements all share in common, and
                    # the value is the list itself 
                    aDict = {element[0].tag: XmlListConfig(element)}
                # if the tag has attributes, add those to the dict
                if list(element.items()):
                    aDict.update(dict(list(element.items())))
                self.update({element.tag: aDict})
            # this assumes that if you've got an attribute in a tag,
            # you won't be having any text. This may or may not be a 
            # good idea -- time will tell. It works for the way we are
            # currently doing XML configuration files...
            elif list(element.items()):
                self.update({element.tag: dict(list(element.items()))})
            # finally, if there are no child tags and no attributes, extract
            # the text
            else:
                self.update({element.tag: element.text})

def merge_two_dicts(x, y):
    z = x.copy()
    z.update(y)
    return z

def main():
    """Main"""

    # Get results
    result = dict()
    result = merge_two_dicts(get_snowagent_config(), get_snowagent_version())

    # Write snowagent results to cache
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    output_plist = os.path.join(cachedir, 'snowagent.plist')
    FoundationPlist.writePlist(result, output_plist)
#    print FoundationPlist.writePlistToString(result)

if __name__ == "__main__":
    main()
