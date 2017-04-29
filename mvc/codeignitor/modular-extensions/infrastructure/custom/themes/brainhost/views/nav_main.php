<nav id="t-nav-main">

	<div class="wrapper">		

		<?php echo $this->template->load_view('menu_main'); ?>

		<div class="social">

			<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo $this->anchors->get_link('twitter'); ?>" data-text="<?php echo $this->lang->line('brand_twitter_text'); ?>" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>

		</div>

		<div class="social">

			<div id="fb-root"></div>

			<script src="http://connect.facebook.net/en_US/all.js#appId=181309525271554&amp;xfbml=1"></script>

			<fb:like href="<?php echo $this->anchors->get_link('facebook'); ?>" send="false" layout="button_count" width="100" height="35" show_faces="true" action="like" font=""></fb:like>

		</div>

	</div>

</nav>