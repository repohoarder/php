<hgroup>
	<h1 class="msg-hosting hide-text"><?php echo $this->lang->line('bonus_hosting_title'); ?></h1>
	<h2 class="msg-source"><?php echo $this->lang->line('bonus_hosting_description'); ?></h2>
	<p class="msg-se-desc"><img src="/resources/brainhost/img/img-hosting.jpg" alt="Google, Yahoo!, Bing" /></p>
</hgroup>
<p class="msg-work"><?php echo $this->lang->line('bonus_hosting_solution'); ?></p>
<form action="#" method="post">
	<select name="selHosting" id="selHosting">
		<option value=""><?php echo $this->lang->line('bonus_hosting_best_value'); ?></option>
	</select>
	<input type="submit" class="hosting hide-text" value="<?php echo $this->lang->line('bonus_seo_package_btn_add'); ?>" />
</form>
<span class="lbl-or hide-text">or</span>
<p class="lbl-nothanks"><a href="#"><?php echo $this->lang->line('bonus_hosting_no_thanks'); ?></a></p>