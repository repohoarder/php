<?php echo $this->template->load_view('header.php'); ?>
<?php echo $this->template->load_view('branding.php'); ?>
<div id="t-main" role="main">
	<div class="center-width">
		<?php echo $this->template->load_view('errors.php'); ?>
		<?php echo $template['body']; ?>
	</div>
</div>

<!-- Start footer -->
	</body>

	<?php
	echo $template['footermeta'];
	$this->load->config('debug');
	if (in_array($this->session->userdata('ip_address'), $this->config->item('debug_ips'))): ?>
		<script type="text/javascript" src="<?php echo $subdir; ?>/resources/brainhost/js/debugger.js"></script>
		<?php 
	endif;
	?>
	
</html>
