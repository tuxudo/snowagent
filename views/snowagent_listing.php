<?php $this->view('partials/head'); ?>

<div class="container">
  <div class="row">
	<div class="col-lg-12">
	  <h3><span data-i18n="snowagent.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>
	  <table class="table table-striped table-condensed table-bordered">
		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="snowagent.sitename" data-colname='snowagent.sitename'></th>
			<th data-i18n="snowagent.configname" data-colname='snowagent.configname'></th>
			<th data-i18n="snowagent.server_address" data-colname='snowagent.server_address'></th>
			<th data-i18n="snowagent.version" data-colname='snowagent.version'></th>
			<th data-i18n="snowagent.build" data-colname='snowagent.build'></th>
			<th data-i18n="snowagent.rev" data-colname='snowagent.rev'></th>
			<th data-i18n="snowagent.http_ssl_verify" data-colname='snowagent.http_ssl_verify'></th>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="9" class="dataTables_empty"></td>
		  </tr>
		</tbody>
	  </table>
	</div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">

	$(document).on('appUpdate', function(e){

		var oTable = $('.table').DataTable();
		oTable.ajax.reload();
		return;

	});

	$(document).on('appReady', function(e, lang) {

        // Get modifiers from data attribute
        var mySort = [], // Initial sort
            hideThese = [], // Hidden columns
            col = 0, // Column counter
            columnDefs = [{ visible: false, targets: hideThese }]; //Column Definitions

        $('.table th').map(function(){

            columnDefs.push({name: $(this).data('colname'), targets: col, render: $.fn.dataTable.render.text()});

            if($(this).data('sort')){
              mySort.push([col, $(this).data('sort')])
            }

            if($(this).data('hide')){
              hideThese.push(col);
            }

            col++
        });

	    oTable = $('.table').dataTable( {
            ajax: {
                url: appUrl + '/datatables/data',
                type: "POST",
                data: function(d){
                    d.mrColNotEmpty = "version";
                    
                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'snowagent.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });
                    }

                    if(d.search.value.match(/^software_scan_running_processes = \d$/))
                    {
                        // Add column specific search
                        d.columns[2].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }

                    if(d.search.value.match(/^software_scan_jar = \d$/))
                    {
                        // Add column specific search
                        d.columns[3].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }

                    if(d.search.value.match(/^http_ssl_verify = \d$/))
                    {
                        // Add column specific search
                        d.columns[4].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }

                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            order: mySort,
            columnDefs: columnDefs,
		    createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = mr.getClientDetailLink(name, sn, '#tab_snowagent-tab');
	        	$('td:eq(0)', nRow).html(link);
                
	        	// http_ssl_verify
	        	var colvar=$('td:eq(8)', nRow).html();
	        	colvar = colvar == '1' ? '<span class="label label-success">'+i18n.t('yes')+'</span>' :
	        	(colvar === '0' ? '<span class="label label-danger">'+i18n.t('no')+'</span>' : '')
	        	$('td:eq(8)', nRow).html(colvar)
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
