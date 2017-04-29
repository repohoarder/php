<?php

class Locate extends MX_Controller {
	
	protected $_response = array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);

	function __construct()
	{
		parent::__construct();

		$this->load->library('geo');
		$this->load->config('countries');
	}

	function index()
	{

		// set data variable
		$data['output']	= $this->_response;
		
		// output json
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'], $data);

	}

	function to_country() {

		$ip = trim($this->input->post('ip_address'));

		if ( ! $ip):

			$this->_response = array(
				'success' 	=> 0,
				'error'		=> array('No IP address specified'),
				'data'		=> array()
			);

			return $this->index();

		endif;

		if ( ! filter_var($ip, FILTER_VALIDATE_IP)):

			$this->_response = array(
				'success' 	=> 0,
				'error'		=> array('Invalid IP specified'),
				'data'		=> array()
			);

			return $this->index();

		endif;

		$country = $this->geo->ip_to_country_code($ip);

		if ( ! $country):

			$this->_response = array(
				'success' 	=> 0,
				'error'		=> array('Unable to convert IP to country'),
				'data'		=> array()
			);

			return $this->index();

		endif;

		$countries = $this->config->item('countries_all');
		$name      = $countries[$country];		
		$banned    = array_key_exists($country, $this->config->item('countries_banned'));

		$this->_response = array(
			'success' 	=> 1,
			'error'		=> array(),
			'data'		=> array(
				'ip_address'   => $ip,
				'country_code' => $country,
				'country_name' => $name,
				'banned'       => $banned
			)
		);
		
		return $this->index();

	}


	function get_record() {

		$ip = trim($this->input->post('ip_address'));

		if ( ! $ip):

			$this->_response = array(
				'success' 	=> 0,
				'error'		=> array('No IP address specified'),
				'data'		=> array()
			);

			return $this->index();

		endif;

		if ( ! filter_var($ip, FILTER_VALIDATE_IP)):

			$this->_response = array(
				'success' 	=> 0,
				'error'		=> array('Invalid IP specified'),
				'data'		=> array()
			);

			return $this->index();

		endif;

		$obj    = $this->geo->get_record($ip);
		$record = get_object_vars($obj);

		if ( ! $record || ! is_array($record) || ! isset($record['country_code'])):

			$this->_response = array(
				'success' 	=> 0,
				'error'		=> array('Unable to convert IP'),
				'data'		=> array()
			);

			return $this->index();

		endif;

		$countries = $this->config->item('countries_all');
		$banned    = array_key_exists($record['country_code'], $this->config->item('countries_banned'));

		$this->_response = array(
			'success' 	=> 1,
			'error'		=> array(),
			'data'		=> array(
				'ip_address' => $ip,
				'banned'     => $banned,
				'record'     => $record,
			)
		);
		
		return $this->index();

	}

}