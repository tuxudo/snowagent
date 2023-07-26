<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container-fluid">
    
  <div class="row pt-4">
    <?php $widget->view($this, 'snowagent_http_ssl_verify'); ?>
    <?php $widget->view($this, 'snowagent_scan_jar'); ?>
    <?php $widget->view($this, 'snowagent_scan_running_processes'); ?>
  </div> <!-- /row -->
    
  <div class="row pt-4">
    <?php $widget->view($this, 'snowagent_versions'); ?>
  </div> <!-- /row -->
    
</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
