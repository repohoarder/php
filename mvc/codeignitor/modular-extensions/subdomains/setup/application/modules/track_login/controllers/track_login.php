<?php

class Track_login extends MX_Controller {


	function index()
	{

		$this->load->library('platform');
	
		$params = array(
			'domain' => $this->input->post('domain')
		);

		if ( ! $params['domain'] || strpos($params['domain'], '.') === FALSE) :

			return FALSE;

		endif;


		$response = $this->platform->post('sitebuilder/track_login/domain/'.$params['domain'], array());

		echo json_encode($response);

	}

}