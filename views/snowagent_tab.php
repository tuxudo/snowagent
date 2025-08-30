<div id="lister" style="font-size: large; float: right;">
    <a href="/show/listing/snowagent/snowagent" title="List">
        <i class="btn btn-default tab-btn fa fa-list"></i>
    </a>
</div>
<div id="report_btn" style="font-size: large; float: right;">
    <a href="/show/report/snowagent/snowagent_report" title="Report">
        <i class="btn btn-default tab-btn fa fa-th"></i>
    </a>
</div>
<h2><i class="fa fa-snowflake-o"></i> <span data-i18n="snowagent.snowagent"></span></h2>
<div id="snowagent-tab"></div>

<div id="snowagent-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
    
	$.getJSON(appUrl + '/module/snowagent/get_data/' + serialNumber, function(data){

        if( data.length == 0 || data[0]["sitename"] == null){
            $('#snowagent-msg').text(i18n.t('no_data'));
            $('#snowagent-cnt').text('')
        } else {
            // Hide loading message
            $('#snowagent-msg').text('');
        
            var skipThese = ['id','serial_number'];
            $.each(data, function(i,d){
                // Generate rows from data
                var rows = ''
                var rows_apps = ''
                var rows_services = ''
                for (var prop in d){
                    // Skip skipThese
                    if(skipThese.indexOf(prop) == -1){
                        if (d[prop] == null || d[prop] === '') {
                            // Do nothing for empty values to blank them

                        } else if((prop == 'software_scan_running_processes' || prop == 'software_scan_jar' || prop == 'http_ssl_verify' || prop == 'saas_chrome_enabled') && d[prop] == 1){
                            rows = rows + '<tr><th>'+i18n.t('snowagent.'+prop)+'</th><td><span class="label label-success">'+i18n.t('yes')+'</span></td></tr>';
                        } else if((prop == 'software_scan_running_processes' || prop == 'software_scan_jar' || prop == 'http_ssl_verify' || prop == 'saas_chrome_enabled') && d[prop] == 0){
                            rows = rows + '<tr><th>'+i18n.t('snowagent.'+prop)+'</th><td><span class="label label-danger">'+i18n.t('no')+'</span></td></tr>';
                        } else if((prop == 'software_scan_running_processes' || prop == 'software_scan_jar' || prop == 'http_ssl_verify' || prop == 'saas_chrome_enabled') && (d[prop] == null || d[prop] === '')){
                            rows = rows + '<tr><th>'+i18n.t('snowagent.'+prop)+'</th><td><span class="label label-warning">Unknown</span></td></tr>';
                        } else if((prop == 'version_long')){
                            // rows = rows + '<tr><th>'+i18n.t('snowagent.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        } else if(prop == 'scan_include_paths' || prop == 'scan_exclude_paths'){
                            // Format scan paths with better readability - show recursive status clearly
                            var formattedPaths = d[prop].replace(/true:/g, '<span class="label label-success">Recursive</span> ').replace(/false:/g, '<span class="label label-default">Non-recursive</span> ').replace(/,/g, '<br>');
                            rows = rows + '<tr><th>'+i18n.t('snowagent.'+prop)+'</th><td>'+formattedPaths+'</td></tr>';
                        } else {
                            // Escape HTML to prevent XSS
                            var escapedValue = $('<div>').text(d[prop]).html();
                            rows = rows + '<tr><th>'+i18n.t('snowagent.'+prop)+'</th><td>'+escapedValue+'</td></tr>';
                        }
                    }
                }
                $('#snowagent-tab')
                    .append($('<div style="max-width:575px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            })
        }
	});
});
</script>
