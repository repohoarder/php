<?php

class Avg_order_val extends MX_Controller {

	function __construct()
	{

		$this->load->config('brands');

	}


	function index($brand = 'all_brands')
	{
		// set start and end date	
		$params = array(
			'start_date' => ($this->input->post('start_date') ? $this->input->post('start_date') : date('Y-m-d',strtotime('-7 days'))),
			'end_date'   => ($this->input->post('end_date') ? $this->input->post('end_date') : date('Y-m-d'))
		);
		
		
		// API CALLS FOR PLATFORM DATA
		
			// ALL BRANDS OR ONE BRAND ???
			
				$show_brands = $this->config->item('brandsdb');
				$switch_brands = $this->config->item('brands_switch');

				if ($brand != 'all_brands') {
				
					if ( ! array_key_exists($brand, $show_brands)):

						$brand = $switch_brands[$brand];

					endif;

					$show_brands = array(
						$brand => $show_brands[$brand]
					);
				
				}
							
		// BRANDS PLATFORM CALL

			$brands = array(); // key = db, val = name
			$averages = array();
	
			foreach ($show_brands as $key=>$val):
	
				$params['brand'] = $key;
				$brands[$val] = $this->platform->post('crm/reports/sales/avg_order_val_orders', $params);
				$averages[$val] = $this->platform->post('crm/reports/sales/avg_order_val_average', $params);

			endforeach;
			
		// RECONSTRUCT THE RESPONSE	
			
			$stats = array();
			
			foreach ($brands as $user=>$result) {
			
				$stats[$user] = $result['data'];
				$stats[$user.' Average']=$stats[$user];
				foreach ($stats[$user.' Average'] as $key=>$value)
				{
					$stats[$user.' Average'][$key]['amount']=$averages[$user]['data'][0]['amount'];
				}
			
			}
			
			if (count($stats) < 1) {
				$sucess = 0;
			
			} else {
				$success = 1;
			
			}

			$response = array(
				'success' => $success,
				'error'   => array(),
				'data'    => $stats
			);
				
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;

		endif;
		
		$data['title']          = 'Average Order (Revenue) Values with Refunds';
		$data['subtitle']       = ucwords(str_replace('_',' ',$brand));
		
		$data['label_y']        = 'Dollars';
		$data['label_y_format'] = 'this.value';
		
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - $"+Highcharts.numberFormat(this.y,2)';
		
		$data['categories_x']   = array();		
		$all_series             = array();


		$this->load->library('duct_tape');
		$response['data'] = $this->duct_tape->fix_series_gaps($response['data']);
		

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


		$this->load->view('highcharts/line_basic', $data);




	}


}