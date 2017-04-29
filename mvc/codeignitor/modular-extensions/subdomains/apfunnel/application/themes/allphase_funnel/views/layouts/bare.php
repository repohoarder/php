<?php echo $this->template->load_view('header.php'); ?>
<?php echo $this->template->load_view('branding.php'); ?>
<div id="t-main" role="main">
	<div class="center-width">
		<div id="page">
			<?php echo $this->template->load_view('errors.php'); ?>

			<?php echo $template['body']; ?>
		</div>
	</div>
</div>
<?php echo $this->template->load_view('footer.php'); ?>