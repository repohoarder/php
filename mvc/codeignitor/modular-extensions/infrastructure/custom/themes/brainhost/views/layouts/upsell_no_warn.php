<?php 
$this->lang->load('upsell');

echo $this->template->load_view('head.php'); ?>

<div id="one_column_thin">
	<?php echo $this->template->load_view('branding.php'); ?>
		
		<div id="t-main">
			<div class="wrapper">
				<div id="page">
					
					<?php echo $template['body']; ?>
					
				</div><!-- /page -->
			</div><!-- /wrapper -->
		</div><!-- /t-main -->
</div>		
	<?php echo $this->template->load_view('footer.php'); ?>

