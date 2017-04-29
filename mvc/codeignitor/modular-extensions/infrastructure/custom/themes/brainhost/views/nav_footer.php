<footer id="footer">

	<nav class="c-hosting">

		<h2><?php echo $this->lang->line('brand_footer_heading_hosting'); ?></h2>

		<ol>

			<li><a href="<?php echo $this->anchors->get_link('weblock'); ?>"><?php echo $this->anchors->get_text('weblock'); ?></a></li>

			<li><a href="<?php echo $this->anchors->get_link('jobs'); ?>"><?php echo $this->anchors->get_text('jobs'); ?></a></li>

			<li><a href="<?php echo $this->anchors->get_link('news'); ?>"><?php echo $this->anchors->get_text('news'); ?></a></li>

			<li><a href="<?php echo $this->anchors->get_link('blog'); ?>"><?php echo $this->anchors->get_text('blog'); ?></a></li>

		</ol>

	</nav>

	<nav class="c-services">

		<h2><?php echo $this->lang->line('brand_footer_heading_products'); ?></h2>

		<ol>

			<li><a href="<?php echo $this->anchors->get_link('funnel'); ?>"><?php echo $this->anchors->get_text('funnel'); ?></a></li>

			<li><a href="<?php echo $this->anchors->get_link('about'); ?>"><?php echo $this->anchors->get_text('about'); ?></a></li>

			<li><a href="<?php echo $this->anchors->get_link('why'); ?>"><?php echo $this->anchors->get_text('why'); ?></a></li>

			<li><a href="<?php echo $this->anchors->get_link('affiliates'); ?>"><?php echo $this->anchors->get_text('affiliates'); ?></a></li>

			<li><a href="<?php echo $this->anchors->get_link('partners'); ?>"><?php echo $this->anchors->get_text('partners'); ?></a></li>

		</ol>

	</nav>

	<nav class="c-help">

		<h2><?php echo $this->lang->line('brand_footer_heading_help'); ?></h2>

		<ol>

			<li><a href="<?php echo $this->anchors->get_link('support'); ?>"><?php echo $this->anchors->get_text('support'); ?></a></li>

			<li><a href="<?php echo $this->anchors->get_link('login'); ?>"><?php echo $this->anchors->get_text('login'); ?></a></li>

			<li><a href="<?php echo $this->anchors->get_link('contact'); ?>"><?php echo $this->anchors->get_text('contact'); ?></a></li>

		</ol>

	</nav>

	<div class="c-social">

		<h2><?php echo $this->lang->line('brand_footer_heading_social'); ?></h2>

		<a href="<?php echo $this->anchors->get_link('twitter'); ?>" target="_blank"><img src="/resources/brainhost/img/icon-twitter.png" alt="Follow us on Twitter"></a>

		<a href="<?php echo $this->anchors->get_link('facebook'); ?>" target="_blank"><img src="/resources/brainhost/img/icon-facebook.png" alt="Like us on Facebook"></a>

		<a href="<?php echo $this->anchors->get_link('blog'); ?>" target="_blank"><img src="/resources/brainhost/img/icon-blog.png" alt="View our blog"></a>

		<g:plusone annotation="bubble" href="<?php echo $this->anchors->get_link('homepage'); ?>"></g:plusone>

	</div>

	<div class="c-signup">

		<h2><?php echo $this->lang->line('brand_footer_heading_signup'); ?></h2>

		<a href="<?php echo $this->anchors->get_link('funnel'); ?>" class="btn-signup"><?php echo $this->anchors->get_text('funnel'); ?></a>

	</div>

</footer>