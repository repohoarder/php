<?php

class Sitebuilder extends MX_Controller {
		
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

		return $this->add_build_ticket();

	}


	function add_build_ticket()
	{

		$client_id = $this->_order_info['client_id'];		
		$domains   = $this->_order_info['info']['domains'];
		$core      = array_shift($domains);

		$order_id  = $this->_order_id;

		$added     = array();

		for ($i = 0; $i <= 30; $i++):

			if ( ! isset($this->_order_info['info']['pack'.$i])):

				if ($i < 2):

					continue;

				endif;

				break;

			endif;

			$pack = $this->_order_info['info']['pack'.$i];

			if ( ! isset($pack['website_build_version_id']) || ! isset($pack['domain'])):

				continue;

			endif;

			if ( ! $pack['website_build_version_id'] || ! $pack['domain']):

				continue;

			endif;

			$added[] = $pack['domain'];

			$params = array(
				'client_id'        			=> $client_id,
				'build_version_id' 			=> $pack['website_build_version_id'],
				'domain'           			=> $pack['domain']
			);

			$resp = $this->platform->post(
				'builder/queue/insert',
				$params
			);

			if ( ! $resp['success']):

				$this->_response   = array(
					'success' => 0,
					'errors'  => array('Unable to add ticket. Response: '.json_encode($resp)),
					'data'    => array()
				);

				return $this->index();

			endif;

		endfor;

		$success = 'Step skipped, no domains with website_build_version_id found';

		$count = count($added);

		if ($count):

			$success = $count.' ticket(s) added. ['.implode(', ',$added).']';

		endif;

		$this->_response   = array(
			'success' => 1,
			'errors'  => array(),
			'data'    => array(
				$success
			)
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