
var SnowSSLVerifyFilter = function(colNumber, d){

    // Look for 'between' statement todo: make generic
    if(d.search.value.match(/^http_ssl_verify = \d$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = d.search.value.replace(/.*(\d)$/, '= $1');

        // Clear global search
        d.search.value = '';
    }
}

var softwareScanRunningProcessesFilter = function(colNumber, d){

    // Look for 'between' statement todo: make generic
    if(d.search.value.match(/^software_scan_running_processes = \d$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = d.search.value.replace(/.*(\d)$/, '= $1');

        // Clear global search
        d.search.value = '';
    }
}

var softwareScanJarFilter = function(colNumber, d){

    // Look for 'between' statement todo: make generic
    if(d.search.value.match(/^software_scan_jar = \d$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = d.search.value.replace(/.*(\d)$/, '= $1');

        // Clear global search
        d.search.value = '';
    }
}

