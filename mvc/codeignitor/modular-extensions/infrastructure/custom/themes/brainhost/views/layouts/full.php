<?php 

echo $this->template->load_view('head.php'); 

$data['login'] = FALSE;
# $data['login'] = TRUE;

echo $this->template->load_view('branding.php', $data); ?>

<?php echo $this->template->load_view('nav_main.php'); ?>
	
	<div id="t-main">
		<div class="wrapper">
			<div id="page">
				
				<?php echo $this->template->load_view('errors.php'); ?>
				
				<?php echo $template['body']; ?>
				
				<?php echo $this->template->load_view('testimonials'); ?>
				
				<?php echo $this->template->load_view('nav_footer'); ?>
				
				<?php echo $this->template->load_view('seo_blocks'); ?>
				
			</div><!-- /page -->
		</div><!-- /wrapper -->
	</div><!-- /t-main -->
	
<?php echo $this->template->load_view('footer.php'); ?>