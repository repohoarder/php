
<?php
	
echo form_open(
	'',
	array( # attributes
		'id' => 'form-billing'
	),
	array( # hidden inputs
		'billing_form' => 1,
		'action_id'    => 18
	)
);

?>

	<h1>
		<?php echo $this->lang->line('billing_title'); ?> 
		<span>
			<a><?php echo $this->lang->line('billing_breadcrumb_step1'); ?></a> 
			&nbsp;&gt;</a>&nbsp; 
			<?php echo $this->lang->line('billing_breadcrumb_step2'); ?>
		</span>
	</h1>
	
	<?php /*
	<section class="congratulations">
		<h2><?php echo $this->lang->line('billing_congrats_title'); ?></h2>
		<strong><?php echo $this->lang->line('billing_congrats_intro'); ?></strong>
		<span class="timer">10:00:00</span>
		<input name="txtCounter" type="hidden" id="txtCounter" value="10:00:00" />
		<p><?php echo $this->lang->line('billing_congrats_warning'); ?></p>
	</section>
	 */ ?>

	<section>
		<div class="column c-features">
			<h2><?php echo $this->lang->line('billing_features_title'); ?></h2>

			<ol>
				<li>
					<strong>Choose Your Hosting Package</strong>
					<?php
						echo form_dropdown('hosting', $hosting_options, $fields['hosting']);
					?>
				</li>
			</ol>

			<?php /*
			<ol>
				<li>
					<strong>mydomainname.com <span>$9.95</span></strong>
					<ol>
						<li class="alt"><input type="checkbox" id="chkPrivacy1" name="chkPrivacy1" /><label for="chkPrivacy1">Domain Privacy <span>$11.99</span></label></li>
						<li><input type="checkbox" id="chkSEO1" name="chkSEO1" /><label for="chkSEO1">SEO Package <span>$11.99</span></label></li>
						<li class="alt"><input type="checkbox" id="chkOption1" name="chkOption1" /><label for="chkOption1">Another Upsell <span>$11.99</span></label></li>
					</ol>
				</li>
				<li class="alt">
					<strong>mydomainname.com <span>$9.95</span></strong>
					<ol>
						<li class="alt"><input type="checkbox" id="chkPrivacy2" name="chkPrivacy2" /><label for="chkPrivacy2">Domain Privacy <span>$11.99</span></label></li>
						<li><input type="checkbox" id="chkSEO2" name="chkSEO2" /><label for="chkSEO2">SEO Package <span>$11.99</span></label></li>
						<li class="alt"><input type="checkbox" id="chkOption2" name="chkOption2" /><label for="chkOption2">Another Upsell <span>$11.99</span></label></li>
					</ol>
				</li>
				<li>
					<strong>mydomainname.com <span>$9.95</span></strong>
					<ol>
						<li class="alt"><input type="checkbox" id="chkPrivacy3" name="chkPrivacy3" /><label for="chkPrivacy3">Domain Privacy <span>$11.99</span></label></li>
						<li><input type="checkbox" id="chkSEO3" name="chkSEO3" /><label for="chkSEO3">SEO Package <span>$11.99</span></label></li>
						<li class="alt"><input type="checkbox" id="chkOption3" name="chkOption3" /><label for="chkOption3">Another Upsell <span>$11.99</span></label></li>
					</ol>
				</li>
			</ol>
			
			*/ ?>

		</div>
		<div class="column c-guarantee">
			<h2><?php echo $this->lang->line('billing_guarantee_title'); ?></h2>
			<h3><?php echo $this->lang->line('billing_guarantee_subtitle'); ?></h3>
			<div class="award">
				<img src="/resources/brainhost/img/img-guarantee.jpg" alt="<?php echo $this->lang->line('billing_guarantee_subtitle'); ?>" />
				<p><?php echo $this->lang->line('billing_guarantee_desc'); ?></p>
			</div>
			<p><?php echo $this->lang->line('billing_guarantee_sent_to'); ?></p>
			<p><?php echo $this->lang->line('billing_guarantee_match'); ?></p>
			<fieldset>
				<legend><?php echo $this->lang->line('billing_guarantee_legend'); ?></legend>

				<?php

					$tos_checked = ( ! $fields['tos_agreement'] && $this->input->post('billing_form')) ? '' : 'checked';

					echo form_checkbox(
						array(
							'name'    => 'tos_agreement',
							'value'   => '1',
							'checked' => $tos_checked,
							'id'      => 'tos_agreement',
							'class'   => 'required',
						)
					);

					// <input type="checkbox" id="tos_agreement" name="tos_agreement" class="required" />

				?>

				<label for="tos_agreement"><?php echo $this->lang->line('billing_guarantee_agree'); ?> <a href="http://www.brainhost.com/terms-of-service/" class="lightview" data-lightview-type="iframe" data-lightview-options="width: 1000"><?php echo $this->lang->line('billing_guarantee_terms'); ?></a> and <a href="http://www.brainhost.com/privacy/" class="lightview" data-lightview-type="iframe" data-lightview-options="width: 1000"><?php echo $this->lang->line('billing_guarantee_privacy'); ?></a>.</label>

			</fieldset>
		</div>
		<div class="column c-info">
			<h2><?php echo $this->lang->line('billing_info_title'); ?></h2>
			<fieldset class="info-account">
				<legend><?php echo $this->lang->line('billing_info_title'); ?></legend>
				<div class="row">
					<label for="first_name"><?php echo $this->lang->line('billing_info_lblFName'); ?></label>

					<?php 

					echo form_input(array(
						'id'      => 'first_name',
						'value'   => $fields['first_name'],
						'name'    => 'first_name',
						'class'   => 'required',							
						'size'    => 10
					)); 

					// <input type="text" id="first_name" name="first_name" class="required" />
					
					?>
					
				</div>
				<div class="row">
					<label for="last_name"><?php echo $this->lang->line('billing_info_lblLName'); ?></label>

					<?php 

					echo form_input(array(
						'id'      => 'last_name',
						'value'   => $fields['last_name'],
						'name'    => 'last_name',
						'class'   => 'required',							
						'size'    => 10
					)); 

					// <input type="text" id="last_name" name="last_name" class="required" />
					
					?>

				</div>
				<div class="row">
					<label for="email"><?php echo $this->lang->line('billing_info_lblEmail'); ?></label>

					<?php 

					echo form_input(array(
						'id'      => 'email',
						'value'   => $fields['email'],
						'name'    => 'email',
						'class'   => 'required',							
						'size'    => 10
					)); 

					// <input type="text" id="email" name="email" class="required" />
					
					?>

				</div>
				<div class="row">
					<label for="phone"><?php echo $this->lang->line('billing_info_lblPhone'); ?></label>

					<?php 

					echo form_input(array(
						'id'      => 'phone',
						'value'   => $fields['phone'],
						'name'    => 'phone',
						'class'   => 'required',							
						'size'    => 10
					)); 

					// <input type="text" id="phone" name="phone" class="required" />
					
					?>

				</div>
				<?php /*
				<div class="row">
					<label for="txtMPhone"><?php echo $this->lang->line('billing_info_lblMPhone'); ?></label>
					<input type="text" id="txtMPhone" name="txtMPhone" />
				</div>
				*/ ?>
				<div class="row">
					<label for="country"><?php echo $this->lang->line('billing_info_lblCountry'); ?></label>

					<?php
					
					$this->load->config('address_validation');

					$countries = array(
						'Please Select' => $this->config->item('addr_common_countries'),
						'All Countries' => $this->config->item('addr_countries')
					);

					//$countries = array_merge(array(''=>'Please Select'), $this->config->item('addr_countries'));
					
					echo form_dropdown(
						'country', 
						$countries,
						$fields['country'],
						'id="country" class="required"'
					);

					?>
				</div>
				<div class="row">
					<label for="address"><?php echo $this->lang->line('billing_info_lblAddress'); ?></label>
					
					<?php 

					echo form_input(array(
						'id'      => 'address',
						'value'   => $fields['address'],
						'name'    => 'address',
						'class'   => 'required',							
						'size'    => 10
					)); 

					// <input type="text" id="address" name="address" class="required" />
					
					?>

				</div>
				<div class="row">
					<label for="city"><?php echo $this->lang->line('billing_info_lblCity'); ?></label>
					
					<?php 

					echo form_input(array(
						'id'      => 'city',
						'value'   => $fields['city'],
						'name'    => 'city',
						'class'   => 'required',							
						'size'    => 10
					)); 

					// <input type="text" id="city" name="city" class="required" />
					
					?>

				</div>
				<div class="row">
					<label for="state"><?php echo $this->lang->line('billing_info_lblState'); ?></label>

					<?php
						
					$state_options = array_merge(array('' => 'Please Select'),$this->config->item('addr_states'));
					
					$uk_states = array();
					
					foreach ($state_options['GB'] as $uk_list):
						
						$uk_states = array_merge($uk_states,$uk_list);
						
					endforeach;
					
					$state_options['GB'] = $uk_states;

					# Select State / Province
					echo form_dropdown(
						'state', 
						$state_options,
						$fields['state'],
						'id="state" class="required"'
					);

					?>

				</div>
				<div class="row">
					<label for="zipcode"><?php echo $this->lang->line('billing_info_lblZip'); ?></label>
					
					<?php 

					echo form_input(array(
						'id'      => 'zipcode',
						'value'   => $fields['zipcode'],
						'name'    => 'zipcode',
						'class'   => 'required',							
						'size'    => 10
					)); 

					// <input type="text" id="zipcode" name="zipcode" class="required" />
					
					?>

				</div>
			</fieldset>
			<h2><?php echo $this->lang->line('billing_card_title'); ?></h2>
			<fieldset class="info-payment">
				<legend><?php echo $this->lang->line('billing_card_title'); ?></legend>
				<div class="row">
					<label for="cc_num"><?php echo $this->lang->line('billing_card_lblCardNum'); ?></label>
					
					<?php 

					echo form_input(array(
						'id'           => 'cc_num',
						'value'        => '',
						'name'         => 'cc_num',
						'class'        => 'required',
						'size'         => 10,
						'autocomplete' => 'off'
					)); 

					// <input type="text" id="cc_num" name="cc_num" class="required" />
					
					?>

				</div>
				<div class="row">
					<label for="cc_exp_mo"><?php echo $this->lang->line('billing_card_lblExpiration'); ?></label>

					<?php

					$years = range(date('Y'),date('Y')+10);

					echo form_dropdown(
						'cc_exp_yr', 
						array_combine($years,$years),
						date('Y'),
						'id="cc_exp_yr" class="required"'
					);

					$range = range(1,12);

					foreach ($range as $key=>$mo):

						$key = str_pad($mo,2,"0",STR_PAD_LEFT);

						$months[$key] = $key.' '.date('M',mktime(0, 0, 0, $mo, 1));

					endforeach;


					echo form_dropdown(
						'cc_exp_mo', 
						$months,
						'01',
						'id="cc_exp_mo" class="required"'
					);

					?>

				</div>
				<div class="row">
					<label for="cc_security"><?php echo $this->lang->line('billing_card_lblCVV'); ?></label>
					<?php 

					echo form_input(array(
						'id'           => 'cc_security',
						'value'        => '',
						'name'         => 'cc_security',
						'class'        => 'cvv required',
						'autocomplete' => 'off'
					)); 

					// <input type="text" id="cc_security" name="cc_security" class="required" />
					
					?>
					<a href="#"><?php echo $this->lang->line('billing_card_what'); ?></a>
					<input type="submit" value="<?php echo $this->lang->line('billing_btnContinue'); ?>">
				</div>
			</fieldset>
		</div>
	</section>
	<aside class="security">
		

		<div style="width:301px;margin:0 auto;">			
					
			<div style="width:143px;float:left;padding-top:21px;margin-right:2px;" class="truste">
		
				<a href="https://privacy-policy.truste.com/click-with-confidence/wps/en/www.brainhost.com/seal_m" title="TRUSTe online privacy certification" target="_blank" tabindex="-1" class="splash"><img style="border: none;" src="https://privacy-policy.truste.com/certified-seal/wps/en/www.brainhost.com/seal_m.png" alt="TRUSTe online privacy certification"></a>
		
			</div>
			
		
			<div style="width:130px;margin:0 13px;float:left;" class="vsign">
						
				<a href="https://trustsealinfo.verisign.com/splash?form_file=fdf/splash.fdf&amp;dn=brainhost.com&amp;lang=en" target="_blank" tabindex="-1" class="splash"><img style="width: 130px; height: 88px;" name="seal" src="https://seal.verisign.com/getseal?at=0&amp;sealid=0&amp;dn=brainhost.com&amp;lang=en" oncontextmenu="return false;" alt="Click to Verify" title="Click to Verify - This site has chosen a VeriSign SSL Certificate to improve Web site security"></a>
						
			</div>			

			<div style="clear:both;"></div>
		</div>


	</aside>


<script src="//configusa.veinteractive.com/tags/3C652ECF/8FF7/4B56/A389/1C34EFF0971B/tag.js" type="text/javascript" async></script>


	

<?php echo form_close();