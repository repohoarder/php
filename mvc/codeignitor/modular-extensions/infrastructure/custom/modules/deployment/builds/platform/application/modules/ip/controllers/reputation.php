<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reputation extends MX_Controller
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
	
    function __construct() 
    {
        parent::__construct();

		// load validation library
		$this->load->library('reputations');
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
	public function index()
	{
		
		// set data variable
		$data['output']	= $this->_response;
		
		// output
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'],$data);
	}

	public function blacklisted()
	{
		// initialize variables
		$ip 	= $this->input->post('ip');

		// error handling
		if ( ! $ip )
			return $this->api->error_handling($this,$this->lang->line('invalid_ip').$this->error->code($this, __DIR__,__LINE__));

		// see if ip is blacklisted
		$valid 	= $this->reputations->blacklisted($ip);

		// set response
		$this->_response	= (is_array($valid) AND ! empty($valid))
			? $this->api->response(TRUE,$valid)
			: $this->api->response(FALSE,$valid);

		// show response
		return $this->index();
	}
}








