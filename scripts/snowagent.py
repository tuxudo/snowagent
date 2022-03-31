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
            version = output.split('+build')[0]
            build = output.split('+build-')[1].split('-rev-')[0]
            rev = output.split('-rev-')[1]

            version_return = {"version":version,"version_long":output,"build":build,"rev":rev}
            return version_return
        except Exception:
            return {"version":"","version_long":""}
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
            if xmldict['Server']['Endpoint']['Address']:
                snowagent_config['server_address'] = xmldict['Server']['Endpoint']['Address']
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

        if 'key="software.scan.running_processes" value="true"' in xml_str:
            snowagent_config['software_scan_running_processes'] = 1    
        elif 'key="software.scan.running_processes" value="false"' in xml_str:
            snowagent_config['software_scan_running_processes'] = 0

        if 'key="software.scan.jar" value="true"' in xml_str:
            snowagent_config['software_scan_jar'] = 1    
        elif 'key="software.scan.jar" value="false"' in xml_str:
            snowagent_config['software_scan_jar'] = 0

        if 'key="http.ssl_verify" value="true"' in xml_str:
            snowagent_config['http_ssl_verify'] = 1    
        elif 'key="http.ssl_verify" value="false"' in xml_str:
            snowagent_config['http_ssl_verify'] = 0

        return snowagent_config

    else:
        return {}

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
