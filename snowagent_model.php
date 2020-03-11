<?php

use CFPropertyList\CFPropertyList;

class Snowagent_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'snowagent'); // Primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['version'] = '';
		$this->rs['version_long'] = '';
		$this->rs['build'] = '';
		$this->rs['rev'] = '';
		$this->rs['sitename'] = '';
		$this->rs['configname'] = '';
		$this->rs['server_address'] = '';
		$this->rs['client_cert'] = '';
		$this->rs['client_cert_password'] = '';
		$this->rs['drop_location'] = '';
		$this->rs['software_scan_running_processes'] = ''; // True/False
		$this->rs['software_scan_jar'] = ''; // True/False
		$this->rs['http_ssl_verify'] = ''; // True/False

		if ($serial) {
			$this->retrieve_record($serial);
		}

		$this->serial_number = $serial;
	}

	// ------------------------------------------------------------------------
    
	/**
	 * Process data sent by postflight
	 *
	 * @param plist data
	 * @author tuxudo
	 **/
	function process($plist)
	{
        // If plist is empty, echo out error
        if (! $plist) {
			echo ("Error Processing snowagent module: No data found");
		} else { 

            $parser = new CFPropertyList();
            $parser->parse($plist, CFPropertyList::FORMAT_XML);
            $myList = $parser->toArray();

            $typeList = array(
                'version' => '',
                'version_long' => '',
                'build' => '',
                'rev' => '',
                'sitename' => '',
                'configname' => '',
                'server_address' => '',
                'client_cert' => '',
                'client_cert_password' => '',
                'drop_location' => '',
                'software_scan_running_processes' => '',
                'software_scan_jar' => '',
                'http_ssl_verify' => ''
            );

            // Process each key
            foreach ($typeList as $key => $value) {
                $this->rs[$key] = $value;
                if(array_key_exists($key, $myList))
                {
                    $this->rs[$key] = $myList[$key];
                }
            }

            // Save the data, I want some soup
            $this->save();
		}
	}
}
