<header id="t-branding">
	<div class="wrapper">
		<a href="<?php echo $this->anchors->get_link('homepage'); ?>" class="logo">
			<img src="/resources/brainhost/img/logo.png" alt="<?php echo $this->lang->line('brand_company'); ?>" />
		</a>
		
		<?php echo $this->template->load_view('login_form'); ?>
		
	</div>
</header>