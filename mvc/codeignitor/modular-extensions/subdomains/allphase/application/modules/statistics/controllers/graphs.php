<?php

class Graphs extends MX_Controller {

	function __construct()
	{

		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');

	}

	function ajax_revenue()
	{

		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');

		$resp = $this->platform->post(
			'partner/statistics/sale/get_date_range_sales',
			array(
				'end_date'   => date('Y-m-d', strtotime($end_date)),
				'start_date' => date('Y-m-d', strtotime($start_date)),
				'partner_id' => $this->_partner['id']
			)
		);

		echo json_encode($resp);

	}

	function ajax_visitors()
	{

		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');

		$resp = $this->platform->post(
			'partner/statistics/visitor/get_date_range_visitors',
			array(
				'end_date'   => date('Y-m-d', strtotime($end_date)),
				'start_date' => date('Y-m-d', strtotime($start_date)),
				'partner_id' => $this->_partner['id']
			)
		);

		echo json_encode($resp);

	}

	function ajax_epc()
	{

		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');

		$resp = $this->platform->post(
			'partner/statistics/epc/get_date_range_epc',
			array(
				'end_date'   => date('Y-m-d', strtotime($end_date)),
				'start_date' => date('Y-m-d', strtotime($start_date)),
				'partner_id' => $this->_partner['id']
			)
		);
		$x=0;
		foreach($resp['data'] as $record):
			if(!isset($record['epc'])){
				$record['epc']= 0;
			}
			$response['data'][$x]['epc'] = $record['epc'];
			$response['data'][$x]['date'] = $record['date'];
			$x++;
		endforeach;
		$response['success'] = $resp['success'];
		echo json_encode($response);

	}

	function epc($partner_id)
	{

		$data['title']          = 'EPC by Date';
		$data['subtitle']       = 'Past 7 Days';
		$data['label_y']        = 'Earnings-per-click';
		$data['label_y_format'] = 'this.value';
		$data['charts_div']     = '#epc_statistics';
		$data['chart_height']   = 250;
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - $"+Highcharts.numberFormat(this.y,2)';
		$data['label_x_format'] = "hc_readable_date(this.value)";
		$data['categories_x']   = array();	
		$data['series']         = array();

		$v_resp = $this->platform->post(
			'partner/statistics/visitor/get_date_range_visitors',
			array(
				'end_date'   => date('Y-m-d',strtotime('+1 day')),
				'start_date' => date('Y-m-d',strtotime('-7 days')),
				'partner_id' => $partner_id
			)
		);

		$s_resp = $this->platform->post(
			'partner/statistics/sale/get_date_range_sales',
			array(
				'end_date'   => date('Y-m-d',strtotime('+1 day')),
				'start_date' => date('Y-m-d',strtotime('-7 days')),
				'partner_id' => $partner_id
			)
		);

		if ( ! $v_resp['success'] || ! $s_resp['success']):

			return;

		endif;

		$visitors = $v_resp['data'];
		$sales    = $s_resp['data'];

		$graph_data = array();

		foreach ($visitors as $num => $vdata):

			$graph_data[$vdata['date']]['visitors'] = $vdata['num_visitors'];
			$graph_data[$vdata['date']]['date']     = $vdata['date'];
			
			$graph_data[$vdata['date']]['revenue']  = 0.00;
			$graph_data[$vdata['date']]['sales']    = 0;

		endforeach;

		foreach ($sales as $num => $sdata):

			$graph_data[$sdata['date']]['sales']   = $sdata['num_sales'];
			$graph_data[$sdata['date']]['revenue'] = $sdata['revenue'];
			$graph_data[$sdata['date']]['date']    = $sdata['date'];

			if ( ! isset($graph_data[$sdata['date']]['visitors'])):

				$graph_data[$sdata['date']]['visitors'] = 0;

			endif;

		endforeach;

		foreach ($graph_data as $date => $gdata):

			$graph_data[$gdata['date']]['epc'] = ($gdata['visitors'] > 0) ? $gdata['revenue'] / $gdata['visitors'] : 0;
			$graph_data[$gdata['date']]['epc'] = number_format($graph_data[$gdata['date']]['epc'],2);

		endforeach;

		$series = array(
			'name' => 'EPC',
			'data' => array()
		);

		foreach ($graph_data AS $row):

			$data['categories_x'][] = $row['date'];
			$series['data'][]       = floatval($row['epc']);

		endforeach; 

		$data['series'] = array(
			$series
		);
	
		$this->load->view('highcharts/line_basic', $data);
		$this->load->view('statistics/epc_graph');

	}




