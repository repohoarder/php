<?php

$assets_loc = 'assets';
$noun       = 'Website';
$heading    = 'Start working from home and <br/> making money with your <br/> website as soon as tomorrow!';

if (isset($_GET['test_assets']) || $this->session->userdata('affiliate_id') == '103384'):

  $assets_loc = 'blog_assets';
  $noun       = 'Blog';
  $heading    = 'Start working from home as <br/> a paid blogger as soon as <br/> tomorrow!';

  ?>

  <style type="text/css">

    .midcontent_lt .blogintro_content .blogintro { background-image:url('/resources/brainhost_aress_wfhm/blog_assets/images/webintro_img.png'); }

  </style>

  <?php

endif;

?>

<style type="text/css">
#cvv2_popup {
    background: url("/resources/modules/billing/assets/threecol/img/cvv2.png") no-repeat scroll 0 0 transparent;
    cursor: pointer;
    display: none;
    height: 164px;
    position: absolute;
    right: -115px;
    top: -164px;
    width: 494px;
    z-index: 999999;
}

.congrats_outer .getblog_img .blogarrow {z-index:90;}

.blogform_bxouter {position:relative;}

.whythis {position:relative;}

.congrats_outer .getblog_img .blogarrow {
    position: absolute;
    right: -60px;
    top: 98px;
}

