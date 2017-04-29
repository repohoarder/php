<?php

class Leads extends CI_Model 
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

	public function get()
	{
		// initialize variables

		// get lead(s)
		$leads 		= $this->db->select("*")->from("leads")->get()->result_array();

		// iterate all leads and get meta data
		foreach ($leads AS $key => $value):

			// add meta to lead
			$leads[$key]['meta'] 	= $this->get_meta($value['id']);

		endforeach;

		// return leads
		return $leads;
	}

	public function add($vars=array())
	{
		// intiialize variables
		$first		= $vars['first'];
		$last 		= $vars['last'];
		$email		= $vars['email'];
		$source		= $vars['source'];
		$language	= (isset($vars['language']))? $vars['language']: 'english';
		$buyer		= (isset($vars['buyer']))? $vars['buyer']: 0;
		$meta 		= (isset($vars['meta']) AND is_array($vars['meta']))? $vars['meta']: array();
		$offer 		= (isset($vars['offer']))? $vars['offer']: FALSE;
		$ip 		= $vars['ip'];

		// verify this is a valid "source"

		// insert lead
		$sql 		= '
		INSERT IGNORE INTO 
			leads 
		(first, last, email, source_id, language, buyer, ip) 
			VALUES 
		("'.$first.'", "'.$last.'", "'.$email.'", "'.$source.'", "'.$language.'", "'.$buyer.'", "'.$ip.'")
		';
		$this->db->query($sql);

		// grab lead id
		$lead_id 	= $this->db->insert_id();

		// insert any meta
		if (isset($meta) AND ! empty($meta))
			$this->add_meta($lead_id,$meta);

		// insert offer
		if ($offer AND ! empty($offer))
			$this->add_offer($lead_id,$offer);

		// return lead id
		return $lead_id;
	}

	public function add_meta($lead_id,$meta=array())
	{
		// iterate meta
		foreach ($meta AS $key => $value):

			// intiailzie variables
			$field 	= $key;
			$value 	= $value;

			// insert meta
			$this->db->insert("leads_meta",array('lead_id' => $lead_id, 'field' => $field, 'value' => $value));

		endforeach;

		return;
	}

	public function get_meta($lead_id)
	{
		// intiailize variables
		$formatted 	= array();

		// get lead meta
		$meta 		= $this->db->get_where("leads_meta",array('lead_id' => $lead_id))->result_array();

		// iterate meta and format into array
		foreach ($meta AS $key => $value):

			// format meta
			$formatted[$value['field']]	= $value['value'];

		endforeach;

		// return meta
		return $formatted;
	}


	public function add_offer($lead_id,$offer_id)
	{
		// insert offer
		$this->db->insert("leads_offers",array('lead_id' => $lead_id, 'offer_id' => $offer_id));

		return;
	}

}