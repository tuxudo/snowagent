<div class="col-lg-4 col-md-6">
	<div class="card" id="snowagent-versions-widget">
		<div class="card-header" data-container="body">
			<i class="fa fa-snowflake-o "></i>
			<span data-i18n="snowagent.snowagent_versions"></span>
			<a href="/show/listing/snowagent/snowagent" class="pull-right"><i class="fa fa-list"></i></a>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /card -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(){

	$.getJSON( appUrl + '/module/snowagent/get_versions', function( data ) {

		var list = $('#snowagent-versions-widget div.scroll-box').empty();

		if(data.length){
			// Sort on version number
			data.sort(function(a,b){
				return mr.naturalSort(b.version, a.version);
			});
			$.each(data, function(i,d){
				var badge = '<span class="badge pull-right">'+d.count+'</span>';
				d.version = d.version || i18n.t('unknown');
				list.append('<a href="'+appUrl+'/show/listing/snowagent/snowagent/#'+d.version+'" class="list-group-item">'+d.version+badge+'</a>')
			});
		}
		else {
			list.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
		}

	});
});
</script>
