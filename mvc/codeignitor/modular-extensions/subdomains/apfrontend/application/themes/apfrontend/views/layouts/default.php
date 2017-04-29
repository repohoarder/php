<?php echo $this->template->load_view('header.php'); ?>
<?php /*echo $this->template->load_view('branding.php');*/ ?>
<div id="t-main" role="main" class="section-partner">
	<div class="center-width">
		<?php echo $template['body']; ?>
		<?php echo $this->template->load_view('sidebar.php'); ?>
	</div>
</div>
<?php /*echo $this->template->load_view('footer.php');*/ ?>
	</body>
</html>