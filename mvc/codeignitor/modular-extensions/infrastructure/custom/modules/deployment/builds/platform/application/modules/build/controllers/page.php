<?php

class Page extends MX_Controller {
	
	protected $_response = array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{

		// set data variable
		$data['output']	= $this->_response;
		
		// output json
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'], $data);

	}

}