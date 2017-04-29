<?php

class Mailgun extends MX_Controller
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

	
}