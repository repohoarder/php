<?php

class Product extends MX_Controller
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

	public function get()
	{
		// initialize variables
		$product_id 	= $this->input->post('product_id');

		// initialize library variables
		$params 	= array(	// this is the params array to pass to library
			'application'	=> $this->input->post('application')
		);

		// load limelight library (with params)
		$this->load->library('limelight',$params);

		// get product 
		$product 	= $this->limelight->get_product($product_id);

		// set response
		$this->_response 	= (is_array($product) AND $product['response_code'] == 100)
			? $this->api->response(TRUE,$product)
			: $this->api->response(FALSE,$product);

		// return
		return $this->index();
	}
}