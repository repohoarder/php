<?php

class Aff_rev_comm extends MX_Controller {


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
		
		
		if ($brand=='all_brands')
		{
			$brand='brainhost';
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

		$rdata=array();

		$response = $this->platform->post('crm/reports/affiliate/revenue/'.$brand, $params);
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;
		endif;
		$rdata['Revenue']=$response['data'][$brand];
		
		$response = $this->platform->post('crm/reports/affiliate/commission/'.$brand, $params);
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;
		endif;
		$rdata['Commission']=$response['data'][$brand];
		//var_dump($data);
		
		
		$data['title']          = 'Affiliate Revenue vs. Commissions';
		$data['subtitle']       = ucwords(str_replace('_',' ',$brand)). ' (New Customers)';
		
		$data['label_y']        = 'Dollars (k)';
		$data['label_y_format'] = 'this.value / 1000 + "k"';
		
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - $"+Highcharts.numberFormat(this.y,2)';
		
		$data['categories_x']   = array();		
		$all_series             = array();
		
		foreach ($rdata as $name => $series):

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