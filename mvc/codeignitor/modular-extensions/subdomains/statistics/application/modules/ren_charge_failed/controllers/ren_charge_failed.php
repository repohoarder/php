<?php

class Ren_charge_failed extends MX_Controller {


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

			$response = $this->platform->post('crm/reports/renewals/charged/'.$brand, $params);
			if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

				return;
			endif;
			$rdata['Charged']=$response['data'][$brand];
			
			$response = $this->platform->post('crm/reports/renewals/failed/'.$brand, $params);
			if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

				return;
			endif;
			$rdata['Failed']=$response['data'][$brand];
		}
		
		
		
		$data['title']               = 'Renewal Charged vs Failed';
		$data['subtitle']       = ucwords(str_replace('_',' ',$brand));
		
		$data['label_y']        = 'Count';
		$data['label_y_format'] = 'this.value';
		
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - "+this.y';
		
		$data['categories_x']   = array();		
		$all_series             = array();


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


		$this->load->view('highcharts/line_basic', $data);




	}


}