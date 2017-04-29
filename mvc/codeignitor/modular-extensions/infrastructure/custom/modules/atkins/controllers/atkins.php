<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Atkins extends MX_Controller {


	protected $_response = array(
		'success' => 0,
		'error'   => array(),
		'data'    => array()
	);

	function index()
	{
		echo json_encode($this->_response);
	}

	function api($which) 
	{
		// add_service
		// charge_services
		// get_client
		 
		if ( ! in_array($which, array('add_service', 'charge_services', 'get_client'))):

			$this->_response['error'][] = 'Invalid API';
			return $this->index();

		endif;

		$params = $this->input->get_post(NULL, TRUE);
		$api    = 'ubersmith/atkins/'.$which;

		$this->_response = $this->platform->post($api, $params);

		return $this->index();


	}

}