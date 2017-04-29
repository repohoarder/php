<?php

class Domain extends MX_Controller {
	
	protected $_response = array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);

	function __construct()
	{
		parent::__construct();

		// load whois library
		$this->load->library('whoiss');
	}

	public function index()
	{

		// set data variable
		$data['output']	= $this->_response;
		
		// output json
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'], $data);
	}

	public function lookup($domain=FALSE)
	{
		// error handling
		if ( ! $domain)
			return $this->api->error_handling($this,$this->lang->line('required_domain').$this->error->code($this, __DIR__,__LINE__));
		
		// set response
		$this->_response	= $this->whoiss->lookup($domain);

		// show response
		return $this->index();
	}

	public function email($domain=FALSE)
	{
		// error handling
		if ( ! $domain)
			return $this->api->error_handling($this,$this->lang->line('required_domain').$this->error->code($this, __DIR__,__LINE__));
		
		// set response
		$this->_response	= $this->whoiss->email($domain);

		// show response
		return $this->index();	
	}
}