.errors_wrapper {padding:10px 27px 0 27px;}
.errors {position:relative;float:left;width:100%;margin:20px -2px 0 -2px;background:#fec2c3 url('/resources/brainhost/img/icon-error.png') 10px center no-repeat;border:2px solid #a32e2e;-webkit-border-radius:7px;border-radius:7px;margin:0;padding:20px 0;margin-bottom:15px;font-weight:bold; font-size:1.3em; line-height:1.4em;color:#A32E2E;}
.errors li {list-style-type:none;padding:0 0 0 70px;}

</style>

<?php echo form_open('',array('id' => 'form-billing'),array('billing_form' => 1,'action_id' => 91, 'tos_agreement' => 1)); ?>

<section class="midcontent_lt">
  <!--blog intro -->
  <section class="blogintro_content">
    <h1>Get Your <?php echo $noun; ?> Right Now!</h1>
    <section class="blogintro">
      <h2><?php echo $heading;?></h2>
    </section>
  </section>
  <!--blog intro -->
  <!--money back-->
  <section class="moneyback_outer">
    <section class="moneyback_lt">
      <ul>
        <li><span>One Year Money Back Guarantee</span></li>
        <li class="last"><span>100% Satisfaction Guaranteed!</span></li>
      </ul>
    </section>
    <section class="moneyback_rt">
      <ul>

        <?php

        if (is_array($hosting_prices) && count($hosting_prices)):

          $billed_ats = array(
            'monthly'  => 'Monthly',
            'annual'   => 'Annually',
            'biannual' => 'Semiannually',
            'biennial'    => 'every 2 years',
            'triennial'   => 'every 3 years',
            'quadrennial' => 'every 4 years',
          );

          foreach ($hosting_prices as $key => $price_array): 
             
            $main_price = ($price_array['price'] / $price_array['num_months']);

            if ($price_array['trial_discount'] > 0.00):
                
                $main_price = $price_array['price'] - $price_array['trial_discount'];                
                $main_price = ($main_price > 0) ? $main_price : 0.00;

            endif; ?>

            <li class="price">$<?php echo $main_price;?>/month</li>
            <li class="last">Billed <?php echo $billed_ats[$key];?> at $<?php echo $price_array['price'];?></li>

            <input type="hidden" value="<?php echo $key;?>" name="hosting"/>

            <?php 

            break;

          endforeach; 

        endif; ?>

      </ul>
    </section>
  </section>
  <!--money back-->
  <!--features-->
  <section class="blogfeatures_outer">
    <ul>
      <li>Professional <?php echo $noun;?></li>
      <li>Secure Transaction </li>
      <li>1 Year of Web Hosting</li>
      <li>One Year Money Back Guarantee</li>
      <li>1 Year Domain Name Registration</li>
      <li>Print and Email Receipts</li>
      <li>Dedicated Customer Service</li>
      <li>Verified Business Authority</li>
    </ul>
  </section>
  <!--features-->
  <!--congrats-->
  <section class="congrats_outer">
    <h1>Congrats!</h1>
    <h2>You are almost Done!</h2>
    <section class="getblog_img">
      <section class="blogarrow"><img src="/resources/brainhost_aress_wfhm/<?php echo $assets_loc; ?>/images/blog_arrow.png" alt="Get Your
Blog Now"></section>
      <img src="/resources/brainhost_aress_wfhm/<?php echo $assets_loc; ?>/images/getweb_img.jpg" alt="Get Your Website Now"></section>
  </section>
  <section class="securenote">
    <p>This is a secure website. Your personal data <br>
      is safely encrypted and is protected<br>
      from unauthorized access.</p>
  </section>
  <!--congrats-->
</section>
<section class="midcontent_rt">
  <section class="blogform_bxouter">
    <section class="yelarrow"><img src="/resources/brainhost_aress_wfhm/<?php echo $assets_loc; ?>/images/down_arrow.png" alt="GET YOUR WEBSITE NOW"></section>
    <section class="blogform_top"><img src="/resources/brainhost_aress_wfhm/<?php echo $assets_loc; ?>/images/getweb_heading.png" alt="Limited Availability - GET YOUR website NOW"></section>
    <section class="blogform_mid">
      <section class="debitcredit_outer">
        <p>We accept <strong>Credit Cards</strong> and <strong>Debit Cards</strong>:</p>
        <ul>
          <li><img src="/resources/brainhost_aress_wfhm/<?php echo $assets_loc; ?>/images/visa_card.jpg" alt="Visa"></li>
          <li><img src="/resources/brainhost_aress_wfhm/<?php echo $assets_loc; ?>/images/mastercard_card.jpg" alt="Master Card"></li>
          <li><img src="/resources/brainhost_aress_wfhm/<?php echo $assets_loc; ?>/images/ae_card.jpg" alt="American Express"></li>
          <li class="last"><img src="/resources/brainhost_aress_wfhm/<?php echo $assets_loc; ?>/images/discover_card.jpg" alt="Discover"></li>
        </ul>
      </section>

      <section class="blogform_content">
        <section class="blogform_row">
          <label class="formlabel">First Name:</label>
          <section class="fldouter">
            <section class="txtfld_outer">
              <?php
                  echo form_input(array(
                      'id'          => 'first_name',
                      'value'       => $fields['first_name'],
                      'name'        => 'first_name',                       
                  ));
              ?>
            </section>
            <!--    <section class="error_message">Error message will be here</section>-->
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">Last Name:</label>
          <section class="fldouter">
            <section class="txtfld_outer">
              <?php 
                  echo form_input(array(
                      'id'          => 'last_name',
                      'value'       => $fields['last_name'],
                      'name'        => 'last_name',
                  )); 
              ?>
            </section>
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">Email:</label>
          <section class="fldouter">
            <section class="txtfld_outer">
              <?php 
                  echo form_input(array(
                      'id'          => 'email',
                      'value'       => $fields['email'],
                      'name'        => 'email',
                  )); 
              ?>
            </section>
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">Phone:</label>
          <section class="fldouter">
            <section class="txtfld_outer">
              <?php 
                  echo form_input(array(
                      'id'          => 'phone',
                      'value'       => $fields['phone'],
                      'name'        => 'phone',
                  )); 
              ?>
            </section>
            <section class="fldnote"> (We respect your privacy) </section>
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">Billing Address:</label>
          <section class="fldouter">
            <section class="txtfld_outer">
              <?php 
                  echo form_input(array(
                      'id'          => 'address',
                      'value'       => $fields['address'],
                      'name'        => 'address',
                  )); 
              ?>
            </section>
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">Country:</label>
          <section class="fldouter">
            <section class="txtfld_outer">
              <select name="country" id="country">
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
            </section>
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">City:</label>
          <section class="fldouter">
            <section class="txtfld_outer">
              <?php 
                  echo form_input(array(
                      'id'          => 'city',
                      'value'       => $fields['city'],
                      'name'        => 'city',
                  )); 
              ?>
            </section>
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">State:</label>
          <section class="fldouter">
            <section class="txtfld_outer">
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
                    'id="state"'
                );

                ?>
            </section>
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">Postal/Zip Code:</label>
          <section class="fldouter">
            <section class="txtfld_outer">
              <?php 
                  echo form_input(array(
                      'id'          => 'zipcode',
                      'value'       => $fields['zipcode'],
                      'name'        => 'zipcode',
                  )); 
              ?>
            </section>
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">Card Number:</label>
          <section class="fldouter">
            <section class="txtfld_outer">
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
            </section>
            <section class="fldnote">(No dashes or spaces)</section>
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">Expiration:</label>
          <section class="fldouter">
            <section class="txtfld_outer1">
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

              ?>
            </section>
            <section class="txtfld_outer1">

              <?php

                $years = range(date('Y'),date('Y')+10);

                echo form_dropdown(
                    'cc_exp_yr', 
                    array_combine($years,$years),
                    date('Y'),
                    'id="cc_exp_yr" class="required"'
                );

              ?>

            </section>
          </section>
        </section>
        <section class="blogform_row">
          <label class="formlabel">Security Code:</label>
          <section class="fldouter">
            <section class="txtfld_outer2">
              <?php 
              echo form_input(array(
                  'id'           => 'cc_security',
                  'value'        => '',
                  'name'         => 'cc_security',
                  'class'        => 'cvv required',
                  'autocomplete' => 'off'
              )); 
              ?>
            </section>
            <section class="whythis">
              <span id="cvv2_popup"></span>
              <a href="#" id="cvv2_popup_click">What’s This?</a>
            </section>
          </section>
        </section>
        <section class="getblogbtn">
          <input name="" type="image" src="/resources/brainhost_aress_wfhm/<?php echo $assets_loc; ?>/images/getweb_btn.png" alt="Get My Website">
        </section>
        <section class="whtbox_outer1">
          <section class="whtbox_top1"></section>
          <section class="whtbox_mid1"> 

            <a style="display:block;width:143px;height:45px;float:left;margin:20px 20px 0px 30px" href="https://privacy-policy.truste.com/click-with-confidence/wps/en/www.brainhost.com/seal_m" title="TRUSTe online privacy certification" target="_blank" tabindex="-1" class="splash"><img style="border: none;width:143px;height:45px;" src="https://privacy-policy.truste.com/certified-seal/wps/en/www.brainhost.com/seal_m.png" alt="TRUSTe online privacy certification"></a>

            <a style="display:block;width: 130px;height: 88px;float:left;" href="https://trustsealinfo.verisign.com/splash?form_file=fdf/splash.fdf&amp;dn=brainhost.com&amp;lang=en" target="_blank" tabindex="-1" class="splash"><img style="width: 130px;height: 88px;" name="seal" src="https://seal.verisign.com/getseal?at=0&amp;sealid=0&amp;dn=brainhost.com&amp;lang=en" oncontextmenu="return false;" alt="Click to Verify" title="Click to Verify - This site has chosen a VeriSign SSL Certificate to improve Web site security"></a>

            <div style="clear:both"></div>

          </section>
        </section>
      </section>

    </section>
  </section>
</section>

</form>