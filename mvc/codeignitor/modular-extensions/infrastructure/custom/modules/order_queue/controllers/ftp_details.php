<?php

class Ftp_details extends MX_Controller {
		
	private 
		$_order_id   = FALSE,
		$_order_info = FALSE,
		$_response   = array(
			'success' => 0,
			'errors'  => array(),
			'data'    => array()
		);

	function __construct()
	{

		parent::__construct();

		$key = $this->input->post('api_key');
		$this->_verify_key($key);

		$id = $this->input->post('order_id');
		$this->_set_vars($id);

	}

	function _verify_key($key)
	{

		if ($key !== 'CE2_stUswutrAbTrawrE9A86teD'):

			$this->_response['success']  = FALSE;
			$this->_response['errors'][] = 'Invalid API Key';
			return;

		endif;
	}

	function index()
	{

		$data['response'] = $this->_response;
		$this->load->view('json', $data);
		return;

	}

	function test($order_id)
	{

		$this->_response['errors'] = array();
		$this->_set_vars($order_id);

		return $this->update_credentials();

	}

	function update_credentials()
	{

		if (count($this->_response['errors'])):

			return $this->index();

		endif;

		if ( ! $this->_order_info['client_id']):

			$this->_response = array(
				'success' => 0,
				'errors'  => array('Unable to retrieve client ID'),
				'data'    => array()
			);

			return $this->index();

		endif;



		$client_info  = $this->_get_client_info($this->_order_info['client_id']);

		if ( ! $client_info):

			$this->_response = array(
				'success' => 0,
				'errors'  => array('Unable to retrieve client info'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$meta         = $client_info['metadata'];
		$partner_info = $this->_get_partner_info($meta['partner_id']);

		if ( ! $partner_info):

			$this->_response = array(
				'success' => 0,
				'errors'  => array('Unable to retrieve partner info'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$website_id = $partner_info['website'][0]['id'];
		
		$user       = $meta['global_username'];
		$pass       = $meta['global_password'];
		$host       = $meta['core_domain_name'];

		if ($meta['install_server_ip']):

			$host = $meta['install_server_ip'];

		endif;

		if ($meta['install_server_name']):

			$host = $meta['install_server_name'];

		endif;

		$resp = $this->platform->post(
			'partner/website/update_ftp_credentials',
			array(
				'website_id' => $website_id,
				'host'       => $host,
				'user'       => $user,
				'pass'       => $pass
			)
		);

		if ( ! $resp['success']):

			$this->_response = array(
				'success' => 0,
				'errors'  => array('Unable to update FTP info'),
				'data'    => array()
			);

			return $this->index();

		endif;


		$this->_response = array(
			'success' => 1,
			'errors'  => array(),
			'data'    => array('FTP credentials updated')
		);

		return $this->index();

	}


	function _get_partner_info($partner_id)
	{
		$resp = $this->platform->post(
			'partner/account/details',
			array(
				'partner_id' => $partner_id
			)
		);

		if ( ! $resp['success']):

			return FALSE;

		endif;

		return array_shift($resp['data']);
	}

	function _get_client_info($client_id)
	{
		$resp = $this->platform->post(
			'ubersmith/client/get',
			array(
				'name'      => 'id',
				'client_id' => $client_id
			)
		);

		if ( ! $resp['success']):

			return FALSE;

		endif;

		return $resp['data'];
	}

	function _set_vars($id)
	{

		if ($id):

			$this->_order_id = $id;

		endif;

		if ( ! $this->_order_id):

			$this->_response['success']  = FALSE;
			$this->_response['errors'][] = 'Invalid Order ID';
			return;

		endif;

		$o_info = $this->platform->post(
			'ubersmith/order/get',
			array(
				'order_id' => $this->_order_id
			)
		);

		if ( ! $o_info['success']):

			$this->_response['success']  = FALSE;
			$this->_response['errors'][] = 'Unable to retrieve order info';
			return;

		endif;

		$this->_order_info = $o_info['data'];

	}


	/*
		$client_id = $this->_partner_info['uber_client_id'];	
		
		$this->_client_info = $this->_get_client_info($client_id);

		if ( ! $this->_client_info || ! isset($this->_client_info['metadata']['core_domain_name'])):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Unable to get partner client info'),
				'data'    => array() 
			);

			return FALSE;

		endif;

		$this->_ftp_creds = array(
			'host' => $this->_client_info['metadata']['core_domain_name'],
			'user' => $this->_client_info['metadata']['global_username'],
			'pass' => $this->_client_info['metadata']['global_password'],
		);
		*/

}