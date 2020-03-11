<?php

/**
 * snowagent module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Snowagent_controller extends Module_controller
{

	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 * @author tuxudo
	 *
	 **/
	function index()
	{
		echo "You've loaded the snowagent module!";
	}
    
    /**
    * Snowagent HTTP SSL verify widget
    *
    * @return void
    * @author tuxudo
    **/
    public function get_http_ssl_verify()
    {
        $obj = new View();
        $queryobj = new Snowagent_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `http_ssl_verify` = 0 THEN 1 END) AS 'off',
                        COUNT(CASE WHEN `http_ssl_verify` = 1 THEN 1 END) AS 'on'
                        from snowagent
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE ".get_machine_group_filter('');       
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
    
    /**
    * Snowagent software_scan_jar widget
    *
    * @return void
    * @author tuxudo
    **/
    public function get_scan_jar()
    {
        $obj = new View();
        $queryobj = new Snowagent_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `software_scan_jar` = 0 THEN 1 END) AS 'off',
                        COUNT(CASE WHEN `software_scan_jar` = 1 THEN 1 END) AS 'on'
                        from snowagent
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE ".get_machine_group_filter('');       
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
    
    /**
    * Snowagent scan_running_processes widget
    *
    * @return void
    * @author tuxudo
    **/
    public function get_scan_running_processes()
    {
        $obj = new View();
        $queryobj = new Snowagent_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `software_scan_running_processes` = 0 THEN 1 END) AS 'off',
                        COUNT(CASE WHEN `software_scan_running_processes` = 1 THEN 1 END) AS 'on'
                        from snowagent
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE ".get_machine_group_filter('');       
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
    
    /**
    * Get snow agent versions
    *
    *
    **/
    public function get_versions()
    {
        $obj = new View();
        $queryobj = new Snowagent_model();
        $sql = "SELECT version, COUNT(1) AS count
                    FROM snowagent
                    LEFT JOIN reportdata USING (serial_number)
                    WHERE ".get_machine_group_filter('')." 
                    GROUP BY version
                    ORDER BY COUNT DESC";       
        $obj->view('json', array('msg' => $queryobj->query($sql)));
    }

	/**
     * Retrieve data in json format
     *
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();
        $sql = "SELECT sitename, configname, server_address, version, build, rev, version_long, client_cert, http_ssl_verify, software_scan_running_processes, software_scan_jar
                    FROM snowagent 
                    WHERE serial_number = '$serial_number'";
        
        $queryobj = new Snowagent_model();
        $snowagent_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $snowagent_tab)))); 
    }
} // End class Snowagent_controller
