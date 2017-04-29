<?php

class Lead extends MX_Controller {
	
	public $_response = array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);

	public function __construct()
	{
		parent::__construct();

		// load model
		$this->load->model('leads');
	} 

	public function index()
	{
		// set data variable
		$data['output']	= $this->_response;
		
		// output json
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'], $data);
	}

	public function get()
	{
		// initialize variables

		// get lead(s)
		$leads 		= $this->leads->get();

		// set response
		$this->_response	= (isset($leads) AND is_array($leads))
			? $this->api->response(TRUE,$leads)
			: $this->api->response(FALSE,FALSE);

		// show response
		return $this->index();
	}

	public function add()
	{
		// initialize variables
		$source_id	= $this->input->post('source_id');
		$first		= $this->input->post('first');
		$last		= $this->input->post('last');
		$email		= $this->input->post('email');
		$meta		= $this->input->post('meta');
		$language	= $this->input->post('language');
		$buyer		= $this->input->post('buyer');
		$offer_id	= $this->input->post('offer_id');
		$ip 		= $this->input->post('ip');

		// error handling
		if ( ! $source_id):

			// show error
			$this->api->error_handling($this,'Please pass a valid source id.');
			return;

		endif;

		if ( ! $offer_id):

			// show error
			$this->api->error_handling($this,'Please pass a valid offer id.');
			return;

		endif;

		// create insert array
		$vars 		= array(
			'source'	=> $source_id,
			'first'		=> $first,
			'last' 		=> $last,
			'email' 	=> $email,
			'meta' 		=> $meta,
			'language' 	=> $language,
			'buyer' 	=> $buyer,
			'offer' 	=> $offer_id,
			'ip' 		=> $ip
		);

		// insert lead
		$lead 		= $this->leads->add($vars);

		// set response
		$this->_response 	= (isset($lead) AND is_int($lead))
			? $this->api->response(TRUE,$lead)
			: $this->api->response(FALSE,FALSE);

		// display response
		return $this->index();
	}

}