	function visitors($partner_id)
	{
		
		$data['title']          = 'Visitors by Date';
		$data['subtitle']       = 'Past 7 Days';
		$data['label_y']        = 'Visitors';
		$data['label_y_format'] = 'this.value';
		$data['label_y_decimals'] = 'doit';
		$data['charts_div']     = '#visitors_statistics';
		$data['chart_height']   = 250;
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - "+Highcharts.numberFormat(this.y,0)';
		$data['label_x_format'] = "hc_readable_date(this.value)";
		$data['categories_x']   = array();	
		$data['series']         = array();

		$resp = $this->platform->post(
			'partner/statistics/visitor/get_date_range_visitors',
			array(
				'end_date'   => date('Y-m-d',strtotime('+1 day')),
				'start_date' => date('Y-m-d',strtotime('-7 days')),
				'partner_id' => $partner_id
			)
		);

		if ( ! $resp['success']):

			return;

		endif;

		$series = array(
			'name' => 'Visitors',
			'data' => array()
		);

		foreach ($resp['data'] AS $row):

			$data['categories_x'][] = $row['date'];
			$series['data'][]       = floatval($row['num_visitors']);

		endforeach;

		$data['series'] = array(
			$series
		);
	
		$this->load->view('highcharts/line_basic', $data);
		$this->load->view('statistics/visitors_graph');

	}

	function revenue($partner_id)
	{

		$data['title']          = 'Sales Revenue by Date';
		$data['subtitle']       = 'Past 7 Days';
		$data['label_y']        = 'Dollars';
		$data['label_y_format'] = 'this.value';
		$data['charts_div']     = '#sales_statistics';
		$data['chart_height']   = 250;
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - $"+Highcharts.numberFormat(this.y,2)';
		$data['label_x_format'] = "hc_readable_date(this.value)";
		$data['categories_x']   = array();	
		$data['series']         = array();

		$resp = $this->platform->post(
			'partner/statistics/sale/get_date_range_sales',
			array(
				'end_date'   => date('Y-m-d',strtotime('+1 day')),
				'start_date' => date('Y-m-d',strtotime('-7 days')),
				'partner_id' => $partner_id
			)
		);

		if ( ! $resp['success']):
			
			return;

		endif;

		$series = array(
			'name' => 'Revenue',
			'data' => array()
		);

		foreach ($resp['data'] AS $row):

			$data['categories_x'][] = $row['date'];
			$series['data'][]       = floatval($row['revenue']);

		endforeach;

		$data['series'] = array($series);
	
		$this->load->view('highcharts/line_basic', $data);
		$this->load->view('statistics/sales_graph');
	}

	function ajax_tickets()
	{

		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');

		$resp = $this->platform->post(
			'partner/customer_support/tickets_by_day',
			array(
				'end_date'   => date('Y-m-d', strtotime($end_date)),
				'start_date' => date('Y-m-d', strtotime($start_date)),
				'partner_id' => $this->_partner['id']
			)
		);

		echo json_encode($resp);

	}
	
	function ajax_calls()
	{

		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');

		$resp = $this->platform->post(
			'five9/partner/calls_by_day',
			array(
				'end_date'   => date('Y-m-d', strtotime($end_date)),
				'start_date' => date('Y-m-d', strtotime($start_date)),
				'partner_id' => $this->_partner['id']
			)
		);
		
		foreach($resp['data'] as $date=>$array):
			$resp['data'][] = $array;
		endforeach;
		echo json_encode($resp);

	}

