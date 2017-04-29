<?php

class Total_visitors extends MX_Controller {

	function __construct()
	{

		$this->load->config('brands');

	}

	function index($brand = 'all_brands')
	{

		$params = array(
			'start_date' => (
				$this->input->post('start_date') 
					? $this->input->post('start_date') 
					: date('Y-m-d',strtotime('-7 days'))
			),
			'end_date'   => (
				$this->input->post('end_date') 
					? $this->input->post('end_date') 
					: date('Y-m-d')
			),
		);

		$show_brands = $this->config->item('brands');

		if ($brand !== 'all_brands'):

			$show_brands = array(
				$brand => $show_brands[$brand]
			);

		endif;

		$brands = array();

		foreach ($show_brands as $key=>$val):

			$brands[$val] = $this->platform->post('google_api/visitors/get/'.$key, $params);

		endforeach;

		/*
		$brands = array(
			'Brain Host'        => $this->platform->post('google_api/visitors/get/brain_host', $params),
			'Purely Hosting'    => $this->platform->post('google_api/visitors/get/purely_hosting', $params),
			'Brain Host Brazil' => $this->platform->post('google_api/visitors/get/brain_host_brazil', $params)
		);
		 */
		
		
		$stats = array();

		foreach ($brands as $site=>$visits):

			if ($visits['success']):

				$stats[$site] = $visits['data'];

			endif;

		endforeach;

		// do platform request here

		$response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => $stats
		);
		
		
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;

		endif;
		
		$data['title']          = 'Total Visitors';
		$data['subtitle']       = ucwords(str_replace('_',' ',$brand));
		
		$data['label_y']        = 'Visitors';
		$data['label_y_format'] = 'this.value';
		
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - "+Highcharts.numberFormat(this.y,0)';
		
		$data['categories_x']   = array();		
		$all_series             = array();

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