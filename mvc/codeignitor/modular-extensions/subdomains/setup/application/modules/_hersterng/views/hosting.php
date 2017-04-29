		
		
		<form method="post" action="https://orders.brainhost.com/hosting" id="spinner_form">
			<h1><?php echo $this->lang->line('hosting_title'); ?></h1>
			<section class="step1">
				<h2><?php echo $this->lang->line('hosting_domain'); ?></h2>
					<a 
						 href="http://8beeda049e5d49c340dd-9e0fc90ca393afce126174d62bc6e1d5.r61.cf1.rackcdn.com/Brain_Host_Domain_Short.flv"  
						 style="display:block;margin:0 auto;width:500px;height:275px"  
						 id="player"> 
					</a>
			</section>
			<section class="step2">
				<fieldset>
					<legend><?php echo $this->lang->line('hosting_fill_form_title'); ?></legend>
					<div id="error"></div>
					<div class="half">
						<label for="txtName"><?php echo $this->lang->line('hosting_label_name'); ?></label>
						<input type="text" id="txtName" name="txtName" />
					</div>
					<div class="half">
						<label for="txtEmail"><?php echo $this->lang->line('hosting_label_email'); ?></label>
						<input type="email" id="txtEmail" name="txtEmail" />
					</div>
					<div>
						<label for="txtTopic"><?php echo $this->lang->line('hosting_label_topic'); ?></label>
						<input type="text" id="txtTopic" name="txtTopic" placeholder="<?php echo $this->lang->line('hosting_char_limit'); ?>" maxlength="30" />
					</div>
					<div>
						<a href="#" id="btn-domain"><?php echo $this->lang->line('hosting_btn_results'); ?></a>
					</div>
					<div class="last" id="pnl-domain">
						<span id="txtDomain"></span>
						<span class="available"><?php echo $this->lang->line('hosting_available'); ?></span>
					</div>
					<div id="pnl-results"></div>
					<div id="pnl-loading"></div>
				</fieldset>
			</section>
			<aside id="signup">
				<span><?php echo $this->lang->line('hosting_sign_up'); ?></span>
				<a href="http://orders.brainhost.com" id="ext-link"><?php echo $this->lang->line('hosting_choose_domain'); ?></a>
				<button id="btn-hosting"><?php echo $this->lang->line('hosting_use_domain'); ?></button>
			</aside>
			<input type="hidden" name="hdnDomain" id="hdnDomain" />
		</form>