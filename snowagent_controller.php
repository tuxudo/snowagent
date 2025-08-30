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
        if (!$this->authorized()) {
            jsonError('Not authorized', 403);
            return;
        }

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
    * Get list for boolean columns (1, 0, NULL)
    *
     * @return void
     * @author tuxudo
     **/
    public function get_list_boolean($column = '')
    {
        if (!$this->authorized()) {
            jsonError('Not authorized', 403);
            return;
        }

        // Sanitize input
        $column = preg_replace("/[^A-Za-z0-9_\-]+/", '', $column);
        
        // Whitelist allowed boolean columns to prevent column injection
        $allowed_columns = [
            'software_scan_jar', 'software_scan_running_processes', 'http_ssl_verify'
        ];
        
        if (!in_array($column, $allowed_columns)) {
            jsonView([]);
            return;
        }
        
        // Use the old model pattern like other working methods in this controller
        $queryobj = new Snowagent_model();
        $sql = "SELECT $column AS label, COUNT(*) AS count 
                FROM snowagent 
                LEFT JOIN reportdata USING (serial_number)
                WHERE ".get_machine_group_filter('')."
                GROUP BY $column 
                ORDER BY count DESC";
        
        $result = $queryobj->query($sql);
        if ($result) {
            jsonView($result);
        } else {
            jsonView([]);
        }
    }
    
    /**
    * Snowagent scan_running_processes widget
    *
    * @return void
    * @author tuxudo
    **/
    public function get_scan_running_processes()
    {
        if (!$this->authorized()) {
            jsonError('Not authorized', 403);
            return;
        }

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
        if (!$this->authorized()) {
            jsonError('Not authorized', 403);
            return;
        }

        $obj = new View();
        $queryobj = new Snowagent_model();
        $sql = "SELECT version, COUNT(1) AS count
                    FROM snowagent
                    LEFT JOIN reportdata USING (serial_number)
                    WHERE ".get_machine_group_filter('')." 
                    AND version IS NOT NULL 
                    AND version != ''
                    GROUP BY version
                    ORDER BY COUNT DESC";       
        $obj->view('json', array('msg' => $queryobj->query($sql)));
    }
    
    /**
    * Get snow agent server addresses
    *
    * @return void
    * @author tuxudo
    **/
    public function get_server_addresses()
    {
        if (!$this->authorized()) {
            jsonError('Not authorized', 403);
            return;
        }

        $obj = new View();
        $queryobj = new Snowagent_model();
        $sql = "SELECT server_address, COUNT(1) AS count
                    FROM snowagent
                    LEFT JOIN reportdata USING (serial_number)
                    WHERE ".get_machine_group_filter('')." 
                    AND server_address IS NOT NULL 
                    AND server_address != ''
                    GROUP BY server_address
                    ORDER BY COUNT DESC";       
        $obj->view('json', array('msg' => $queryobj->query($sql)));
    }

    /**
    * Get snow agent config names
    *
    * @return void
    * @author tuxudo
    **/
    public function get_config_names()
    {
        if (!$this->authorized()) {
            jsonError('Not authorized', 403);
            return;
        }

        $obj = new View();
        $queryobj = new Snowagent_model();
        $sql = "SELECT configname, COUNT(1) AS count
                    FROM snowagent
                    LEFT JOIN reportdata USING (serial_number)
                    WHERE ".get_machine_group_filter('')." 
                    AND configname IS NOT NULL 
                    AND configname != ''
                    GROUP BY configname
                    ORDER BY COUNT DESC";       
        $obj->view('json', array('msg' => $queryobj->query($sql)));
    }

	/**
     * Retrieve data in json format
     *
     * @return void
     * @author tuxudo
     **/
    public function get_data($serial_number = '')
    {
        if (!$this->authorized()) {
            jsonError('Not authorized', 403);
            return;
        }

        // Sanitize input - fix regex pattern and validate
        $serial_number = preg_replace("/[^A-Za-z0-9_\-]+/", '', $serial_number);
        
        if (empty($serial_number)) {
            $obj = new View();
            $obj->view('json', array('msg' => []));
            return;
        }

        // Use parameterized query to prevent SQL injection
        $sql = "SELECT sitename, configname, server_address, version, build, rev, version_long, client_cert, http_ssl_verify, snowpack_count, software_scan_running_processes, software_scan_jar, saas_chrome_enabled, scan_include_paths, scan_exclude_paths
                    FROM snowagent 
                    WHERE serial_number = ?";
        
        $obj = new View();
        $queryobj = new Snowagent_model();
        $snowagent_tab = $queryobj->query($sql, [$serial_number]);
        $obj->view('json', array('msg' => current(array('msg' => $snowagent_tab)))); 
    }
} // End class Snowagent_controller
