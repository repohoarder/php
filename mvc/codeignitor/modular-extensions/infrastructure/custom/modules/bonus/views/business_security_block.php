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
			$price		= $this->prices->get($funnel_id,$partner_id,$affiliate_id,$offer_id,$plan_id,$variant,$term);

			// make sure we grabbed a valid price
			if (is_float($price)):
?>
				<table>
					<thead>
						<tr>
							<th colspan="2" class="custom-color1"><label for="chkSecurity1"><?php echo $service['name']; ?></label></th>
							<td class="custom-color1"><input type="checkbox" name="security_alacarte[]" id="service_<?php echo $slug;?>" value="<?php echo $slug;?>" />
							<label for="service_<?php echo $slug;?>">$<?php echo number_format($price,2); ?></label></td>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td class="first"></td>
							<td class="second"></td>
							<td class="third"></td>
					</tfoot>
					<tbody>
						<tr>
							<td><label for="chkSecurity1"><img src="/resources/modules/bonus/assets/<?php echo $service_config[$slug]['img'];?>" alt="<?php echo $service['name']; ?>" /></label></td>
							<td colspan="2"><label for="chkSecurity1"><?php echo $service_config[$slug]['content'];?></label></td>
						</tr>
					</tbody>
				</table>
<?php
			endif;	// end making sure we grabbed a valid price
		endif; // end making sure we were able to grab a plan id
	endif;	// end making sure we were able to grab service details
endif;	// end making sure plan was passed
?>
