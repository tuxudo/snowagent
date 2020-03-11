<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="snowagent_scan_jar-widget">
        <div id="snowagent_scan_jar-widget" class="panel-heading" data-container="body">
            <h3 class="panel-title"><i class="fa fa-coffee"></i> 
                <span data-i18n="snowagent.software_scan_jar"></span>
                <list-link data-url="/show/listing/snowagent/snowagent"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/snowagent/get_scan_jar', function( data ) {
        if(data.error){
            //alert(data.error);
            return;
        }

        var panel = $('#snowagent_scan_jar-widget div.panel-body'),
        baseUrl = appUrl + '/show/listing/snowagent/snowagent/#';
        panel.empty();
        // Set blocks, disable if zero
        if(data.off != "0"){
            panel.append(' <a href="'+baseUrl+'software_scan_jar = 0" class="btn btn-info"><span class="bigger-150">'+data.off+'</span><br>&nbsp;&nbsp;&nbsp;'+i18n.t('no')+'&nbsp;&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'software_scan_jar = 0" class="btn btn-info disabled"><span class="bigger-150">'+data.off+'</span><br>&nbsp;&nbsp;&nbsp;'+i18n.t('no')+'&nbsp;&nbsp;&nbsp;</a>');
        }
        if(data.on != "0"){
            panel.append(' <a href="'+baseUrl+'software_scan_jar = 1" class="btn btn-success"><span class="bigger-150">'+data.on+'</span><br>&nbsp;&nbsp;&nbsp;'+i18n.t('yes')+'&nbsp;&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'software_scan_jar = 1" class="btn btn-success disabled"><span class="bigger-150">'+data.on+'</span><br>&nbsp;&nbsp;&nbsp;'+i18n.t('yes')+'&nbsp;&nbsp;&nbsp;</a>');
        }
    });
});

</script>
