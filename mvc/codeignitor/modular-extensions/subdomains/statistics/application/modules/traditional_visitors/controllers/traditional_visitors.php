<?php

class Traditional_visitors extends MX_Controller {

	function index($brand = 'brain_host')
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

		$oldBrand=$brand;

		if ($brand=='all_brands')
		{
			$brand='brainhost';
			$oldBrand='brain_host';
		}
		elseif ($brand=='brain_host')
		{
			$brand='brainhost';
		}
		elseif ($brand=='brain_host_brazil')
		{
			$brand='brazil_orders';
		}
		elseif ($brand=='purely_hosting')
		{
			$brand='purelyhosting';
		}


		$response = $this->platform->post('affiliate/reports/tracking_traffic/affiliate/'.$brand, $params);
		
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;

		endif;
		
		$traffic=$response;
		
		$response = $this->platform->post('google_api/visits/get/'.$oldBrand, $params);
		
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;

		endif;
		$aryData=$response['data'];
		unset($response['data']);
		$response['data']['Total']=$aryData;
		
		foreach ($traffic['data'][$brand] as $key=>$value)
		{
			$value['amount']=$response['data']['Total'][$key]['amount']-$value['amount'];
			$response['data']['Traditional'][$key]=$value;
		}
		
		$response['data']['Affiliate']=$traffic['data'][$brand];
		
		$data['title']          = 'Total vs. Traditional Traffic';
		$data['subtitle']       = ucwords(str_replace('_',' ',$brand)).' (Visitors)';
		
		$data['label_y']        = 'Visitors (k)';
		$data['label_y_format'] = 'this.value / 1000 + "k"';
		
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - "+Highcharts.numberFormat(this.y, 0)';
		
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


		$this->load->view('highcharts/area_basic', $data);




	}


}