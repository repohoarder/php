<?php

class Dns extends MX_Controller {
	
	protected $_response = array(
		'success' 	=> FALSE,
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

	public function get($type=FALSE)
	{
		// initialize variables
		$host 	= $this->input->post('host');
		$type 	= ( ! $type)? DNS_ALL: $type;

		// get DNS records
		$dns 	= dns_get_record($host,DNS_ALL,$auth,$addtl);

		// set response
		$this->_response 	= $this->api->response(TRUE,array('result' => $dns, 'auth' => $auth, 'addtl' => $addtl));

		// display response
		return $this->index();
	}

	public function add()
	{
		return $this->index();
	}

	public function edit()
	{
		return $this->index();
	}

	public function remove()
	{
		return $this->index();
	}
}