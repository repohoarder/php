<?php

class Sales_counts extends MX_Controller {


	function index($brand = 'brain_host')
	{


		$params['start_date'] = $this->input->post('start_date') ? $this->input->post('start_date') : date('Y-m-d',strtotime('-7 days'));
		$params['end_date']   = $this->input->post('end_date')   ? $this->input->post('end_date')   : date('Y-m-d');


		$response = $this->platform->post('crm/reports/sales_count/get/'.$brand, $params);

		if ( ! $response['success']):
			
			return;

		endif;


		$data['title']          = 'Sales Count';
		$data['subtitle']       = ucwords(str_replace('_',' ',$brand));
		
		$data['label_y']        = 'Sales';
		$data['label_y_format'] = 'this.value';
		
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - "+this.y';
		
		$data['categories_x']   = array();		
		$all_series             = array();



		$this->load->library('duct_tape');
		$response['data'] = $this->duct_tape->fix_series_gaps($response['data']);



		foreach ($response['data'] as $name => $series):

			$series_data = array();

			foreach ($series as $series_array):

				$data['categories_x'][] = date('Y-m-d',mktime(0, 0, 0, $series_array['month'], $series_array['day'],$series_array['year'])); 

				$series_data[] = floatval($series_array['amount']);

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