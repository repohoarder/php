<?php
// fix for subdirectories
$subdir 	= ($this->config->item('subdir'))? $this->config->item('subdir'): '';
?>

		<footer id="t-footer">
			<div class="center-width">
				<p><span id="copyright">&copy;</span> <?php echo date('Y'); ?> <!-- All Phase Web Hosting, LLC All Rights Reserved.--></p>
				<ul>
					<li><a href="<?php echo $subdir; ?>/pages/about"><?php echo $this->lang->line('about_us'); ?></a></li>
					<li><a href="<?php echo $subdir; ?>/pages/terms"><?php echo $this->lang->line('terms_and_conditions'); ?></a></li>
					<li><a href="<?php echo $subdir; ?>/pages/privacy"><?php echo $this->lang->line('privacy_policy'); ?></a></li>
				</ul>
			</div>
		</footer>
	
	<?php

	echo $template['footermeta'];

	$this->load->config('debug');
	
	if (in_array($this->session->userdata('ip_address'), $this->config->item('debug_ips'))): ?>

		<script type="text/javascript" src="/resources/brainhost/js/debugger.js"></script>

	<?php endif; 


	$pixels = $this->session->userdata('pixels');

	if (is_array($pixels)):

		$types = array(
			'all',	
			'funnel'
		);

		if (isset($display_pixel_type)):

			if ( ! is_array($display_pixel_type)):

				$display_pixel_type = array($display_pixel_type);

			endif;

			foreach ($display_pixel_type as $display_type):

				$types[] = $display_type;

			endforeach;

		endif;

		foreach ($types as $type):

			if ( ! isset($pixels[$type]) || ! is_array($pixels[$type])):

				continue;

			endif;

			foreach ($pixels[$type] as $pixel): ?>

				<!--Partner Tracking Pixel-->
				
				<?php echo $pixel;

			endforeach;

		endforeach;

	endif;

	?>

	</body>
	
</html>