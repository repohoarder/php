<?php

class Ticker extends MX_Controller {


	function index($brand = 'brain_host')
	{


		$params['start_date'] = $this->input->post('start_date') ? $this->input->post('start_date') : date('Y-m-d');
		$params['end_date']   = $this->input->post('end_date')   ? $this->input->post('end_date')   : date('Y-m-d',strtotime('tomorrow'));

		$rdata=array();
		if ($brand=='all_brands')
		{
			$response = $this->platform->post('ubersmith/reports/ticker/toc/brainhost', $params);
			if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

				return;
			endif;
			$rdata['Brain Host']=$response['data']['brainhost'];
			
			$response = $this->platform->post('ubersmith/reports/ticker/toc/brazil_orders', $params);
			if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

				return;
			endif;
			$rdata['Brain Host Brazil']=$response['data']['brazil_orders'];
			
			$response = $this->platform->post('ubersmith/reports/ticker/toc/purelyhosting', $params);
			if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

				return;
			endif;
			$rdata['Purely Hosting']=$response['data']['purelyhosting'];
		}
		else
		{
			if ($brand=='brain_host')
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

			$response = $this->platform->post('ubersmith/reports/ticker/toc/'.$brand, $params);
			if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

				return;
			endif;
			$rdata['Profit']=$response['data'][$brand];
		}

		$data['title']          = 'Profit Growth';
		$data['subtitle']       = ucwords(str_replace('_',' ',$brand));
		
		$data['label_y']        = 'Profit ($)';
		$data['label_y_format'] = 'this.value';
		
		$data['tooltip_format'] = 'this.x +" - $"+this.y';
		
		$data['categories_x']   = array();		
		$all_series             = array();



		foreach ($rdata as $name => $series):

			$series_data = array();

			foreach ($series as $label => $adata):

				$data['categories_x'][] = $label; 

				$series_data[] = floatval($adata);

			endforeach;

			$all_series[] = array(
				'name' => $name,
				'data' => $series_data
			);

		endforeach;

		$data['label_x_format'] = "this.value";
		
		$data['series'] = $all_series;


		$this->load->view('highcharts/line_basic', $data);


	}


}