<?php

class Thrive extends MX_Controller {
		
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

		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
		@mail('travis.loudin@brainhost.com','thrive attempt',json_encode(array('request' => $_REQUEST)));
		exit();
		# vinnie requested all lead passbacks turned off Sept 5, 2013 @ ~1:00PM
		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

		$key = $this->input->post('api_key');
		$this->_verify_key($key);

		$id = $this->input->post('order_id');
		$this->_set_vars($id);

	}

	function _verify_key($key)
	{

		if ($key !== 'sP4Y9chaXevUmA6e'):

			$this->_response['success']  = FALSE;
			$this->_response['errors'][] = 'Invalid API Key';
			return;

		endif;
	}

	function index()
	{

		$data['response'] = $this->_response;


		# @mail('travis.loudin@brainhost.com','thrive response',json_encode($this->_response));

		$this->load->view('json', $data);

		return;

	}

	function test($order_id)
	{

		$this->_response['errors'] = array();
		$this->_set_vars($order_id);
		return $this->add();

	}

	function add()
	{

		$thrive_errors = FALSE;

		if (count($this->_response['errors'])):

			if ( ! $thrive_errors):

				$this->_response['success'] = 1;

			endif;

			return $this->index();

		endif;


		// initialize variables
		$add 	= array();

		// create add array for thrive
		$add 	= array(
			'first_name'	=> $this->_order_info['info']['first'],
			'last_name'		=> $this->_order_info['info']['last'],
			'email'			=> $this->_order_info['info']['email'],
			'city'			=> $this->_order_info['info']['city'],
			'state'			=> $this->_order_info['info']['state'],
			'zip'			=> $this->_order_info['info']['zip'],
			'country'		=> $this->_order_info['info']['country'],
			'phone'			=> $this->_order_info['info']['phone'],
			'domain'	    => $this->_order_info['info']['server'],
			'company'		=> ($this->input->post('company'))? $this->input->post('company'): 'brainhost'
		);

		// if we have data to add, then send off to Thrive
		if (empty($add)):

			$this->_response   = array(
				'success' => ($thrive_errors) ? 0 : 1,
				'errors'  => array('No parameters for Thrive'),
				'data'    => array()
			);

			return $this->index();

		endif;


		$resp = $this->platform->post(
			'thrive/lead/add',
			$add
		);

		$this->_response   = array(
			'success' => ($thrive_errors && ! $resp['success']) ? 0 : 1,
			'errors'  => array(),
			'data'    => array(
				'thrive_response' => $resp
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