<div id="snowagent-tab"></div>
<h2 data-i18n="snowagent.snowagent"></h2>

<div id="snowagent-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
    
	$.getJSON(appUrl + '/module/snowagent/get_data/' + serialNumber, function(data){
        
        if( data.length == 0 ){
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
                        if (d[prop] == ''){
                            // Do nothing for empty values to blank them

                        } else if((prop == 'software_scan_running_processes' || prop == 'software_scan_jar' || prop == 'http_ssl_verify') && d[prop] == 1){
                            rows = rows + '<tr><th>'+i18n.t('snowagent.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        } else if((prop == 'software_scan_running_processes' || prop == 'software_scan_jar' || prop == 'http_ssl_verify') && d[prop] == 0){
                            rows = rows + '<tr><th>'+i18n.t('snowagent.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';

                        } else {
                            rows = rows + '<tr><th>'+i18n.t('snowagent.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
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
