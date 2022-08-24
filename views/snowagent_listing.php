<?php 

$this->view('listings/default',
[
	"i18n_title" => 'snowagent.reporttitle',
  "js_link" => "module/snowagent/js/snowagent",
  "not_null_column" => "snowagent.version",
	"table" => [
		[
			"column" => "machine.computer_name",
			"i18n_header" => "listing.computername",
			"formatter" => "clientDetail",
			"tab_link" => "snowagent-tab",
		],
		["i18n_header" => "displays_info.machineserial", "column" => "reportdata.serial_number"],
    ["i18n_header" => "snowagent.sitename", "column" => 'snowagent.sitename'],
    ["i18n_header" => "snowagent.configname", "column" => 'snowagent.configname'],
    ["i18n_header" => "snowagent.server_address", "column" => 'snowagent.server_address'],
    ["i18n_header" => "snowagent.version", "column" => 'snowagent.version'],
    ["i18n_header" => "snowagent.build", "column" => 'snowagent.build'],
    ["i18n_header" => "snowagent.rev", "column" => 'snowagent.rev'],
    ["i18n_header" => "snowagent.snowpack_count", "column" => 'snowagent.snowpack_count'],
    ["i18n_header" => "snowagent.http_ssl_verify", "column" => 'snowagent.http_ssl_verify', "formatter" => "binaryYesNo", "filter" => "SnowSSLVerifyFilter"],
    ["i18n_header" => "snowagent.software_scan_jar", "column" => 'snowagent.software_scan_jar', "formatter" => "binaryYesNo", "filter" => "softwareScanJarFilter"],
    ["i18n_header" => "snowagent.software_scan_running_processes", "column" => 'snowagent.software_scan_running_processes', "formatter" => "binaryYesNo", "filter" => "softwareScanRunningProcessesFilter"],
	]
]);
