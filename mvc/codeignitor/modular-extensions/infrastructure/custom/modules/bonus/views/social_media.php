<?php

// get social media packages prices
$facebook_page          = $this->prices->get($funnel_id,$partner_id,$affiliate_id,$offer_id,$this->orders->get_plan_id('facebook_page'),$variant,$term);
$facebook_marketing     = $this->prices->get($funnel_id,$partner_id,$affiliate_id,$offer_id,$this->orders->get_plan_id('facebook_marketing'),$variant,$term);
$twitter_page           = $this->prices->get($funnel_id,$partner_id,$affiliate_id,$offer_id,$this->orders->get_plan_id('twitter_page'),$variant,$term);
$twitter_marketing      = $this->prices->get($funnel_id,$partner_id,$affiliate_id,$offer_id,$this->orders->get_plan_id('twitter_marketing'),$variant,$term);

// this is the total of all individual services combined
$total                 = $facebook_page + $facebook_marketing + $twitter_page + $twitter_marketing;



?>
<script type="text/javascript">
    
    $(document).ready(function(){

        $('form').submit(function(e) {
            if($('form').validate().checkForm()){
                show_loading_dialog();
            } else {
                e.preventDefault();
            }
        });
    });
</script>
		<div class="mainbox">
        	<div class="left">
            	<h2>Social Media Package</h2>
            	<div class="info">
                    <p><?php echo $this->lang->line('bonus_social_intro'); ?></p>
                    <p><?php echo $this->lang->line('bonus_social_intro'); ?></p>
                </div>
                <img src="/resources/modules/bonus/assets/img/social_media/box.png">
            
            </div>

			<?php
			// open the form
			echo form_open(
				'',
				array('method'	=> 'POST' , 'id' => 'add_package_form', 'style' => 'width:365px;'),
				array('plans[]' => 'social_media')
			);
			?>
            
            <div class="right">
            	<div class="right-title"><?php echo $this->lang->line('bonus_social_includes'); ?></div>
                <div class="socialpages">
                	<div class="socialpage">
                    	<div class="social-title"><h3><?php echo $this->lang->line('bonus_social_facebook'); ?></h3></div>
                        	<div class="pageinfo">
                            	<div class="iconfb"><img src="/resources/modules/bonus/assets/img/social_media/facebook.png"></div>
                                	<div class="socialinfo">
                                    	<?php echo $this->lang->line('bonus_social_searches'); ?>
                                    </div>
                                    <div class="checkprice">

                                    	<?php 
										// Twitter Page Checkbox
										echo form_input(array(
											'name'		=> 'plans[]',
											'id'		=> 'facebook_page',
											'type'		=> 'checkbox',
											'value'		=> 'facebook_page'
										));
										?> <span>$<?php echo $facebook_page; ?></span>

                                    <div class="clear"></div>
                                    </div>
                            
                            <div class="clear"></div>
                            </div>
                    </div>
                    <div class="fbpage">
                    	<div class="social-title"><h3><?php echo $this->lang->line('bonus_social_twitter'); ?></h3></div>
                        	<div class="pageinfo">
                            	<div class="iconfb"><img src="/resources/modules/bonus/assets/img/social_media/twitter.png"></div>
                                	<div class="socialinfo">
                                    	<?php echo $this->lang->line('bonus_social_searches'); ?>
                                    </div>
                                    <div class="checkprice">
                                    	
                                    	<?php 
										// Twitter Page Checkbox
										echo form_input(array(
											'name'		=> 'plans[]',
											'id'		=> 'twitter_page',
											'type'		=> 'checkbox',
											'value'		=> 'twitter_page'
										));
										?> <span>$<?php echo $twitter_page; ?></span>

                                    <div class="clear"></div>
                                    </div>
                            
                            <div class="clear"></div>
                            </div>
                    </div>
                </div>
                
                <div class="socialpages">
                	<div class="socialpage">
                    	<div class="social-title"><h3><?php echo $this->lang->line('bonus_social_facebook_marketing'); ?></h3></div>
                        	<div class="pageinfo">
                            	<div class="iconfb"><img src="/resources/modules/bonus/assets/img/social_media/facebook-marketing.png"></div>
                                	<div class="socialinfo">
                                    	<?php echo $this->lang->line('bonus_social_searches'); ?>
                                    </div>
                                    <div class="checkprice">
                                    	
                                    	<?php 
										// Facebook Marketing Checkbox
										echo form_input(array(
											'name'		=> 'plans[]',
											'id'		=> 'facebook_marketing',
											'type'		=> 'checkbox',
											'value'		=> 'facebook_marketing'
										));
										?> <span>$<?php echo $facebook_marketing; ?></span>

                                    <div class="clear"></div>
                                    </div>
                            
                            <div class="clear"></div>
                            </div>
                    </div>
                    <div class="fbpage">
                    	<div class="social-title"><h3><?php echo $this->lang->line('bonus_social_twitter_marketing'); ?></h3></div>
                        	<div class="pageinfo">
                            	<div class="iconfb"><img src="/resources/modules/bonus/assets/img/social_media/twitter-marketing.png"></div>
                                	<div class="socialinfo">
                                    	<?php echo $this->lang->line('bonus_social_searches'); ?>
                                    </div>
                                    <div class="checkprice">
                                    	
                                    	<?php 
										// Twitter Marketing Checkbox
										echo form_input(array(
											'name'		=> 'plans[]',
											'id'		=> 'twitter_marketing',
											'type'		=> 'checkbox',
											'value'		=> 'twitter_marketing'
										));
										?> <span>$<?php echo $twitter_marketing; ?></span>

                                    <div class="clear"></div>
                                    </div>
                            
                            <div class="clear"></div>
                            </div>
                    </div>
                </div>
                <div class="buttom-block">
                	<h3><?php echo $this->lang->line('bonus_social_all'); ?> <span>$<?php echo number_format($total,2); ?></span> $<?php echo number_format($price,2); ?></h3>	
                	<div class="yellobtn"><a id="add_social_media" href="#"><?php echo $this->lang->line('bonus_social_add_social'); ?></a></div>
                    <p>_____________ or _____________</p>
                    <div class="graybtn"><a id="add_items_individually" href="#"><?php echo $this->lang->line('bonus_social_add_individually'); ?></a></div>
                
                </div>

                <input type="hidden" name="action_id" id="action_id" value="43" />

            	<?php
            	// close the form
            	echo form_close();
				?>


				<?php
				// open the form
				echo form_open(
				'',
				array('method' 		=> 'POST', 'id' => 'no_thanks_form'),
				array('action_id'	=> 42)
				);
				?>


                <div class="thanks">
                	<h4 id="no_thanks_link" class="pointer"><?php echo $this->lang->line('bonus_social_no_thanks'); ?></h4>
                    <p><?php echo $this->lang->line('bonus_social_purchase'); ?></p>
                </div>
              
				<?php
				echo form_close();
				?>

                
            </div>
        
        <div class="clear"></div>
        </div>

		<!-- Javascript to update action_id hidden field depending on button clicked -->
		<script type="text/javascript">
		$(document).ready(function() {

			// on click of + Add Social Media Package button, update action_id (43)
			$("#add_social_media").click(function() {
				$("#action_id").val("43");			// update action_id
				$("#add_package_form").submit();	// submit form
			});
			
			// on click of Add Items Individually button, update action_id (44)
			$("#add_items_individually").click(function() {
				$("#action_id").val("44");			// update action_id
				$("#add_package_form").submit();	// submit form
			});

			// on click of no thanks link, submit form
			$("#no_thanks_link").click(function() {
				$("#no_thanks_form").submit();		// submit form
			});
		});
		</script>
		<!-- End script to update action_id -->

