<?php 

// initialize array of term periods
$period 	= array(
	'0'		=> 'one-time fee',
	'1'		=> 'monthly',
	'6'		=> 'semi-annually',
	'12'	=> $this->lang->line('annually'),
	'24'	=> 'bi-annually'
);

// make sure a plan was passed
if (isset($slug) AND ! empty($slug)):

	// get plan information
	$service 	= $this->platform->post('sales_funnel/page/get',array('slug' => $slug));

	// make sure we were able to grab service information
	if ($service['success'] AND ! empty($service['data'])):
	
		// initialize variables
		$service 	= $service['data'];

		// grab plan id from slug
		$plan_id 	= $this->platform->post('ubersmith/package/get_plan_id',array('slug' => $slug));

		// make sure we were able to grab plan_id
		if ($plan_id['success'] AND is_numeric($plan_id['data'])):

			// set plan id
			$plan_id 	= $plan_id['data'];

			// load prices library
			$this->load->library('prices');

			// grab prices data for this plan
			$price		= $this->prices->get($funnel_id,$partner_id,$affiliate_id,$offer_id,$plan_id,$variant,$term);

			// make sure we grabbed a valid price
			if (is_float($price)):
?>

				<fieldset class="item">
					<div class="checkbox">
						<img src="/resources/modules/bonus/assets/img/<?php echo $this->session->userdata('_language'); ?>/platinum_block/<?php echo $slug; ?>.jpg" width="106" height="72" alt="<?php echo $this->lang->line($slug.'_title'); ?>" class="upsell_icon"/>
						<span>
							<input type="checkbox" name="platinum_alacarte[]" value="<?php echo $slug; ?>" id="<?php echo $slug; ?>" style="" onclick="toggle_upsell_block(this);"  />
							<label for="<?php echo $slug; ?>"><?php echo $this->lang->line('bonus_platinum_package_feature_add'); ?></label>
						</span>
					</div>
					<h3 style="text-align:left;">
						<?php echo $this->lang->line($slug.'_title'); ?>
						<em>$<?php echo number_format($price,2); ?> <span>/<?php echo $period[$term]; ?></span></em>
						<del>$<?php echo number_format(($price*2),2); ?></del>
					</h3>
					<p style="text-align:left;"><?php echo $this->lang->line($slug.'_description'); ?></p>
				</fieldset>

<?php
			endif;	// end making sure we grabbed a valid price
		endif; // end making sure we were able to grab a plan id
	endif;	// end making sure we were able to grab service details
endif;	// end making sure plan was passed
?>
