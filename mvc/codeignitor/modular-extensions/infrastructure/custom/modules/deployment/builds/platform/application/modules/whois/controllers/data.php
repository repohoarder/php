<?php

class Data extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		// load scrape library
		$this->load->library('scrape');
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
		// initialize variable
		$tld 		= $this->input->post('tld');
		$folder 	= $this->input->post('folder');
		$delete 	= ($this->input->post('delete'))? $this->input->post('delete'): FALSE;

		// get this TLD's file
		$this->scrape->get($tld,$folder,$delete);
	}

	public function create($days=1)
	{
		// initialize variables
		$start 	= date('Y-m-d H:i:s');

		// create scrape
		$this->scrape->create($days);

		// set end
		$end 	= date('Y-m-d H:i:s');

		// set response
		$this->_response 	= $this->api->response(TRUE,array('start' => $start, 'end' => $end));

		// return 
		return $this->index();
	}
}