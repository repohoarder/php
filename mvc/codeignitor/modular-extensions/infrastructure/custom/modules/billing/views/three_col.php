<?php

if ( ! $this->input->post('billing_form')):

	$geoiped      = FALSE;
	
	$geoip_fields = array('country');

	$get_geoip = array(
		'country',
		'state',
		'city',
		'zipcode'
	);

	if ($get_geoip && is_array($get_geoip)):
		$geoip_fields = array_merge($get_geoip, $geoip_fields);
	endif;

	$keys = array(
		'country' => 'country_code',
		'state'   => 'region',
		'city'    => 'city',
		'zipcode' => 'postal_code'
	);

	$which_fields = array_combine($geoip_fields, array_fill(0, count($geoip_fields), NULL));
	$which_fields = array_filter(array_intersect_key($keys, $which_fields));

	$resp         = $this->platform->post(
		'/geoip/get_record',
		array(
			'ip_address' => ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? '98.100.69.22' : $_SERVER['REMOTE_ADDR'])
		)
	);

	if ($resp['success']): 

		foreach ($which_fields as $key => $geo_key): 

			if ($resp['data']['record']['country_code'] != 'US' && $key != 'country'):

				continue;

			endif;

			$fields[$key] = $resp['data']['record'][$geo_key];
			
			$geoiped      = TRUE;
			
		endforeach;

	endif; 

	if ($geoiped): ?>

		<input name="geoiped" type="hidden" value="1" id="geoiped" />

	<?php endif;

endif; 



