<?php

class Offers extends CI_Model 
{
	/**
	 * Offer Global Variables
	 * 
	 * @var array
	 */
	var $_offer;
	
    function __construct() 
    {
        parent::__construct();
        
		// set ubersmith variables (from variables loaded into ci vars)
		$this->_offer		= $this->config->item($this->load->_ci_cached_vars['domain']);
        
        // set the database object (from cached global vars)
        $this->load->database($this->_offer['database']);
	}

	public function create($name,$desc,$url)
	{
		// create insert array
		$insert 	= array(
			'name'			=> $name,
			'description'	=> $desc,
			'url'			=> $url
		);

		// insert offer
		$this->db->insert("offers",$insert);

		// return insert id
		return $this->db->insert_id();
	}

	public function get()
	{
		// get offers
		$offers 	= $this->db->get_where("offers",array('active' => 1))->result_array();

		// return offers
		return $offers;
	}

	public function remove($offer_id)
	{
		// deactivate offer
		$this->db->update("offers",array('active' => 0),array('id' => $offer_id));

		// return number of affected rows
		return $this->db->affected_rows();
	}

}