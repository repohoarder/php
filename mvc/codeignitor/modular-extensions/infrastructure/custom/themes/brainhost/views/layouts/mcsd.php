<?php 

echo $this->template->load_view('head.php'); 

$mcsd_params['image']        = $image;
$mcsd_params['brand']        = $brand;
$mcsd_params['affiliate_id'] = $affiliate_id;

echo $this->template->load_view('branding.php', $mcsd_params); ?>
	
	<div id="t-main">

		<div class="wrapper">

			<div id="page">		

				<?php echo $template['body']; ?>

			</div><!-- /page -->

		</div><!-- /wrapper -->

	</div><!-- /t-main -->

<?php echo $this->template->load_view('footer.php'); ?>