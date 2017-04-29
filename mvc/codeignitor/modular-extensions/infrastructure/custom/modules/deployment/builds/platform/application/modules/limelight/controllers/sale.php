<?php

class Sale extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
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
		$data 		= array(
			'campaign_id'			=> $this->input->post('campaign_id'),
			'prospect_id'			=> $this->input->post('prospect_id'),
			'product_id'			=> $this->input->post('product_id'),
			'shipping_id'			=> $this->input->post('shipping_id'),
			'card_type'				=> $this->input->post('card_type'),
			'credit_card_number'	=> $this->input->post('credit_card_number'),
			'exp_month'				=> $this->input->post('exp_month'),
			'exp_year'				=> $this->input->post('exp_year'),
			'cvv'					=> $this->input->post('cvv'),
			'gateway_id' 			=> $this->input->post('gateway_id');
		);

		// initialize library variables
		$params 	= array(	// this is the params array to pass to library
			'application'	=> $this->input->post('application')
		);

		// load limelight library (with params)
		$this->load->library('limelight',$params);

		// charge sale
		$charge 	= $this->limelight->add_prospect_sale($data);

		// set response
		$this->_response 	= ((isset($charge['errorFound']) AND ! empty($charge['errorFound'])) OR empty($charge) OR ! empty($charge['errorMessage']))
			? $this->api->response(FALSE,isset($charge['errorMessage'])? $charge['errorMessage']: FALSE)
			: $this->api->response(TRUE,$charge);

		// return
		return $this->index();
	}
}