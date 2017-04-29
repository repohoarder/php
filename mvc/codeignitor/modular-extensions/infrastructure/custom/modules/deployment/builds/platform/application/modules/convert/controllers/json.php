<?php

class Json extends MX_Controller {
	
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

	public function to($type='xml')
	{
		$_POST 	= array(
			'json'	=> json_encode(array(
				'this'	=> 'is',
				'a'		=> 'test',
				'json'	=> array(
					'to'	=> 'xml'
				)
			))
		);

		// set method 
		$method 	= '_'.$type;

		// grab conversion
		$this->_response 	= $this->$method($this->input->post());

		// show response
		return $this->index();
	}

	private function _xml($data=array())
	{
		// initialize variables
		$json 	= $data['json'];

		// convert to XML
		$xml 	= $this->xml->array_to_xml(json_decode($json),new SimpleXMLElement('<root></root>'));

		// return XML
		return $this->api->response(TRUE,$xml);
	}

	private function _array($data=array())
	{
		// initialize variables
		$json 	= $data['json'];

		// return array
		return $this->api->response(TRUE,json_decode($json,TRUE));
	}
}