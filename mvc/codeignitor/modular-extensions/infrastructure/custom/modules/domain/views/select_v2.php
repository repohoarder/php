<?php
$this->load->helper('url');

// initialize varibales
$form_submission	= '';
$attributes			= array(
	'method'	=> 'POST',
	'id'		=> 'spinner_form'
);

// open the form
echo form_open(
	$form_submission,
	$attributes,
	array('action_id' => 36)	// Hidden Fields
);

?>
			<h1><?php echo $this->lang->line('hosting_title'); ?></h1>
			<section class="step1">
				<h2><?php echo $this->lang->line('hosting_domain'); ?></h2>
					<a 
						 href="<?php echo site_url('/resources/modules/free/assets/vid/hosting.flv'); ?>"  
						 style="display:block;margin:0 auto;width:500px;height:275px"  
						 id="player"> 
					</a>
			</section>
			<section class="step2">
				<fieldset>
					<legend><?php echo $this->lang->line('hosting_fill_form_title'); ?></legend>
					<?php
					/*
						if(isset($_GET['error'])) { ?>
							<div id="error" style="display:block;">Sorry, that domain is no longer available.</div>
						<?php } else { ?>
							<div id="error"></div>
						<?php } 
					*/
					?>
					<div class="half">
						<label for="full_name"><?php echo $this->lang->line('hosting_label_name'); ?></label>
						<?php
						echo form_input(array(
							'name'		=> 'full_name',
							'type'		=> 'text',
							'id'		=> 'full_name'
						));
						?>
						<!-- <input type="text" id="full_name" name="full_name" /> -->
					</div>
					<div class="half">
						<label for="email"><?php echo $this->lang->line('hosting_label_email'); ?></label>
						<?php
						echo form_input(array(
							'name'		=> 'email',
							'type'		=> 'email',
							'id'		=> 'email'
						));
						?>
						<!-- <input type="email" id="email" name="email" /> -->
					</div>
					<div>
						<label for="txtTopic"><?php echo $this->lang->line('hosting_label_topic'); ?></label>
						<?php
						echo form_input(array(
							'name'			=> 'txtTopic',
							'type'			=> 'text',
							'id'			=> 'txtTopic',
							'placeholder'	=> $this->lang->line('hosting_char_limit'),
							'maxlength'		=> 30
						));
						?>
						<!-- <input type="text" id="txtTopic" name="txtTopic" placeholder="<?php echo $this->lang->line('hosting_char_limit'); ?>" maxlength="30" /> -->
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
						<label for="core_domain"><?php echo $this->lang->line('hosting_best_rank'); ?></label>
						<?php
						echo form_input(array(
							'name'			=> 'core_domain',
							'type'			=> 'text',
							'id'			=> 'core_domain',
							'placeholder'	=> $this->lang->line('hosting_example'),
							'disabled'		=> TRUE
						));
						?>
						<!-- <input type="text" id="core_domain" placeholder="<?php echo $this->lang->line('hosting_example'); ?>" disabled="disabled" /> -->
				
						<a href="#" id="btn-spin"><?php echo $this->lang->line('hosting_spin'); ?></a>
					</div>
					<div id="pnl-loading"></div>
				</fieldset>
				<a href="#" id="ext-link"><?php echo $this->lang->line('hosting_choose_domain'); ?></a>
			</section>
			<aside id="signup">
				<span><?php echo $this->lang->line('hosting_sign_up'); ?></span>
				
				
				<?php
				echo form_input(array(
					'name'			=> 'submit',
					'type'			=> 'submit',
					'id'			=> 'btn-hosting',
					'value'			=> $this->lang->line('hosting_use_domain')
				));
				?>
				<!-- <button id="btn-hosting"><?php echo $this->lang->line('hosting_use_domain'); ?></button> -->
			</aside>
				<?php
				echo form_input(array(
					'name'			=> 'core_domain',
					'type'			=> 'hidden',
					'id'			=> 'hdnDomain'
				));
				?>
				<!-- <input id="hdnDomain" type="hidden" name="core_domain" value="" /> -->
			<?php

			echo form_close();