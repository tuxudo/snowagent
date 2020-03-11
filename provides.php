<?php

return array(
    'client_tabs' => array(
        'snowagent-tab' => array('view' => 'snowagent_tab', 'i18n' => 'snowagent.snowagent'),
    ),
    'listings' => array(
        'snowagent' => array('view' => 'snowagent_listing', 'i18n' => 'snowagent.snowagent'),
    ),
    'widgets' => array(
        'snowagent_http_ssl_verify' => array('view' => 'snowagent_http_ssl_verify_widget'),
        'snowagent_scan_jar' => array('view' => 'snowagent_scan_jar_widget'),
        'snowagent_scan_running_processes' => array('view' => 'snowagent_scan_running_processes_widget'),
        'snowagent_versions' => array('view' => 'snowagent_versions_widget'),
    ),
    'reports' => array(
        'snowagent_report' => array('view' => 'snowagent_report', 'i18n' => 'snowagent.reporttitle'),
    ),
);
