<?php

class Merchants extends CI_Model 
{
	var $_lead;
	
    function __construct() 
    {
        parent::__construct();
        
		// set ubersmith variables (from variables loaded into ci vars)
		$this->_lead		= $this->config->item($this->load->_ci_cached_vars['domain']);
        
        // set the database object (from cached global vars)
        $this->load->database($this->_lead['database']);
	}

	public function get($merchant_id)
	{
		// get merchant information
		$merchant 	= $this->db->select("*")->from('merchant')->get()->result_array();

		// return merchant info
		return (isset($merchant[0]) AND ! empty($merchant[0]))? $merchant[0]: FALSE;
	}
}