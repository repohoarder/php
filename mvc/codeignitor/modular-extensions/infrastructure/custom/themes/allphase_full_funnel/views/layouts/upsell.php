<?php 
$this->lang->load('bonus'); ?>

<?php echo $this->template->load_view('header.php'); ?>
<?php echo $this->template->load_view('branding.php'); ?>
<div id="t-main" role="main">
	<div class="center-width">
		<?php echo $this->template->load_view('errors.php'); ?>
		<?php echo $template['body']; ?>
	</div>
</div>
<?php echo $this->template->load_view('footer.php'); ?>