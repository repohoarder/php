<?php

$style_version = (isset($style_version) ? $style_version : '');

$data['layout_style']  = '/resources/brainhost/css/funnel'.$style_version.'.css';

echo $this->template->load_view('head.php', $data); ?>

<?php 

$data['home_link'] = '#';
echo $this->template->load_view('branding.php', $data); 

?>
	
	<div id="t-main">
		<div class="wrapper">
			<div id="page">

				<?php echo $this->template->load_view('errors.php'); ?>
				
				<?php echo $template['body']; ?>
				
			</div><!-- /page -->
		</div><!-- /wrapper -->
	</div><!-- /t-main -->
	
<?php echo $this->template->load_view('footer.php'); ?>