echo form_open('',array('id' => 'form-billing'),array('billing_form' => 1,'action_id' => 91)); ?>

	<div id="top_heading">
		<h1>

			<?php

			$dom     = (isset($fields['core_domain']) ? $fields['core_domain'] : '');
			$brand   = $partner_data['website']['company_name'];
			$heading = $this->lang->line('billing_v1_congrats'). ' <em>' . $dom . '!</em>'; // should change this to pass to lang line

			if ($core_type != 'register'):

				$heading = 'Congratulations! <em>'.$dom.'</em> will soon be hosted at '.$brand.'.';

			endif;

			// custom lanty hack
			if ($this->session->userdata('partner_id') == '169' OR $this->session->userdata('partner_id') == '225' OR $this->session->userdata('partner_id') == '251'):

				$heading = 'Congratulations your discount was accepted!';

			endif;

			echo $heading;

			?>
		</h1>


		<?php 

		if ($core_type == 'register' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1'):

			$params = array(
				'domain' => $dom
			);

			if ($this->session->userdata('partner_id') == '169' OR $this->session->userdata('partner_id') == '225' OR $this->session->userdata('partner_id') == '251'):

				// custom lanty
				$this->load->view('billing/lanty_custom');

			else:

				// cuntdown timer
				$this->load->view('billing/countdown_timer', $params);

			endif;

		endif;?>

	</div>


	<section>
		<div class="column c-features">
			<h2><?php echo $this->lang->line('billing_features_title'); ?></h2>

			<ul style="color: #666;">


				<?php if (is_array($hosting_prices) && count($hosting_prices)): ?>

					<li class="nocheck">
						<?php

						## More custom lanty
						if ($partner_data['website']['partner_id'] != '251' AND $partner_data['website']['partner_id'] != '225' AND $partner_data['website']['partner_id'] != '169'):
						?>
						<label>Package:</label>
						<?php
						endif;
						?>

						<?php

	                    ## HACK FOR LANTY (default annual hosting package)
	                    if ($partner_data['website']['partner_id'] == '251' OR $partner_data['website']['partner_id'] == '225' OR $partner_data['website']['partner_id'] == '169')
	                    	$fields['hosting']	= 'annual';

						// custom lanty hack
	                    $hide_select 	= FALSE;	// variable that toggles hiding the hosting select dropdown
						if ($this->session->userdata('partner_id') == '169' OR $this->session->userdata('partner_id') == '225' OR $this->session->userdata('partner_id') == '251'):

							// display price
							//echo '<strong>'.ucwords($fields['hosting']).' ('.$hosting_prices[$fields['hosting']]['price'].'/mo.)</strong>';

							// show hidden input
							//echo '<input type="hidden" name="hosting" value="'.$fields['hosting'].'" data-hosting-package="'.$fields['hosting'].'" data-hosting-price="'.$hosting_prices[$fields['hosting']]['price'].'" data-setup-fee="'.$hosting_prices[$fields['hosting']]['setup_fee'].'" data-trial-discount="'.$hosting_prices[$fields['hosting']]['trial_discount'].'" />';

							// show hidden select 
							echo '
							<select class="hosting-package" name="hosting" style="visibility:hidden">
								<option value="'.$fields['hosting'].'" data-hosting-package="'.$fields['hosting'].'" data-hosting-price="'.$hosting_prices[$fields['hosting']]['price'].'" data-setup-fee="'.$hosting_prices[$fields['hosting']]['setup_fee'].'" data-trial-discount="'.$hosting_prices[$fields['hosting']]['trial_discount'].'" class="active" selected>
							</select>
							';

							$hide_select 	= TRUE;

						else:
						?>

							<select class="hosting-package" name="hosting">
		                    <?php

		                    ## HACK FOR LANTY
		                    if ($partner_data['website']['partner_id'] == '251' OR $partner_data['website']['partner_id'] == '225' OR $partner_data['website']['partner_id'] == '169')
		                    	$fields['hosting']	= 'annual';


		                    foreach ($hosting_prices as $key => $price_array): 
								
								$now_price =  $price_array['price'];
								$now_price += $price_array['setup_fee'];
								$now_price -= $price_array['trial_discount'];
								
								$mo_price  =  number_format($price_array['price'] / $price_array['num_months'], 2);
								
								$readable  =  $hosting_options[$key];
								
								$text      =  $readable . ' ($'.number_format($mo_price,2).'/mo.)';
		                        
								$class     =  '';
								$selected  =  '';

								if ($key == $fields['hosting']):
									
									$class    = 'active';
									$selected = 'selected="selected"';

								endif;

		                        ?>

		                        <option value="<?php echo $key; ?>" data-hosting-package="<?php echo $key;?>" data-hosting-price="<?php echo $price_array['price'];?>" data-setup-fee="<?php echo $price_array['setup_fee'];?>" data-trial-discount="<?php echo $price_array['trial_discount'];?>" class="<?php echo $class; ?>" <?php echo $selected; ?>>
		                        		<?php echo $text;?>
		                        </option>

		                        <?php

		                    endforeach;

		                    // echo form_dropdown('hosting', $select_options, $fields['hosting']); ?>

		                	</select>

		                <?php
		                endif;	// end custom lanty hack
		                ?>

						<span class="breakfree"></span>
					</li>

				<?php endif; ?>

				<li>
					<label>Instant Account Activation</label>
					<strong>YES</strong>
					<span class="breakfree"></span>
				</li>

				<li>				
					<label>Account Setup</label>
					<strong>$<span id="hosting_setup">0.00</span></strong>
					<span class="breakfree"></span>			
				</li>

				<li>				
					<label>Host Unlimited Websites</label>
					<strong>YES</strong>
					<span class="breakfree"></span>
				</li>

				<li>				
					<label>Unlimited Email Accounts</label>
					<strong>YES</strong>
					<span class="breakfree"></span>
				</li>

				<li id="domain_row">				
					<label>

					<?php
					$word = 'Registration';


					switch ($core_type):
						case('transfer'):
							$word = 'Transfer';
							break;
						case('dns'):
							$word = 'DNS Update';
							break;
						default:
							$word = 'Registration';
					endswitch;
					?>
					
					Domain <?php echo $word; ?>: <br>
					<span><?php echo isset($fields['core_domain']) ? $fields['core_domain'] : '';?></span></label>

					<strong>
						<?php if ($core_type != 'dns'): ?>
							$<span id="annual_price"><?php echo @number_format(($core_prices['annual']['price'] - $core_prices['annual']['trial_discount']),2);?></span>
						<?php else: ?>
							YES
						<?php endif; ?>
					</strong>
					<span class="breakfree"></span>
				</li>

				<li>				
					<label>Website Builder</label>
					<strong>YES</strong>
					<span class="breakfree"></span>
				</li>
				
				<li>
					<label>Unlimited Bandwidth</label>
					<strong>YES</strong>
					<span class="breakfree"></span>
				</li>
				<li>				
					<label>24/7 Customer Support</label>
					<strong>YES</strong>
					<span class="breakfree"></span>
				</li>
				
				<li>				
					<label>Free Advertising Credits</label>
					<strong>YES</strong>
					<span class="breakfree"></span>
				</li>
				<li>				
					<label>99.9% Uptime Guaranteed</label>
					<strong>YES</strong>
					<span class="breakfree"></span>
				</li>
				<li>				
					<label>30-Day Money Back Guarantee</label>
					<strong>YES</strong>
					<span class="breakfree"></span>
				</li>

				<?php 
                if (isset($upsell_prices) && count($upsell_prices)):

                    foreach ($upsell_prices as $key => $term_array): 

                        $checked = ( ! $fields['bill_upsells'][$key] && $this->input->post('billing_form')) ? '' : 'checked'; ?>

                    	<?php
                    	## HACK FOR LANTY
                    	if ($partner_data['website']['partner_id'] == '251' OR $partner_data['website']['partner_id'] == '225' OR $partner_data['website']['partner_id'] == '169')
                    		$checked 	= FALSE;
                    	?>

                        <li class="nocheck billing_upsell">

                        	<?php

                        	echo form_checkbox(
	                            array(
	                                'name'             => 'bill_upsells['.$key.']',
	                                'value'            => '1',
	                                'id'               => 'billing_upsell_'.$key,
	                                'data-addon-price' => $term_array['annual']['price'],
	                                'checked'          => $checked
	                            )
	                        ); ?>

							<label><?php echo $billing_upsells[$key];?></label>
							<strong>$<?php echo $term_array['annual']['price'];?></strong>
							<span class="breakfree"></span>

                        </li>

                    <?php endforeach; 

                endif; ?>
			</ul>

			<ul id="total_container">

				<li id="trial_disc">
                	Special One-Time Discount! $<span>000.00</span> off
                </li>
                <li id="js_total">
                	Total: $<span>000.00</span>
                </li>

			</ul>
		</div>

		<div class="column c-guarantee">
			<h2><?php echo $this->lang->line('billing_guarantee_title'); ?></h2>
			<h3><?php echo $this->lang->line('billing_guarantee_subtitle'); ?></h3>
			<div class="award">
				<div>
					<img src="/resources/brainhost/img/img-guarantee.jpg" alt="<?php echo $this->lang->line('billing_guarantee_subtitle'); ?>" />
				</div>
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
							'class'   => 'required collect',
						)
					);

				?>

				<label for="tos_agreement">
					<?php echo $this->lang->line('billing_guarantee_agree'); ?> 
					<a href="/pages/terms" class="lightview" data-lightview-type="iframe" data-lightview-options="width: 1000">
						<?php echo $this->lang->line('billing_guarantee_terms'); ?>
					</a> 
					and 
					<a href="/pages/privacy" class="lightview" data-lightview-type="iframe" data-lightview-options="width: 1000">
						<?php echo $this->lang->line('billing_guarantee_privacy'); ?>.
					</a>
				</label>

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
						'class'   => 'required collect',							
						'size'    => 10
					)); 

					?>
					
				</div>
				<div class="row">
					<label for="last_name"><?php echo $this->lang->line('billing_info_lblLName'); ?></label>

					<?php 

					echo form_input(array(
						'id'      => 'last_name',
						'value'   => $fields['last_name'],
						'name'    => 'last_name',
						'class'   => 'required collect',							
						'size'    => 10
					)); 

					?>

				</div>
				<div class="row">
					<label for="email"><?php echo $this->lang->line('billing_info_lblEmail'); ?></label>

					<?php 

					echo form_input(array(
						'id'      => 'email',
						'value'   => $fields['email'],
						'name'    => 'email',
						'class'   => 'required collect',							
						'size'    => 10
					)); 

					?>

				</div>
				<div class="row">
					<label for="phone"><?php echo $this->lang->line('billing_info_lblPhone'); ?></label>

					<?php 

					echo form_input(array(
						'id'      => 'phone',
						'value'   => $fields['phone'],
						'name'    => 'phone',
						'class'   => 'required collect',							
						'size'    => 10
					)); 

					?>

				</div>

				<div class="row">
					<label for="country"><?php echo $this->lang->line('billing_info_lblCountry'); ?></label>

					<select name="country" id="country" class="required collect">
                        <?php

                        $selection = FALSE; 

                        $req_zip   = $this->config->item('addr_req_zip_countries');
                        $req_state = $this->config->item('addr_req_state_countries');

                        $countries = array(
                            'Choose Your Country' => $this->config->item('addr_common_countries'),
                            'All Countries'       => $this->config->item('addr_countries')
                        );

                        foreach ($countries as $optgroup => $ctry_array): ?>

                            <optgroup label="<?php echo $optgroup;?>">

                                <?php foreach ($ctry_array as $code => $country): 

                                    $selected = '';

                                    if ( ! $selection && $code == $fields['country']):

                                        $selected  = 'selected="selected"';
                                        $selection = TRUE;

                                    endif;
                                    
                                    $rq_zip = 'no';
                                
                                    if (array_key_exists($code, $req_zip)):

                                        $rq_zip = 'yes';

                                    endif;

                                    $rq_state = 'no';

                                    if (array_key_exists($code, $req_state)):

                                        $rq_state = 'yes';

                                    endif;

                                    ?>

                                    <option value="<?php echo $code; ?>" <?php echo $selected; ?> data-req-zip="<?php echo $rq_zip; ?>" data-req-state="<?php echo $rq_state;?>">
                                        <?php echo $country; ?>
                                    </option>

                                <?php endforeach; ?>

                            </optgroup>

                        <?php endforeach; ?>

                    </select>


				</div>
				<div class="row">
					<label for="address"><?php echo $this->lang->line('billing_info_lblAddress'); ?></label>
					
					<?php 

					echo form_input(array(
						'id'      => 'address',
						'value'   => $fields['address'],
						'name'    => 'address',
						'class'   => 'required collect',							
						'size'    => 10
					)); 

					?>

				</div>
				<div class="row">
					<label for="city"><?php echo $this->lang->line('billing_info_lblCity'); ?></label>
					
					<?php 

					echo form_input(array(
						'id'      => 'city',
						'value'   => $fields['city'],
						'name'    => 'city',
						'class'   => 'required collect',							
						'size'    => 10
					)); 

					?>

				</div>
				<div class="row">
					<label for="state"><?php echo $this->lang->line('billing_info_lblState'); ?></label>

					<?php
                                    
                    $state_options = array_merge(array('' => 'Choose Your State/Province'),$this->config->item('addr_states'));
                    
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
                        'id="state" class="required collect"'
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
						'class'   => 'required collect',							
						'size'    => 10
					)); 

					?>

				</div>
			</fieldset>
			<h2 id="card-info">
				<span><?php echo $this->lang->line('billing_card_title'); ?></span>
			</h2>
			<fieldset class="info-payment">
				<legend><?php echo $this->lang->line('billing_card_title'); ?></legend>
				<div class="row">
					<label for="cc_num"><?php echo $this->lang->line('billing_card_lblCardNum'); ?></label>
					
					<?php 

					echo form_input(array(
						'id'           => 'cc_num',
						'value'        => '',
						'name'         => 'cc_num',
						'class'        => 'required collect',
						'size'         => 10,
						'autocomplete' => 'off'
					)); 

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
				<div class="row" id="security_row">
					<label for="cc_security"><?php echo $this->lang->line('billing_card_lblCVV'); ?></label>
					<?php 

					echo form_input(array(
						'id'           => 'cc_security',
						'value'        => '',
						'name'         => 'cc_security',
						'class'        => 'cvv required',
						'autocomplete' => 'off'
					)); 

					?>
					<span id="cvv2_popup"></span>
					<a id="cvv2_popup_click" href="#"><?php echo $this->lang->line('billing_card_what'); ?></a>
	
					<div style="clear:both"></div>

					<input type="submit" value="<?php echo $this->lang->line('billing_btnContinue'); ?>">
				</div>
			</fieldset>
		</div>
	</section>



	<div id="hosting_options" style="display:none;">
        <?php foreach ($hosting_prices as $key => $price_array): ?>

            <input type="hidden" class="hosting_options" data-hosting-package="<?php echo $key; ?>" data-price="<?php echo $price_array['price'];?>" data-setup="<?php echo $price_array['setup_fee'];?>" data-trial-discount="<?php echo $price_array['trial_discount'];?>" data-months="<?php echo $price_array['num_months'];?>"/>

        <?php endforeach; ?>
    </div>



	<?php
	$seals = $this->config->item('brands_seals');
    $brand = $partner_data['brand'];

    if ($brand && array_key_exists($brand,$seals)): 
        
        $seals_view = $seals[$brand];?>

        <aside class="security">
            
            <?php $this->load->view('seals/'.$seals_view); ?>

        </aside>

        <?php 

    endif;

echo form_close();