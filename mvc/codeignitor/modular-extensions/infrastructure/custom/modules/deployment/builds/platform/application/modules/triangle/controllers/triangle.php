<?php

class Triangle extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		// load triangle library
		$this->load->library('triangles');
	}

	public function index()
	{
		// set data variable
		$data['output']	= $this->_response;
		
		// output json
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'], $data);
	}

	public function prospect()
	{
		// intitialize variables
		$data 		= array(
			'product_type_id'			=> $this->input->post('product_type_id'),
			'product_type_id_specified'	=> $this->input->post('product_type_id_specified'),
			'first'						=> $this->input->post('first'),
			'last'						=> $this->input->post('last'),
			'address'					=> $this->input->post('address'),
			'city'						=> $this->input->post('city'),
			'state'						=> $this->input->post('state'),
			'zip'						=> $this->input->post('zip'),
			'country'					=> $this->input->post('country'),
			'phone'						=> $this->input->post('phone'),
			'email'						=> $this->input->post('email'),
			'affiliate_id'				=> $this->input->post('affiliate_id'),
			'sub_affiliate_id'			=> $this->input->post('sub_affiliate_id'),
			'internal_id'				=> $this->input->post('internal_id'),
			'ip'						=> $this->input->post('ip'),
			'custom_field_1'			=> $this->input->post('custom_field_1'),
			'custom_field_2'			=> $this->input->post('custom_field_2'),
			'custom_field_3'			=> $this->input->post('custom_field_3'),
			'custom_field_4'			=> $this->input->post('custom_field_4'),
			'custom_field_5'			=> $this->input->post('custom_field_5'),
		);

		// load triangle library parameters
		$params 	= array(	// this is the params array to pass to library
			'application'	=> 'elevatedigital'//$this->input->post('application')
		);

		// load triangle library (with params)
		$this->load->library('triangles',$params);

		// charge
		$prospect 	= $this->triangles->prospect($data);

		// add application to data
		$data['application']	= $this->input->post('application');

		// if successful, store in internal database
		//@$this->internal->triangle($data);

		// set response
		$this->_response	= ($prospect['State'] == 'Success')
			? $this->api->response(TRUE,$prospect['ReturnValue']['ProspectID'])
			: $this->api->response(FALSE,$prospect['ErrorMessage']);

		// return
		return $this->index();
	}

	public function subscription()
	{

	}

	public function charge()
	{
		// intitialize variables
		$data 		= $this->input->post();
		/*
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
		*/

		// load triangle library parameters
		$params 	= array(	// this is the params array to pass to library
			'application'	=> 'elevatedigital'//$this->input->post('application')
		);

		// load triangle library (with params)
		$this->load->library('triangles',$params);

		// charge
		$charge 	= $this->triangles->charge($data);

		// set response
		$this->_response	= ($charge['State'] == 'Success')
			? $this->api->response(TRUE,$charge)
			: $this->api->response(FALSE,$charge['ErrorMessage']);

		// return
		return $this->index();
	}
}