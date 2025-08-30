
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

// Format Boolean fields with success/danger classes: 1 = Yes (success), 0 = No (danger), NULL = Unknown (warning)
var formatSnowagentBoolean = function(col, row) {
    var cell = $('td:eq('+col+')', row),
        value = cell.text().trim();
    
    switch (value) {
        case '1':
            value = '<span class="label label-success">Yes</span>';
            break;
        case '0':
            value = '<span class="label label-danger">No</span>';
            break;
        default:
            value = '<span class="label label-warning">Unknown</span>';
    }
    
    cell.html(value);
}

// Make sure functions are in global scope
window.formatSnowagentBoolean = formatSnowagentBoolean;

