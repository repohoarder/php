<?php

class Time_tracker extends MX_Controller 
{

	public function index()
	{
		// initialize variables
		$start = $this->input->post('start_date') ? $this->input->post('start_date') : date('Y-m-d',strtotime('-7 days'));
		$end   = $this->input->post('end_date')   ? $this->input->post('end_date')   : date('Y-m-d');

		$response = $this->platform->post('time/statistic/ratios/'.$start.'/'.$end, array());

		// error handling
		if ( ! $response['success'])
			return;

		$data['title']          = 'Time Tracking Ratios';
		$data['subtitle']       = 'All Brands';
		
		$data['label_y']        = 'Sales';
		$data['label_y_format'] = 'this.value';
		
		$data['tooltip_format'] = 'hc_readable_date(this.x) +" - "+this.y';
		
		$data['categories_x']   = array();		
		$all_series             = array();
	}
}