<html>
	
	<?php echo $this->template->load_view('head.php'); ?>

<body class="menu_hover">

	<div id="loading_layer" style="display:none"><img src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/img/ajax_loader.gif" alt=""></div>

	<?php echo  $this->template->load_view('switcher.php'); ?>

	<div id="maincontainer" class="clearfix">

		<?php echo  $this->template->load_view('navigation.php'); ?>

