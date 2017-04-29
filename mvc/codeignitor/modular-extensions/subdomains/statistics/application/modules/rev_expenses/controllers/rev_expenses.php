<?php

class Rev_expenses extends MX_Controller {


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
		
		$brand='all_brands';

		$rdata=array();

		$response = $this->platform->post('crm/reports/revenue/gross/brainhost', $params);
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;
		endif;
		$rdata['Total']=$response['data']['brainhost'];
		
		$response = $this->platform->post('crm/reports/revenue/gross/brazil_orders', $params);
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;
		endif;
		foreach ($response['data']['brazil_orders'] as $key=>$value)
		{
			$rdata['Total'][$key]['amount']+=$value['amount'];
		}
		
		$response = $this->platform->post('crm/reports/revenue/gross/purelyhosting', $params);
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;
		endif;
		foreach ($response['data']['purelyhosting'] as $key=>$value)
		{
			$rdata['Total'][$key]['amount']+=$value['amount'];
		}
		
		/*$response = $this->platform->post('crm/reports/affiliate/affiliates/'.$brand, $params);
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;
		endif;
		$rdata['Gross Payroll Expenses']=$response['data'][$brand];*/
		
		
		foreach ($rdata['Total'] as $aryTotal)
		{
			$aryTotal['amount']*=0.18;
			$rdata['Sales Team Revenue'][]=$aryTotal;
		}
		
		$data['title']          = 'Revenue vs. Expenses';
		$data['subtitle']       = ucwords(str_replace('_',' ',$brand));
		
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