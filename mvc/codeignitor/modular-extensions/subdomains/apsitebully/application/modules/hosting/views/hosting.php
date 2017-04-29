<form method="post" action="https://orders.brainhost.com/hosting" id="spinner_form">

	<section class="step1">
		<h2><?php echo $this->lang->line('hosting_watch_video'); ?></h2>

		<a 
			 href="http://setup.brainhost.com/resources/modules/free/assets/vid/hosting.flv"  
			 style="display:block;margin:0 auto;width:350px;height:250px"  
			 id="player">  
		</a>

		<?php /*
		<video width="350" height="250" controls="controls">
			<source src="vid/brainhost.mp4" type="video/mp4" />
			<a 
				 href="http://setup.brainhost.com/resources/modules/free/assets/vid/content.flv"  
				 style="display:block;width:350px;height:250px"  
				 id="player"> 
			</a>
		</video>
		*/ ?>

	</section>
	<section class="step2">
		<h2><?php echo $this->lang->line('hosting_fill_form_title'); ?></h2>
		
		<fieldset>
			<legend><?php echo $this->lang->line('hosting_fill_form'); ?></legend>
			<div id="error"></div>
			<div class="half">
				<label for="txtName"><?php echo $this->lang->line('hosting_label_name'); ?></label>
				<input type="text" id="txtName" name="txtName" />
			</div>
			<div class="half">
				<label for="txtEmail"><?php echo $this->lang->line('hosting_label_email'); ?></label>
				<input type="email" id="txtEmail" name="txtEmail" />
			</div>
			<div class="half">
				<label for="txtTopic"><?php echo $this->lang->line('hosting_label_topic'); ?></label>
				<input type="text" id="txtTopic" name="txtTopic" placeholder="<?php echo $this->lang->line('hosting_char_limit'); ?>" maxlength="30" />
			</div>
			<div class="half">
				<a href="#" id="btn-domain"><?php echo $this->lang->line('hosting_get_domain'); ?></a>
			</div>
			<div class="last" id="pnl-domain">
				<label for="txtDomain"><?php echo $this->lang->line('hosting_best_rank'); ?></label>
				<input type="text" id="txtDomain" placeholder="<?php echo $this->lang->line('hosting_example'); ?>" disabled="disabled" />

				<input id="hdnDomain" type="hidden" name="txtDomain" value="" />

				<a href="#" id="btn-spin"><?php echo $this->lang->line('hosting_spin'); ?></a>
			</div>
			<div id="pnl-results"></div>
			<div id="pnl-loading"></div>
		</fieldset>

	</section>
	<aside id="signup">

		<span style="visibility:hidden;"><?php echo $this->lang->line('hosting_not_setup'); ?></span>

		<button id="btn-hosting"><?php echo $this->lang->line('hosting_continue'); ?></button>

	</aside>

</form>