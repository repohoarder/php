<?php

class Merchant extends MX_Controller {
	
	public $_response = array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);

	public function __construct()
	{
		parent::__construct();

		// load model
		$this->load->model('merchants');
	} 

	public function index()
	{
		// set data variable
		$data['output']	= $this->_response;
		
		// output json
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'], $data);
	}

	public function charge()
	{
		// initialize variables
		$lead_id 		= $this->input->post('lead_id');
		$merchant_id 	= $this->input->post('merchant_id');
		$type 			= 'charge';
		$amount 		= $this->input->post('amount');
		$product 		= $this->input->post('product');
		$vars 			= $this->input->post('vars');

		// create array of post variables
		$post 					= $vars;
		$post['lead_id']		= $lead_id;
		$post['merchant_id']	= $merchant_id;
		$post['type']			= $type;
		$post['amount']			= $amount;
		$post['product']		= $product;
		
		// error handling
		if ( ! $merchant_id):

			// show error
			$this->api->error_handling($this,'Please pass a valid merchant id.');
			return;

		endif;

		// grab merchant details
		$merchant 		= $this->merchants->get($merchant_id);

		// if unable to grab merchant, show error
		if ( ! $merchant):

			// show error
			$this->api->error_handling($this,'Please pass a valid merchant id.');
			return;

		endif;

		// grab merchant library
		$library 		= $merchant['library'];

		// load needed library
		$this->load->library('merchants/'.$library);

		// charge
		$charge 		= $this->$library->charge($merchant,$post);

		// add response to transaction table

		// set response
		$this->_response 	= ($charge)
			? $this->api->response(TRUE,$charge)
			: $this->api->response(FALSE,FALSE);

		// show response
		return $this->index();
	}

	public function rebill()
	{
		// initialize variables
		$lead_id 		= $this->input->post('lead_id');
		$merchant_id 	= $this->input->post('merchant_id');
		$type 			= 'charge';
		$amount 		= $this->input->post('amount');
		$product 		= $this->input->post('product');
	}

	public function refund()
	{
		// initialize variables
		$lead_id 		= $this->input->post('lead_id');
		$merchant_id 	= $this->input->post('merchant_id');
		$type 			= 'refund';
		$amount 		= $this->input->post('amount');
		$product 		= $this->input->post('product');
	}
}