<?php

class Prospect extends MX_Controller
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

	public function add()
	{
		// intitialize variables
		$data 		= array(
			'first'			=> $this->input->post('first'),
			'last'			=> $this->input->post('last'),
			'address'		=> $this->input->post('address'),
			'city'			=> $this->input->post('city'),
			'state'			=> $this->input->post('state'),
			'zip'			=> $this->input->post('zip'),
			'country'		=> $this->input->post('country'),
			'phone'			=> $this->input->post('phone'),
			'email'			=> $this->input->post('email'),
			'affiliate_id'	=> $this->input->post('affiliate_id'),
			'subid'			=> $this->input->post('subid'),
			'ip'			=> $this->input->post('ip'),
			'campaign_id'	=> $this->input->post('campaign_id')	// grab this from application?
		);

		// initialize library variables
		$params 	= array(	// this is the params array to pass to library
			'application'	=> $this->input->post('application')
		);

		// load limelight library (with params)
		$this->load->library('limelight',$params);

		// add new prospect
		$prospect_id 		= $this->limelight->add_prospect($data);

		// add application to data
		$data['application']	= $this->input->post('application');

		// if successful, store in internal database
		@$this->internal->limelight($data);

		// set response
		$this->_response	= (is_int($prospect_id))
			? $this->api->response(TRUE,$prospect_id)
			: $this->api->response(FALSE,$prospect_id);

		// return
		return $this->index();
	}

	public function get()
	{
		// initialize variables
		$prospect_id 		= $this->input->post('prospect_id');

		// initialize library variables
		$params 	= array(	// this is the params array to pass to library
			'application'	=> 'buyersupportcenter'//$this->input->post('application')
		);

		// load limelight library (with params)
		$this->load->library('limelight',$params);

		// get prospect info
		$get 		= $this->limelight->get_prospect_info($prospect_id);

		// set response
		$this->_response 	= (is_array($get) AND ! empty($get))
			? $this->api->response(TRUE,$get)
			: $this->api->response(FALSE,$get);

		// return
		return $this->index();
	}
}