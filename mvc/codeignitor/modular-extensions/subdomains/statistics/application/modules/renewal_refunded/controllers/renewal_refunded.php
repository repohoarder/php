<?php

class Renewal_refunded extends MX_Controller {


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

		$rdata=array();
		if ($brand=='all_brands')
		{
			$response = $this->platform->post('crm/reports/renewals/refunded/brainhost', $params);
			if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

				return;
			endif;
			$rdata['Brain Host']=$response['data']['brainhost'];
			
			$response = $this->platform->post('crm/reports/renewals/refunded/brazil_orders', $params);
			if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

				return;
			endif;
			$rdata['Brain Host Brazil']=$response['data']['brazil_orders'];
			
			$response = $this->platform->post('crm/reports/renewals/refunded/purelyhosting', $params);
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

			$response = $this->platform->post('crm/reports/renewals/refunded/'.$brand, $params);
			if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

				return;
			endif;
			$rdata['Refunds']=$response['data'][$brand];
		}
		
		
		
		$data['title']               = 'Renewals Refunded';
		$data['subtitle']            = ucwords(str_replace('_',' ',$brand));
		
		$data['label_y']             = 'Dollars (k)';
		$data['label_stack_format']  = '"$"+Highcharts.numberFormat(this.total,2)';
		
		
		$data['tooltip_format']      = "'<b>'+ hc_readable_date(this.x) +'</b><br/>'+
			this.series.name +': '+ '$'+Highcharts.numberFormat(this.y,2) +'<br/>'+
			'Total: '+ '$'+Highcharts.numberFormat(this.point.stackTotal,2)";
		
		$data['categories_x']        = array();
		
		$data['label_column_format'] = '"$"+(Highcharts.numberFormat(this.y,2))+"<br/>("+Highcharts.numberFormat(this.percentage,2)+"%)"';
		
		$all_series                  = array();


		################################
		## Begin ugly-ass empty date fix
		#################################

		$this->load->library('duct_tape');
		$rdata = $this->duct_tape->fix_series_gaps($rdata);

		################################
		## End god-awful hack
		###############################


		foreach ($rdata as $name => $series):

			$series_data = array();

			foreach ($series as $series_array):

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


		$this->load->view('highcharts/column_stacked', $data);




	}


}