	function tickets($partner_id)
	{
		
		$data['title']          = 'Customer Support Tickets by Date';
		$data['subtitle']       = 'Past 7 Days';
		$data['label_y']        = 'Tickets';
		$data['label_y_format'] = 'this.value';
		$data['charts_div']     = '#tickets_statistics';
		$data['chart_height']   = 250;
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - "+Highcharts.numberFormat(this.y,0)';
		$data['label_x_format'] = "hc_readable_date(this.value)";
		$data['categories_x']   = array();	
		$data['series']         = array();

		$resp = $this->platform->post(
			'partner/customer_support/tickets_by_day',
			array(
				'end_date'   => date('Y-m-d',strtotime('+1 day')),
				'start_date' => date('Y-m-d',strtotime('-7 days')),
				'partner_id' => $partner_id
			)
		);

		if ( ! $resp['success']):

			return;

		endif;

		$series = array(
			'name' => 'Tickets',
			'data' => array()
		);
	
		foreach ($resp['data'] AS $row):
		
			$data['categories_x'][] = $row['date'];
			$series['data'][]       = floatval($row['num_tickets']);
			//endif;
		endforeach;

		$data['series'] = array(
			$series
		);
	
		$this->load->view('highcharts/line_basic', $data);
		$this->load->view('statistics/tickets_graph');

	}

	function minutes($partner_id)
	{
		
		$data['title']          = 'Customer Support Minutes by Date';
		$data['subtitle']       = 'Past 7 Days';
		$data['label_y']        = 'Minutes';
		$data['label_y_format'] = 'this.value';
		$data['charts_div']     = '#minutes_graph';
		$data['chart_height']   = 250;
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - "+Highcharts.numberFormat(this.y,0)';
		$data['label_x_format'] = "hc_readable_date(this.value)";
		$data['categories_x']   = array();	
		$data['series']         = array();

		$resp = $this->platform->post(
			'five9/partner/calls_by_day',
			array(
				'end_date'   => date('Y-m-d',strtotime('+1 day')),
				'start_date' => date('Y-m-d',strtotime('-7 days')),
				'partner_id' => $partner_id
			)
		);

		if ( ! $resp['success']):

			return;

		endif;

		$series = array(
			'name' => 'Minutes',
			'data' => array()
		);

		foreach ($resp['data'] AS $row):

			$data['categories_x'][] = $row['date'];
			$series['data'][]       = floatval($row['num_minutes']);

		endforeach;

		$data['series'] = array(
			$series
		);
	
		$this->load->view('highcharts/line_basic', $data);
		$this->load->view('statistics/minutes_graph');

	}
	
	function calls($partner_id)
	{
		
		$data['title']          = 'Customer Support Calls by Date';
		$data['subtitle']       = 'Past 7 Days';
		$data['label_y']        = 'Calls';
		$data['label_y_format'] = 'this.value';
		$data['charts_div']     = '#calls_graph';
		$data['chart_height']   = 250;
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - "+Highcharts.numberFormat(this.y,0)';
		$data['label_x_format'] = "hc_readable_date(this.value)";
		$data['categories_x']   = array();	
		$data['series']         = array();

		$resp = $this->platform->post(
			'five9/partner/calls_by_day',
			array(
				'end_date'   => date('Y-m-d',strtotime('+1 day')),
				'start_date' => date('Y-m-d',strtotime('-7 days')),
				'partner_id' => $partner_id
			)
		);

		if ( ! $resp['success']):
			return;

		endif;

		$series = array(
			'name' => 'Calls',
			'data' => array()
		);

		foreach ($resp['data'] AS $row):

			$data['categories_x'][] = $row['date'];
			$series['data'][]       = floatval($row['num_calls']);

		endforeach;

		$data['series'] = array(
			$series
		);
	
		$this->load->view('highcharts/line_basic', $data);
		$this->load->view('statistics/calls_graph');

	}
}