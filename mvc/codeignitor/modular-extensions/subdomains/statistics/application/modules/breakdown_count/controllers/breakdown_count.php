<?php

class Breakdown_count extends MX_Controller {


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

		$response = $this->platform->post('crm/reports/breakdown/count/'.$brand, $params);
		if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):

			return;
		endif;
		$rdata=$response['data'];
		
		
		################################
		## Begin ugly-ass empty date fix
		#################################

		$this->load->library('duct_tape');
		$rdata = $this->duct_tape->fix_series_gaps($rdata);

		################################
		## End god-awful hack
		###############################
		
		$data['title']               = 'Product Breakdown Count';
		
		$data['label_y']        = 'Count';
		$data['label_y_format'] = 'this.value';
		
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - "+this.y';
		
		$data['categories_x']   = array();		
		$all_series             = array();
		
		$all_series                  = array();

		$graphlimit=5;
		
		$i=0;
		foreach ($rdata as $pname => $plan):
			
			$i++;

			$series_data = array();

			foreach ($plan as $plan_array):

				//$data['categories_x'][] = date("M", mktime(0, 0, 0, $series_array['month'])).' '.$series_array['day'];
				$data['categories_x'][] = date('Y-m-d',mktime(0, 0, 0, $plan_array['month'], $plan_array['day'],$plan_array['year'])); 

				$series_data[] = (float)$plan_array['amount'];

			endforeach;

			$all_series[] = array(
				'name' => $plan_array['title'],
				'data' => $series_data
			);

			if ($i%$graphlimit==0)
			{
				$data['label_x_format'] = "hc_readable_date(this.value)";
				$data['series'] = $all_series;

				$this->load->view('highcharts/line_basic', $data);
				$all_series=array();
			}

		endforeach;
		if ($i%$graphlimit!=0)
		{
			$data['label_x_format'] = "hc_readable_date(this.value)";
			$data['series'] = $all_series;

			$this->load->view('highcharts/line_basic', $data);
		}

	}


}