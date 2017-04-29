<?php echo $this->template->load_view('head.php'); ?>

<?php 

$data['home_link'] = '#';
echo $this->template->load_view('branding.php', $data); 

?>
	
	<div id="t-main">
		<div class="wrapper">
			<div id="page">

				<?php echo $this->template->load_view('errors'); ?>

				<?php echo $template['body']; ?>
				
			</div><!-- /page -->
		</div><!-- /wrapper -->
	</div><!-- /t-main -->
	
<?php echo $this->template->load_view('footer.php'); ?>