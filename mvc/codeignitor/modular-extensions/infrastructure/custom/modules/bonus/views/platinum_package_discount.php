<script type="text/javascript">
	
	$(document).ready(function(){

		$('#platinum').find('input').attr('disabled', 'disabled');

		$('form').submit(function(e) {
			if($('form').validate().checkForm()){
				$('#facebox_overlay').hide();
				$('#promo-offer-info').hide();
				show_loading_dialog();
			} else {
				e.preventDefault();
			}
		});
	});
</script>
<style>
.add_upgrades_yellow {
    background: url("/assets/v4/images/promo_overlay/Upsell-Funnel-Button.png") no-repeat scroll 0 0 transparent;
    border: 0 none;
    color: #000000;
    cursor: pointer;
    display: block;
    font-size: 20px;
    font-weight: 800;
    height: 51px;
    letter-spacing: -2px;
    margin: 0 auto 8px;
    overflow: hidden;
    text-align: center;
    text-shadow: 0 1px 1px #E5E54A;
    width: 668px;
	text-decoration:none;
	clear:both;
}

.add_upgrades_yellow:hover {
    background: url("/assets/v4/images/promo_overlay/Upsell-Funnel-Button-Hover.png") no-repeat scroll 0 0 transparent;
   
}

#add_platinum_link, #no_thanks_link {
	float:none;
	display:block;
	margin:25px auto;
	background:url(/resources/brainhost/img/btn-create.png) 0 0 repeat-x;
	-webkit-border-radius:7px;border-radius:7px;
	width:525px;
}

.decline_link {

	padding-top : 12px;

}

.platinum_special {
	background:transparent;

}

#promo-offer-info {
	background:#D4F1F8;
	-webkit-border-radius:7px;border-radius:7px;
    left: 50%;
    margin-left: -360px;
    margin-top: 0;
    text-align: center;
    width: 722px;
    z-index: 900;

}

h3.promo-offer {
    background: url("/resources/brainhost/img/Platinum-Discount-Popup-h3.gif") no-repeat scroll center top transparent;
    color: #000000;
    font-size: 27px;
    font-weight: 800;
    height: 93px;
    letter-spacing: -2px;
    margin: 0 auto;
    padding: 27px 0 0;
    text-align: center;
    text-shadow: 0 1px 1px #E5E54A;
    width: 686px;
}

.tag-liner {
    color: #000000;
    font-size: 18px;
    letter-spacing: -2px;
    margin: 0 auto;
    padding: 13px 0 0;
    text-align: center;
    width: 686px;
}	

.promo-offer-info-mid .promo-padding {
    display: block;
    margin: 10px 0;
    padding: 0 0 0 90px;
}
.promo-offer-info ol, ul {
    float: left;
    width:260px;
    text-align:left;
    margin:0;
}

.info-list li {
    background: url("/resources/brainhost/img/bullet2.gif") no-repeat scroll 0 0 transparent;
    float: none;
    margin: 0;
    padding: 0 0 0 25px;
    list-style: none;
    white-space: nowrap;
    width: 178px;
    font-size:12px;
}

#facebox_overlay {
    height: 100%;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
}

.facebox_overlayBG {
    background-color: #000000;
    z-index: 2999;
}

</style>



















<?php 
// initialize varibales
$form_submission	= '';
$attributes			= array(
	'method'	=> 'POST'
);
$hidden_fields		= array('action_id' => 16);
?>

<div id="platinum">
	<aside class="one-time-offer">
		<h1><?php echo $this->lang->line('bonus_platinum_package_title'); ?></h1>
		<p><?php echo $this->lang->line('bonus_platinum_package_intro'); ?></p>
		<div class="date">
			<strong><?php echo date("M"); ?></strong>
			<span><?php echo date("d"); ?></span>
		</div>
	</aside>
	<div class="content">
		<p><strong><?php echo $this->lang->line('bonus_platinum_package_thanks',$partner_data['website']['company_name']); ?></strong></p>
		<p><?php echo $this->lang->line('bonus_platinum_package_welcome'); ?></p>
		
		<?php
		// open the form
		echo form_open(
			$form_submission,
			$attributes,
			$hidden_fields
		);
		?>
			<fieldset class="feature">
				<legend><?php echo $this->lang->line('bonus_platinum_package_feature_title').$price.'</span>'; ?></legend>
				<h2><?php echo $this->lang->line('bonus_platinum_package_feature_intro').$price.'</span>'; ?></h2>
				<p><?php echo $this->lang->line('bonus_platinum_package_feature_p1').$price.'!</strong>'; ?></p>
				<p><?php echo $this->lang->line('bonus_platinum_package_feature_p2'); ?></p>
				<em><?php echo $this->lang->line('bonus_platinum_package_feature_rec'); ?></em>
				<div class="checkbox">
					<?php 
					// Platinum Package Checkbox
					echo form_input(array(
						'name'		=> 'plans[]',
						'id'		=> 'platinum_package',
						'type'		=> 'checkbox',
						'value'		=> $page
					));
					?>
					<label for="platinum_package"><?php echo $this->lang->line('bonus_platinum_package_feature_add'); ?></label>
				</div>
			</fieldset>
			
			<?php 

			// load seo package
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'search_engine_submission', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);

			// load advanced_statistics
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'advanced_statistics', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);

			// load spam assassin
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'spam_assassin', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);

			// load brain host directory
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'brain_host_directory', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);

			// load priority support
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'priority_support', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);

			// load shell access
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'shell_access', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);

			// load private ssl
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'private_ssl', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);

			// load dedicated ip
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'dedicated_ip', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);

			// load green server
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'green_server', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);

			// load lightning fast servers
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'lightning_fast_servers', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);
				
			?>
			
			<fieldset class="feature">
				<legend><?php echo $this->lang->line('bonus_platinum_package_feature_title').$price.'</span>'; ?></legend>
				<h2><?php echo $this->lang->line('bonus_platinum_package_feature_intro').$price.'</span>'; ?></h2>
				<p><?php echo $this->lang->line('bonus_platinum_package_feature_p1').$price.'!</strong>'; ?></p>
				<p><?php echo $this->lang->line('bonus_platinum_package_feature_p2'); ?></p>
				<em><?php echo $this->lang->line('bonus_platinum_package_feature_rec'); ?></em>
				<div class="checkbox">
					<?php 
					// Platinum Package Checkbox
					echo form_input(array(
						'name'		=> 'plans[]',
						'id'		=> 'platinum_package2',
						'type'		=> 'checkbox',
						'value'		=> $page
					));
					?>
					<label for="platinum_package2"><?php echo $this->lang->line('bonus_platinum_package_feature_add'); ?></label>
				</div>
			</fieldset>
			<?php
			// Submit button
			echo form_input(array(
				'name'		=> 'submit1',
				'type'		=> 'submit',
				'class'		=> 'btn-add-upgrades hide-text',
				'value'		=> 'Give me this bonus!',
				'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
			));
			echo form_close();
			?>
			<span class="lbl-or hide-text">or</span>
			<?php
			// open the form
			echo form_open(
				$form_submission,
				$attributes,
				array('action_id' => 17)
			);
			?>

			<?php

			// No Thanks Button
			echo form_input(array(
				'name'		=> 'submit2',
				'type'		=> 'submit',
				'class'		=> 'lbl-nothanks',
				'id'		=> 'lbl-nothanks',
				'value'		=> $this->lang->line('bonus_platinum_package_no_thanks'),
				'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
			));

			?>
			<!-- <p class="lbl-nothanks"><a href="#"><?php echo $this->lang->line('bonus_platinum_package_no_thanks'); ?></a></p> -->
		<?php echo form_close(); ?>
	</div>
