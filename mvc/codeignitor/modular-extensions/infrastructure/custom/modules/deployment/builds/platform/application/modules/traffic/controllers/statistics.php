<?php

class Statistics extends MX_Controller
{
	/**
	 * The return value of the API
	 * 
	 * @var array
	 */
	var $_response 	= array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);
	
	/**
	 * The API Output Type
	 * 
	 * @var string
	 */
	var $_api_output_type	= 'json';
	
    function __construct() 
    {
        parent::__construct();
        
        // load the model(s)
        $this->load->library('traffic/remote');
        $this->load->library('traffic/stats');
	}
	
	/**
	 * Index
	 * 
	 * This method returns the output as json
	 * 
	 * @access	public
	 * 
	 * @example	index() 
	 * 
	 * @return	json
	 */
	public function index(){
		
		// set data variable
		$data['output']	= $this->_response;
		
		// output
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'],$data);
	}

	public function display()
	{
		// initialize variables
		$campaign_id 	= $this->input->post('campaign_id');
		$password 		= $this->input->post('password');

		// grab stats
		$stats  		= $this->stats->retrieve($campaign_id,$password);

		// set response 
		$this->_response	= $stats;

		return $this->index();
	}

}