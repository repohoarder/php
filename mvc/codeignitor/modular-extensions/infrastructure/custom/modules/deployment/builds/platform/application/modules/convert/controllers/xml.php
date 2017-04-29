<?php

class Xml extends MX_Controller {
	
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

	public function to($type='json')
	{
		// set method 
		$method 	= '_'.$type;

		// grab conversion
		$this->_response 	= $this->$method($this->input->post());

		// show response
		return $this->index();
	}

	private function _json($data=array())
	{
		// initialize variables
		$xml 	= $data['xml'];

		// convert to array
		$json 	= $this->xml->to_array($xml);

		// return json encoded array
		return $this->api->response(TRUE,json_encode($json));
	}

	private function _array($data=array())
	{
		// initialize variables
		$xml 	= $data['xml'];

		// convert to array
		$array 	= $this->xml->to_array($xml);

		// return array
		return $this->api->response(TRUE,$array);
	}

}