</div>























<div id="facebox_overlay" class="facebox_hide facebox_overlayBG" style="display: block; opacity: 0.7; z-index: 400;"></div>
	
<div id="promo-offer-info" style="display: block; top: 10px; position: absolute;">
	<div class="promo-offer-info-top">&nbsp;</div>
		<div class="promo-offer-info-mid">
		<h3 class="promo-offer"><?php echo $this->lang->line('platinum_last_chance'); ?>
			<br>
			<?php echo $this->lang->line('platinum_upgrade_now_fity'); ?>
		</h3>
		<div class="tag-liner">
		<strong><?php echo $this->lang->line('platinum_upgrade_for_all'); ?></strong>
		</div>

		<div class="promo-padding">
			<ul class="info-list">
				<li><?php echo $this->lang->line('spam_assassin_title'); ?></li>
				<li><?php echo $this->lang->line('advanced_statistics_title'); ?></li>
				<li><?php echo $this->lang->line('directory_listing_title'); ?> </li>
				<li><?php echo $this->lang->line('daily_backup_title'); ?></li>
				<li><?php echo $this->lang->line('private_ssl_title'); ?></li>
				<li><?php echo $this->lang->line('dedicated_ip_title'); ?></li>
			</ul>
			<ul class="info-list">
				<li><?php echo $this->lang->line('search_engine_submission_title'); ?></li>
				<li><?php echo $this->lang->line('priority_support_title'); ?></li>
				<li><?php echo $this->lang->line('shell_access_title'); ?></li>
				<li><?php echo $this->lang->line('lightning_fast_servers_title'); ?></li>
				<li><?php echo $this->lang->line('advanced_security_title'); ?></li>
				<li><?php echo $this->lang->line('green_server_title'); ?></li>
			</ul>
			<br>
		</div>
		
		

	<br />

	<?php
	echo form_open(
		'',
		array('method'	=> 'POST'),
		array('plans[]'	=> 'platinum_package_discount', 'action_id' => 45)
	);
	?>
	<input type="submit" name="submit_btn" value="<?php echo $this->lang->line('platinum_downsell_yes'); ?>" id="add_platinum_link" width="34" height="28" class="add_upgrades_yellow" alt="<?php echo $this->lang->line('platinum_addplat'); ?>"  />							
	<?php
	echo form_close();
	?>

	<center>
		<img src="/resources/brainhost/img/or.png" alt="or" />
	</center>


	<?php
	echo form_open(
		'',
		array('method' 		=> 'POST', 'id' => 'no_thanks_form'),
		array('action_id'	=> 46)
	);
	
	// No Thanks Button
	echo form_input(array(
		'name'		=> 'submit3',
		'type'		=> 'submit',
		'class'		=> 'add_upgrades_yellow decline_link',
		'id'		=> 'no_thanks_link',
		'value'		=> $this->lang->line('platinum_no_thank_you'),
		'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
	));

	echo form_close();
	?>

	<!-- <a class="add_upgrades_yellow decline_link" href="#" id="no_thanks_link"><strong>No Thanks</strong>, I'm Going To Pass On This Great Opportunity</a> -->

</div><!-- .padder -->

</div>


	<!-- Code to submit No Thanks form upon clicking the no thanks link -->
	<script type="text/javascript">
		$(document).ready(function(){
			$('#no_thanks_link, #no_thanks_link').click(function(){
				$('#no_thanks_form').submit();
				return false;
			});
		});
	</script>