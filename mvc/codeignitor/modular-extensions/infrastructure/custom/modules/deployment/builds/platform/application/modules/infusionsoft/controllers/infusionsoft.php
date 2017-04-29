\<?php

class Infusionsoft extends MX_Controller {
	
	protected $_response = array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);

	function __construct()
	{
		parent::__construct();

		// load whois library
		$this->load->library('infusionsofts');
	}

	public function index()
	{

		// set data variable
		$data['output']	= $this->_response;
		
		// output json
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'], $data);
	}

	public function sale()
	{
		// initialize variables
		$key 	= $this->input->post('key');
		

		// error handling
		if ( ! $domain)
			return $this->api->error_handling($this,$this->lang->line('required_domain').$this->error->code($this, __DIR__,__LINE__));
		
		// set response
		$this->_response	= $this->whoiss->lookup($domain);

		// show response
		return $this->index();
	}

}