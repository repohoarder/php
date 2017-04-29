<?php

class Transfer_domains extends MX_Controller {
		
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
		return $this->stop_transfer_domains();

	}

	function stop_transfer_domains()
	{

		if (count($this->_response['errors'])):

			return $this->index();

		endif;

		$core_domain_pack = $this->platform->post(
			'ubersmith/order/get_core_domain_pack',
			array(
				'order_id' => $this->_order_id
			)
		);

		if ( ! is_array($core_domain_pack) || ! $core_domain_pack['success']):

			$this->_response = array(
				'success' => 0,
				'errors'  => array('Unable to get core domain pack'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$type = $core_domain_pack['data']['core_domain_pack']['domain_registration_type'];

		if ($type != 'register'):

			$this->_response = array(
				'success' => 0,
				'errors'  => array('This order contains a transfer domain. Please initiate transfer process and then skip this step.'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$this->_response = array(
			'success' => 1,
			'errors'  => array(),
			'data'    => array('Registration domain')
		);

		return $this->index();

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


}