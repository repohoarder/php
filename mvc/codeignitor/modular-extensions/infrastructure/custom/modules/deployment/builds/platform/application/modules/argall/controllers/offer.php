<?php

class Offer extends MX_Controller {
	
	public $_response = array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);

	public function __construct()
	{
		parent::__construct();

		// load model
		$this->load->model('offers');
	}

	public function index()
	{
		// set data variable
		$data['output']	= $this->_response;
		
		// output json
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'], $data);
	}

	public function create()
	{
		// initialize variables
		$name			= $this->input->post('name');
		$description	= $this->input->post('description');
		$url			= $this->input->post('url');

		// add offer
		$offer_id 		= $this->offers->create($name,$description,$url);

		// set response
		$this->_response 	= (isset($offer_id) AND is_int($offer_id))
			? $this->api->response(TRUE,$offer_id)
			: $this->api->response(FALSE,FALSE);

		// show response
		return $this->index();
	}

	public function get()
	{
		// get offers
		$offers 	= $this->offers->get();

		// set response
		$this->_response 	= (is_array($offers) AND ! empty($offers))
			? $this->api->response(TRUE,$offers)
			: $this->api->response(FALSE,FALSE);

		// show response
		return $this->index();
	}

	public function remove()
	{
		// initialize variables
		$offer_id 	= $this->input->post('offer_id');

		// deactivate offer 
		$offer 		= $this->offers->remove($offer_id);

		// set response
		$this->_response 	= ($offer)
			? $this->api->response(TRUE,$offer)
			: $this->api->response(FALSE,FALSE);

		// show response
		return $this->index();
	}

}