<h1><?php echo $this->lang->line('billing_v1_congrats'); ?> <em><?php echo isset($fields['core_domain']) ? $fields['core_domain'] : '';?>!</em></h1>
<div class="box">
    <p><?php echo $this->lang->line('billing_v1_your_domain'); ?> <em><?php echo isset($fields['core_domain']) ? $fields['core_domain'] : '';?></em> <?php echo $this->lang->line('billing_v1_is_available'); ?>.</p>
    <p><?php echo $this->lang->line('billing_v1_enter_your_billing'); ?></p>
</div>
<div class="billing-wrap">
    <img src="/resources/modules/billing/assets/v1/img/<?php echo $this->session->userdata('_language'); ?>/img-satisfaction-guaranteed.jpg" alt="100% Satisfaction Guaranteed" />
    <img src="/resources/modules/billing/assets/v1/img/<?php echo $this->session->userdata('_language'); ?>/img-trust.jpg" alt="250,000+ Companies Trust Us With Their Website" />
    <?php 

    // get the action id
    $action_id  = ($_SERVER['HTTP_HOST'] == 'infrastructure.brainhost.com') ? 56 : 86;

    // open the form
    echo form_open('',array('id' => 'form-billing'),array('billing_form' => 1,'action_id' => $action_id)); 
    ?>
        <ol id="accordion">
            <li class="one accordion-level">
                
                <h2><?php echo $this->lang->line('billing_v1_select_hosting_package'); ?></h2>
                <div>

                    <?php

                    if ($fields['funnel_type'] == 'hosting'):

                        $ul_width = count($hosting_prices) * 145; ?>

                        <ul class="hosting-package" style="width:<?php echo $ul_width;?>px;margin:0 auto;display:block;">

                            <?php 

                            $count = 0;
							
							$orig_mo	= $hosting_prices['monthly']['price'];
							$orig_fee	= $hosting_prices['monthly']['setup_fee'];
							$orig_disc	= $hosting_prices['monthly']['trial_discount'];

                            if (is_array($hosting_prices) && count($hosting_prices)):
                                
                                foreach ($hosting_prices as $key => $price_array): 

                                    $count++; 
                                        
									$now_price		=  $price_array['price'];
									$now_price		+= $price_array['setup_fee'];
									$now_price		-= $price_array['trial_discount'];
									
									$orig_price		=  $orig_mo * $price_array['num_months'];
									$orig_price		+= $orig_fee;
									$orig_price		-= $orig_disc;
									
									$percents[$key]	=  @(int)((1 - ($now_price / $orig_price)) * 100);
                                    $percent         = ($percents[$key] && $percents[$key] > 0) ? '(Save '.$percents[$key].'%)' : ''; 
                                     
                                    $main_price      = ($price_array['price'] / $price_array['num_months']);
                                    $main_price_text = 'a month';

                                    if ($price_array['trial_discount'] > 0.00):
                                        
                                        $main_price      = $price_array['price'] - $price_array['trial_discount'];
                                        
                                        $main_price      = ($main_price > 0) ? $main_price : 0.00;
                                        
                                        $main_price_text = 'intro price';

                                    endif;

                                    ?>

                                    <li data-hosting-package="<?php echo $key;?>" data-hosting-price="<?php echo $price_array['price'];?>" data-setup-fee="<?php echo $price_array['setup_fee'];?>" data-trial-discount="<?php echo $price_array['trial_discount'];?>" class="<?php echo ($key == $fields['hosting'] ? 'active' : ''); ?>">
                                        <div class="check"></div>
                                        <h3>$<?php echo number_format($main_price,2); ?> </h3>
                                        <span><?php echo $main_price_text; ?></span>
                                        <p>
											$<?php echo $price_array['price'];?> <?php echo $this->lang->line('billing_v1_billed_every'); ?>
											<?php if ((int)$price_array['num_months'] > 1 ): ?>
												<?php echo $price_array['num_months']; ?> <?php echo $this->lang->line('billing_v1_months'); ?>
											<?php else: ?>
												<?php echo $this->lang->line('billing_v1_month'); ?>
											<?php endif; ?>
											<?php echo $percent; ?>
										</p>
                                        
                                        <?php echo ($count==1) ? '<strong>'. $this->lang->line('billing_v1_best_value').'</strong>' : ''; ?>
                                    </li>

                                <?php endforeach; 

                            endif; ?>

                            <li style="margin:0;padding:0;width:0;height:0;display:block;float:none;clear:both;"></li>

                        </ul>

                        <div style="clear:both;float:none;"></div>

                    <?php endif; 


                    $setup_fee = isset($hosting_prices[$fields['hosting']]['setup_fee']) ? $hosting_prices[$fields['hosting']]['setup_fee'] : 0.00;

                    ?>
                    
                    <ul class="col-l">
                        <li><?php echo $this->lang->line('billing_v1_feature1'); ?></li>
                        <li><?php echo $this->lang->line('billing_v1_feature2'); ?> ($<span id="hosting_setup"><?php echo $setup_fee;?></span>)</li>
                        <li><?php echo $this->lang->line('billing_v1_feature3'); ?></li>
                        <li><?php echo $this->lang->line('billing_v1_feature4'); ?></li>
                        <li>
                            <?php echo $this->lang->line('billing_v1_feature5'); ?> 
                            (<?php echo isset($fields['core_domain']) ? $fields['core_domain'] : '';?>) ($<span id="annual_price"><?php echo @number_format(($core_prices['annual']['price'] - $core_prices['annual']['trial_discount']),2);?></span>)
                        </li>
                        <li><?php echo $this->lang->line('billing_v1_feature6'); ?></li>
                        <li><?php echo $this->lang->line('billing_v1_feature7'); ?></li>
                        <li><?php echo $this->lang->line('billing_v1_feature8'); ?></li>
                    </ul>
                    <ul class="col-r">
                        <li><?php echo $this->lang->line('billing_v1_feature9'); ?></li>
                        <li><?php echo $this->lang->line('billing_v1_feature10'); ?></li>
                        <li><?php echo $this->lang->line('billing_v1_feature11'); ?></li>
                        <li><?php echo $this->lang->line('billing_v1_feature12'); ?></li>

                        <li class="upsell_check">Congratulations on choosing a domain.</li>
                        <li class="upsell_check">We're looking forward to getting your site up and running.</li>
                        <!--
                        <?php 
                        if (isset($upsell_prices) && count($upsell_prices)):

                            foreach ($upsell_prices as $key => $term_array): ?>

                                <li class="upsell_check">

                                    <?php
                                    $checked = ( ! $fields['bill_upsells'][$key] && $this->input->post('billing_form')) ? '' : 'checked';

                                    echo form_checkbox(
                                        array(
                                            'name'             => 'bill_upsells['.$key.']',
                                            'value'            => '1',
                                            'id'               => 'billing_upsell_'.$key,
                                            'data-addon-price' => $term_array['annual']['price'],
                                            'checked'          => $checked
                                        )
                                    );

                                    echo $billing_upsells[$key];?> ($<?php echo $term_array['annual']['price'];?>)

                                </li>

                            <?php endforeach; 

                        endif; ?>
                        -->

                    </ul>
                    <h3 class="total" id="js_total"><?php echo $this->lang->line('billing_v1_total'); ?> $<span>0.00</span></h3>
                    
                    <p class="center row"><a href="#" id="trigger-step2"><img src="/resources/modules/billing/assets/v1/img/<?php echo $this->session->userdata('_language'); ?>/btn-continue-next.png" alt="<?php echo $this->lang->line('billing_v1_contine_next'); ?>" /></a></p>

                </div>

            </li>
            <li class="two ui-state-disabled accordion-level">
                

                <h2><?php echo $this->lang->line('billing_v1_billing_information'); ?></h2>
                <div>
                    <p class="blue">* While there is NO COST to you for registering it, by law we have to have a credit card on file in order to register it to you personally.<?php //echo $this->lang->line('billing_v1_address_match'); ?></p>
                    <fieldset>
                        <ul>
                            <li>
                                <?php
                                    echo form_input(array(
                                        'id'          => 'first_name',
                                        'value'       => $fields['first_name'],
                                        'placeholder' => 'First Name',
                                        'name'        => 'first_name',
                                        'class'       => 'required collect',                            
                                        'size'        => 10
                                    ));
                                ?>
                            </li>
                            <li>
                                <?php 
                                    echo form_input(array(
                                        'id'          => 'last_name',
                                        'value'       => $fields['last_name'],
                                        'placeholder' => 'Last Name',
                                        'name'        => 'last_name',
                                        'class'       => 'required collect',
                                        'size'        => 10
                                    )); 
                                ?>
                            </li>
                            <li>
                                <?php 
                                    echo form_input(array(
                                        'id'          => 'email',
                                        'value'       => $fields['email'],
                                        'placeholder' => 'Email',
                                        'name'        => 'email',
                                        'class'       => 'required collect',                            
                                        'size'        => 10
                                    )); 
                                ?>
                            </li>
                            <li>
                                <?php 
                                    echo form_input(array(
                                        'id'          => 'phone',
                                        'value'       => $fields['phone'],
                                        'placeholder' => 'Phone',
                                        'name'        => 'phone',
                                        'class'       => 'required collect',                            
                                        'size'        => 10
                                    )); 
                                ?>
                            </li>
                            <li>
                                <?php 
                                    echo form_input(array(
                                        'id'          => 'address',
                                        'value'       => $fields['address'],
                                        'placeholder' => 'Street Address',
                                        'name'        => 'address',
                                        'class'       => 'required collect',                            
                                        'size'        => 10
                                    )); 
                                ?>
                            </li>
                            <li class="half">
                                <?php 
                                    echo form_input(array(
                                        'id'          => 'city',
                                        'value'       => $fields['city'],
                                        'placeholder' => 'City',
                                        'name'        => 'city',
                                        'class'       => 'required collect',                            
                                        'size'        => 10
                                    )); 
                                ?>
                            </li>
                            <li class="half">
                                <?php 
                                    echo form_input(array(
                                        'id'          => 'zipcode',
                                        'value'       => $fields['zipcode'],
                                        'placeholder' => 'Zip',
                                        'name'        => 'zipcode',
                                        'class'       => 'required collect',                            
                                        'size'        => 10
                                    )); 
                                ?>
                            </li>
                            <li></li>
                            <li class="half">

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

                            </li>

                            <li class="half">
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
                            </li>
                        </ul>
                    </fieldset>

                    <p class="center row"><a href="#" id="trigger-step3"><img src="/resources/modules/billing/assets/v1/img/<?php echo $this->session->userdata('_language'); ?>/btn-continue-next.png" alt="<?php echo $this->lang->line('billing_v1_contine_next'); ?>" /></a></p>
                    
                </div>


            </li>

            <?php

            $billing_steps = array(
                '3' => 'three',
                '4' => 'four'
            );

            $current_step = '3'; 

            if ($charity_view): ?>

                <li class="<?php echo $billing_steps[$current_step]; ?> ui-state-disabled accordion-level">
                    <?php $this->load->view('charity/'.$charity_view);?>
                </li>

                <?php

                $current_step++;

            endif; ?>

            <li class="<?php echo $billing_steps[$current_step]; ?> ui-state-disabled accordion-level">
                <h2><?php echo $this->lang->line('billing_v1_cc_info'); ?></h2>
                <div>
                    <fieldset>
                        <ul class="billing">
                            <li>
                                <label for="cc_num"><?php echo $this->lang->line('billing_v1_cc_card'); ?></label>
                                <?php 
                                    echo form_input(array(
                                        'id'           => 'cc_num',
                                        'value'        => '',
                                        'name'         => 'cc_num',
                                        'class'        => 'required',
                                        'size'         => 10,
                                        'autocomplete' => 'off'
                                    )); 
                                ?>
                            </li>
                            <li>
                                <label for="cc_exp_yr"><?php echo $this->lang->line('billing_v1_cc_exp'); ?></label>

                                <?php

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

                                    $years = range(date('Y'),date('Y')+10);

                                    echo form_dropdown(
                                        'cc_exp_yr', 
                                        array_combine($years,$years),
                                        date('Y'),
                                        'id="cc_exp_yr" class="required"'
                                    );

                                    
                                ?>

                                
                            </li>
                            <li>
                                <label for="cc_security"><?php echo $this->lang->line('billing_v1_cc_cvv'); ?></label>
                                <?php 
                                    echo form_input(array(
                                        'id'           => 'cc_security',
                                        'value'        => '',
                                        'name'         => 'cc_security',
                                        'class'        => 'cvv required',
                                        'autocomplete' => 'off'
                                    )); 
                                ?>
                            </li>
                        </ul>
                    </fieldset>
                    <div class="tos">
                        <?php
                        
                            $tos_checked = ( ! $fields['tos_agreement'] && $this->input->post('billing_form')) ? '' : 'checked';
                            echo form_checkbox(
                                array(
                                    'name'    => 'tos_agreement',
                                    'value'   => '1',
                                    //'checked' => $tos_checked,
                                    'id'      => 'tos_agreement',
                                    'class'   => 'required',
                                )
                            );
                        
                        ?>

                        <input type="submit" />
                        
                        <label for="tos_agreement"><?php echo $this->lang->line('billing_v1_i_agree'); ?> <a href="https://orders.brainhost.com/popup/terms/off" class="lightview" title="" rel="iframe"><?php echo $this->lang->line('billing_v1_terms'); ?></a> <?php echo $this->lang->line('billing_v1_and'); ?> <a href="https://orders.brainhost.com/popup/privacy/off" class="lightview" title="" rel="iframe"><?php echo $this->lang->line('billing_v1_privacy'); ?></a>.</label>
                       
                        <p id="must_agree"><?php echo $this->lang->line('billing_v1_must_agree'); ?></p>
                    </div>
                </div>
            </li>
        </ol>

        <input type="hidden" name="hosting" id="selHosting" value="<?php echo $fields['hosting'];?>"/>


        <div id="hosting_options" style="display:none;">
            <?php foreach ($hosting_prices as $key => $price_array): ?>

                <input type="hidden" class="hosting_options" data-hosting-package="<?php echo $key; ?>" data-price="<?php echo $price_array['price'];?>" data-setup="<?php echo $price_array['setup_fee'];?>" data-trial-discount="<?php echo $price_array['trial_discount'];?>" data-months="<?php echo $price_array['num_months'];?>"/>

            <?php endforeach; ?>
        </div>


        <?php
        #echo form_dropdown('hosting', $hosting_options, $fields['hosting'], 'id="selHosting"');

        echo form_close(); 


        $seals = $this->config->item('brands_seals');
        $brand = $partner_data['brand'];

        if ($brand && array_key_exists($brand,$seals)): 
            
            $seals_view = $seals[$brand];?>

            <aside class="security">
                
                <?php $this->load->view('seals/'.$seals_view); ?>

            </aside>

            <?php 

        endif; 

        ?>

</div>