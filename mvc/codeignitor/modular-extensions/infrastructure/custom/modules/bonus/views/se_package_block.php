<?php 
$this->load->config('bonus');
$service_config = $this->config->item('services');
// initialize array of term periods
$period 	= array(
	'0'		=> 'one-time fee',
	'1'		=> 'monthly',
	'6'		=> 'semi-annually',
	'12'	=> 'annually',
	'24'	=> 'bi-annually'
);

// make sure a plan was passed
if (isset($slug) AND ! empty($slug) && isset($service_config[$slug])):

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
			$cost		= $this->prices->get($funnel_id,$partner_id,$affiliate_id,$offer_id,$plan_id,$variant,$term);
			
			// make sure we grabbed a valid price
			if (is_float($cost)):
?>
					<tr>
						<th colspan="4" class="custom-color1"><?php echo $service['name']; ?></th>
					</tr>
					<tr>
						<td class="a"><img src="/resources/modules/bonus/assets/<?php echo $service_config[$slug]['img'];?>" alt="Google+ Local Listings" /></td>
						<td class="b"><?php echo $service_config[$slug]['content'];?></td>
						<td class="c"><input type="checkbox" name="se_package_alacarte[]" id="chk_<?php echo $slug;?>" value="<?php echo $slug;?>"/></td>
						<td class="d custom-color1"><label for="chk_<?php echo $slug;?>">$<?php echo number_format($cost,2); ?></label></td>
					</tr>
				
<?php
			endif;	// end making sure we grabbed a valid price
		endif; // end making sure we were able to grab a plan id
	endif;	// end making sure we were able to grab service details
endif;	// end making sure plan was passed
?>

