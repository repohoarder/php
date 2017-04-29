<?php

class Hosting_check extends MX_Controller {
		
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
		return $this->check_for_hosting();

	}


	function skip_cpanel_cron()
	{

		$resp = $this->platform->post(
			'ubersmith/order_queue/push_stuck_domain_only_orders'
		);

		$output = json_encode($resp);

		if ( ! $resp['success']):

			@mail('travis.loudin@brainhost.com','error skipping domain only cpanel',$output);

		endif;

		echo $output;

	}

	function check_for_hosting()
	{

		if (count($this->_response['errors'])):

			return $this->index();

		endif;

		$resp = $this->platform->post(
			'ubersmith/order/get_hosting_pack',
			array(
				'order_id' => $this->_order_id
			)
		);

		if ($resp['success'] && $resp['data']['hosting_pack']):

			$this->_response   = array(
				'success' => 1,
				'errors'  => array(),
				'data'    => array(
					'has_hosting' => TRUE,
					'pack_id'     => $resp['data']['hosting_pack']['packid']
				)
			);

			return $this->index();

		endif;

		$this->_response = array(
			'success' => 0,
			'errors'  => array('Order does not have hosting. Please skip cPanel steps.'),
			'data'    => array()
		);

		return $this->index();

	}

		/*

		// skip cpanel
		 
		$queue_id = $this->_order_info['order_queue_id'];

		if ( ! $queue_id):

			$this->_response   = array(
				'success' => 0,
				'errors'  => array('Unable to retrieve order queue ID'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$data = array();

		$resp = $this->platform->post(
			'ubersmith/order_queue/get_order_queue_type',
			array(
				'queue_id' => $queue_id
			)
		);

		if ( ! $resp['success']):

			$this->_response   = array(
				'success' => 0,
				'errors'  => array('Unable to retrieve order queue type'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$queue_type = $resp['data']['type'];

		if ($queue_type !== 'domain'):

			$this->_response   = array(
				'success' => 0,
				'errors'  => array('Order queue type is not domain only'),
				'data'    => array()
			);

			return $this->index();

		endif;


		$resp = $this->platform->post(
			'ubersmith/order/update/'.$this->_order_id,
			array(
				'order_id' => $this->_order_id,
				'step_id'  => 43,
			)
		);


		if ( ! $resp['success']):

			$this->_response   = array(
				'success' => 0,
				'errors'  => array('Couldn\'t update the order'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$this->_response   = array(
			'success' => 1,
			'errors'  => array('cPanel junk skipped'),			
			# 'success' => 0,
			# 'errors'  => array(json_encode($data)),
			'data'    => $data
		);

		return $this->index();
		*/

		/*
		$params = array(
			'order_action_slug' => 'provision_cpanel',
			'order_id'          => $this->_order_id,
			'skip'              => 1,
			'type'              => $queue_type
		);

		$resp = $this->platform->post(
			'ubersmith/order/process/'.implode('/',$params)
		);

		$data['provision_cpanel'] = array(
			'params'   => 'ubersmith/order/process/'.implode('/',$params),
			'response' => $resp
		);
	
		$params = array(
			'order_action_slug' => 'fix_cpanel_meta',
			'order_id'          => $this->_order_id,
			'skip'              => 0,
			'type'              => $queue_type
		);

		$resp = $this->platform->post(
			'ubersmith/order/process/'.implode('/',$params)
		);


		$data['fix_cpanel_meta'] = array(
			'params'   => 'ubersmith/order/process/'.implode('/',$params),
			'response' => $resp
		);
		*/


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