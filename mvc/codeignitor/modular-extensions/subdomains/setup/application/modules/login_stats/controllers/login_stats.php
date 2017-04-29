<?php

class Login_stats extends MX_Controller {


	function index()
	{

		$params = array(
			'start_date' => date('m/d/Y',strtotime('-1 month')),
			'end_date'   => date('m/d/Y')
		);

		if ($this->input->post('submitted')):

			$params['start_date'] = $this->input->post('start_date');
			$params['end_date']   = $this->input->post('end_date');

		endif;

		$post_params = array();
		foreach ($params as $which => $date):
			$post_params[$which] = date('Y-m-d',strtotime($date));
		endforeach;

		$response = $this->platform->post(
			'sitebuilder/track_login/get_all_brands_login_stats',
			$post_params
		);

		if ( ! $response['success']):

			show_error('Whoops');
			return;

		endif;

		$data['dates'] = $params;
		$data['stats'] = $response['data'];
		$this->load->view('stats', $data);

	}

}