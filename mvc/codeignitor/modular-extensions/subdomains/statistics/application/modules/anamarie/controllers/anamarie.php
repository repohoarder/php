<?php

class Anamarie extends MX_Controller {

	function __construct()
	{

		$this->load->config('brands');

	}


	function index($brand = 'brainhost')
	{

		$switch_brands = $this->config->item('brands_switch');

		if ($brand != 'all_brands'):

			if (array_key_exists($brand, $switch_brands)):

				$brand = $switch_brands[$brand];

			endif;

		endif;

	
		// set start and end date	
		$params = array(
			'start_date' => ($this->input->post('start_date') ? $this->input->post('start_date') : date('Y-m-d',strtotime('-7 days'))),
			'end_date'   => ($this->input->post('end_date') ? $this->input->post('end_date') : date('Y-m-d'))
		);
		
		$params['brand'] = $brand;
		
		// PLATFORM REQUEST
	
			// grab revenue
			$revenue	= $this->platform->post('crm/reports/anamariez/revenues', $params);
			
			// validate revenue
			if ( ! $revenue || ! array_key_exists('success',$revenue) || ! $revenue['success']):
				return;
			endif;
						
			// grab refunds
			$refunds	= $this->platform->post('crm/reports/anamariez/refunds', $params);
			
			// validate refunds
			if ( ! $refunds || ! array_key_exists('success',$refunds) || ! $refunds['success']):
				return;
			endif;
			
			// grab refunds
			$chargebacks	= $this->platform->post('crm/reports/anamariez/chargebacks', $params);
			
			// validate refunds
			if ( ! $chargebacks || ! array_key_exists('success',$chargebacks) || ! $chargebacks['success']):
				return;
			endif;
		
		// concat refunds and revenue
		
			$response['data']['Revenue']	= $revenue['data']['Revenue'];
			$response['data']['Refunds']	= $refunds['data']['Refunds'];
			$response['data']['Chargebacks']	= $chargebacks['data']['Chargebacks'];

		// PREPARE OUTPUT	
			
			$data['title']          = 'Revenue vs. Refunds vs. Chargebacks #?%?$';
			$data['subtitle']       = ucwords(str_replace('_',' ',$brand)).' (Total)';
			
			$data['label_y']        = 'Dollars (k)';
			$data['label_y_format'] = 'this.value / 1000 + "k"';
			
			$data['tooltip_format'] = 'hc_readable_date(this.x) +" - $"+Highcharts.numberFormat(this.y,2)';
			
			$data['categories_x']   = array();		
			$all_series             = array();
			
			################################
			## Begin ugly-ass empty date fix
			#################################

			$this->load->library('duct_tape');
			$response['data'] = $this->duct_tape->fix_series_gaps($response['data']);

			################################
			## End god-awful hack
			###############################
	
			foreach ($response['data'] as $name => $series):
	
				$series_data = array();
	
				foreach ($series as $series_array):
	
					//$data['categories_x'][] = date("M", mktime(0, 0, 0, $series_array['month'])).' '.$series_array['day'];
					$data['categories_x'][] = date('Y-m-d',mktime(0, 0, 0, $series_array['month'], $series_array['day'],$series_array['year'])); 
	
					$series_data[] = (float)$series_array['amount'];
	
				endforeach;
	
				$all_series[] = array(
					'name' => $name,
					'data' => $series_data
				);
	
			endforeach;
	
			$data['label_x_format'] = "hc_readable_date(this.value)";
	
			$data['series'] = $all_series;

		// OUTPUT

			$this->load->view('highcharts/area_basic', $data);
		
	}

	// ####### NUMBER FUNCTIONS
				
	function doubleDigit($number) { // ADD A 0 TO A 1 DIGIT NUMBER FOR 00
	
		if (strlen($number) == 1) {
			$number = "0".$number;
		
		}
	
		return $number;
	
	}

}