		
		
		<form method="post" action="https://orders.brainhost.com/hosting" id="spinner_form">
			<h1><?php echo $this->lang->line('hosting_title'); ?></h1>
			<section class="step1">
				<h2><?php echo $this->lang->line('hosting_domain'); ?></h2>
					<a 
						 href="http://setup.brainhost.com/resources/modules/free/assets/vid/hosting.flv"  
						 style="display:block;margin:0 auto;width:500px;height:275px"  
						 id="player"> 
					</a>
			</section>
			<section class="step2">
				<fieldset>
					<legend><?php echo $this->lang->line('hosting_fill_form_title'); ?></legend>
					<?php
						if(isset($_GET['error'])) { ?>
							<div id="error" style="display:block;">Sorry, that domain is no longer available.</div>
						<?php } else { ?>
							<div id="error"></div>
						<?php } ?>
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
						<a href="#" id="btn-domain"><?php echo $this->lang->line('hosting_btn_results'); ?></a>
					</div>
					<div id="pnl-choose-domain">
						<fieldset>
							<legend><?php echo $this->lang->line('hosting_select_domain'); ?></legend>
							<div>
								<label for="txtCustomDomain"><?php echo $this->lang->line('hosting_select_domain'); ?></label>
								<strong>www.</strong>
								<input type="text" id="txtCustomDomain" name="txtCustomDomain" />
								<select id="selTLD" name="selTLD">
									<option value="com">.com</option>
									<option value="net">.net</option>
									<option value="org">.org</option>
									<option value="biz">.biz</option>
									<option value="info">.info</option>
								</select>
								<a href="#" id="btn-custom-domain" name="btn-custom-domain">Check Availability</a>
							</div>
						</fieldset>
					</div>
					<div class="last" id="pnl-available">
						<span id="spanDomain"></span>
						<span class="available"><?php echo $this->lang->line('hosting_available'); ?></span>
					</div>
					<div id="pnl-results"></div>
					<div class="last" id="pnl-domain">
						<label for="txtDomain"><?php echo $this->lang->line('hosting_best_rank'); ?></label>
						<input type="text" id="txtDomain" placeholder="<?php echo $this->lang->line('hosting_example'); ?>" disabled="disabled" />
				
						<a href="#" id="btn-spin"><?php echo $this->lang->line('hosting_spin'); ?></a>
					</div>
					<div id="pnl-loading"></div>
				</fieldset>
				<a href="http://orders.brainhost.com" id="ext-link"><?php echo $this->lang->line('hosting_choose_domain'); ?></a>
			</section>
			<aside id="signup">
				<span><?php echo $this->lang->line('hosting_sign_up'); ?></span>
				
				
				<button id="btn-hosting"><?php echo $this->lang->line('hosting_use_domain'); ?></button>
			</aside>
			<input type="hidden" name="txtDomain" id="hdnDomain" />
		</form>