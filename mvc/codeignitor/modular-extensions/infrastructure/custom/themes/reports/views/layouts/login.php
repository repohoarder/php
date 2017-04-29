<?php echo $this->template->load_view('head.php'); ?>

<?php echo $this->template->load_view('header_login.php'); ?>
	
	<div id="t-main">
		<div class="wrapper">
			<div id="page">
				
				<?php echo $template['body']; ?>
				
			</div><!-- /page -->
		</div><!-- /wrapper -->
	</div><!-- /t-main --> 
	
<?php echo $this->template->load_view('footer_login.php'); ?> 