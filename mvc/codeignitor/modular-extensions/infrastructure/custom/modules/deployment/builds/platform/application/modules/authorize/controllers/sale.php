<?php

class Sale extends MX_Controller {
	
	public $_response = array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);

	public function __construct()
	{
		parent::__construct();

		// load library
		$this->load->library('authorizes');
	}

	public function index()
	{
		// set data variable
		$data['output']	= $this->_response;
		
		// output json
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'], $data);
	}

	/**
	 * This method charges a credit card
	 *
	 *		"x_card_num"		=> $vars['credit_card'],		// Credit Card Number
	 *		"x_exp_date"		=> $vars['credit_card_exp'],	// format MMYY
	 *		"x_amount"			=> $vars['amount'],
	 *		"x_description"		=> $vars['description'],
     *
	 *		// set billing details
	 *		"x_first_name"		=> $vars['first'],
	 *		"x_last_name"		=> $vars['last'],
	 *		"x_address"			=> $vars['address'],
	 *		"X_city"			=> $vars['city'],
	 *		"x_state"			=> $vars['state'],
	 *		"x_zip"				=> $vars['zip'],
	 *		"x_country"			=> $vars['country'],
     *
	 *		// set customer details
	 *		"x_phone"			=> $vars['phone'],
	 *		"x_email"			=> $vars['email'],
	 *		"x_customer_ip"		=> $vars['ip'],
	 * 
	 * @return [type] [description]
	 */
	public function charge()
	{
		// initialize variables
		
		// error handling
		
		// charge card
		$charge 	= $this->authorizes->charge($this->input->post());

		// set response
		$this->_response 	= ($charge['response_code'])
			? $this->api->response(TRUE,$charge)
			: $this->api->response(FALSE,$charge);

		// return response
		return $this->index();
	}

}