	
<?php 
// if user has iframe code, don't show header
if (strstr($_SERVER['QUERY_STRING'],'frame=1') === FALSE):
?>
	<header id="t-branding">	
		<div class="wrapper">
			<a href="<?php echo $this->anchors->get_link('homepage'); ?>" class="logo"<?php if ($affiliate_id=='102019'): ?>style="margin-top:0;"<?php endif; ?>>	
				<img src="<?php echo $image; ?>" alt="<?php echo $brand; ?>" />	
			</a>	
		</div>	
	</header>
<?php 
endif;
?>