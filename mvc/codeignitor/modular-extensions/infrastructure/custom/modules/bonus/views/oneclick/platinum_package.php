<?php 
// initialize varibales
$form_submission	= '';
$attributes			= array(
	'method'	=> 'POST'
);
$hidden_fields		= array('action_id' => 16);
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

			/*
			// load brain host directory
			$this->load->view(
				'bonus/platinum_package_block',
				array(
					'slug' 			=> 'directory_listing', 
					'partner_id' 	=> $partner_id,
					'funnel_id'		=> $funnel_id,
					'affiliate_id'	=> $affiliate_id,
					'offer_id'		=> $offer_id,
					'variant'		=> 'default',
					'term'			=> '12'
				)
			);
			*/

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
				'name'		=> 'submit',
				'type'		=> 'submit',
				'class'		=> 'btn-add-upgrades hide-text',
				'value'		=> 'Give me this bonus!',
				'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
			));
			echo form_close();
			?>
		<?php if( ! isset($shownothanks) ) : ?>
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
				'name'		=> 'submit',
				'type'		=> 'submit',
				'class'		=> 'lbl-nothanks',
				'id'		=> 'lbl-nothanks',
				'value'		=> $this->lang->line('bonus_platinum_package_no_thanks'),
				'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
			));

			?>
			<!-- <p class="lbl-nothanks"><a href="#"><?php echo $this->lang->line('bonus_platinum_package_no_thanks'); ?></a></p> -->
		<?php echo form_close(); 
		endif;
		?>
	</div>
</div>