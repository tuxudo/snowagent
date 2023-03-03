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
		$this->rs['snowpack_count'] = null;

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
                'version' => null,
                'version_long' => null,
                'build' => null,
                'rev' => null,
                'sitename' => null,
                'configname' => null,
                'server_address' => null,
                'client_cert' => null,
                'client_cert_password' => null,
                'drop_location' => null,
                'software_scan_running_processes' => null,
                'software_scan_jar' => null,
                'http_ssl_verify' => null,
                'snowpack_count' => null
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
