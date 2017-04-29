<?php

class Leads
{
	public function __construct()
	{
		$this->CI 	= &get_instance();
		$this->CI->load->model('lead/lead_insert'); // insert data functions
		$this->CI->load->model('lead/validates'); // validate lead id functions
	}

	public function add($post=array())
	{
		// validate all fields
		$insert 		= $this->_validate($post);

		// fill out address info (hash and missing data)
		$insert 		= $this->_address_data($insert);

		// fill out name info (hash and missing data)
		$insert 		= $this->_name_data($insert);

		// this is where we will figure out any other information to add for this lead (social media?)

		// determine if this lead exists (if so grab lead sites verticals id) else create new lead id
		$insert			= $this->_set_lsvid($insert);
				
		// insert the data
		$data 			= $this->CI->lead_insert->insert_lead_data($insert);

		// if we made it here, then everything was successful - return all data inserted (including ID's)
		return $data;
	}


	/**
	 * Validate all 
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	private function _validate($data=array())
	{
		// initialize variables
		$response 	= array();

		// iterate each POST and attempt to validate the field
		foreach ($data AS $field => $value):

			// if value is an array, we need ot run this function recursively to validate all multi-dimensional field values
			if (is_array($value))
				$value	= $this->_validate($value);

			// see if this field has a method in the validation library
			if(method_exists($this->CI->validate,$field))
				$value 	= $this->CI->validate->$field($value);

			// add this field => value to the response array
			$response[$field]	= $value;

		endforeach;

		return $response;
	}

	/**
	 * This method fills out the address information (creates hash and adds any missing data)
	 * @param  array  $insert [description]
	 * @return [type]         [description]
	 */
	private function _address_data($insert=array())
	{

		// initialize variables
		$address1 	= @$insert['address']['address1'];
		$address2 	= @$insert['address']['address2'];

		// if following vars aren't set, then grab them from the IP data
		$city 		= (isset($insert['address']['city']))? $insert['address']['city']: $insert['ip']['city'];
		$state 		= (isset($insert['address']['state']))? $insert['address']['state']: $insert['ip']['region'];
		$zip 		= (isset($insert['address']['zip']))? $insert['address']['zip']: $insert['ip']['postal_code'];
		$country 	= (isset($insert['address']['country']))? $insert['address']['country']: $insert['ip']['country_code'];

		// set data variables
		$insert['address']['address1']		= $address1;
		$insert['address']['address2']		= $address2;
		$insert['address']['city']			= $city;
		$insert['address']['state']			= $state;
		$insert['address']['zip']			= $zip;
		$insert['address']['country']		= $country;

		// create address hash
		$insert['address']['hash']	= $this->_create_hash($address1.' '.$address2.' '.$city.' '.$state.' '.$zip.' '.$country);

		return $insert;
	}

	/**
	 * This method fills out the name information (creates hash and adds any missing data)
	 * @param  array  $insert [description]
	 * @return [type]         [description]
	 */
	private function _name_data($insert=array())
	{
		// if name array isn't set - but first/last/middle is, then we need ot add them to the name array
		if ( ! isset($insert['name']) AND (isset($insert['first']) OR isset($insert['last']) OR isset($insert['middle']))):

			// add first/last/middle to name array
			$insert['name']	= array(
				'first'		=> @$insert['first'],
				'middle'	=> @$insert['middle'],
				'last'		=> @$insert['last']
			);

			// unset first/last/middle
			unset($insert['first']);
			unset($insert['middle']);
			unset($insert['last']);

		endif;

		// attempt to determine gender
		// attempt to determine prefix & suffix
		
		// create name hash
		$insert['name']['hash']	= $this->_create_hash($insert['name']['first'].' '.$insert['name']['middle'].' '.$insert['name']['last']);
		
		return $insert;
	}
	
	private function _create_hash($input) {
		
		return strtolower(preg_replace('/[^\d\w]/', '', $input));
	}
	
	private function _set_lsvid($post) {
		
		
		
		// check to see if site has access
		$site_id = $this->CI->validates->auth_key($post['key']);
		$post['site_id'] = $site_id;
		// return error if site id  not found;
		if( ! $site_id ) :
			$post['error'] = "Site_ID not found.";
			return $post;
			exit();
		endif;
		
		// get vertical id
		$vertical_id = $this->CI->validates->get_vertical_id_by_slug($post);
		$post['vertical_id'] = $vertical_id;
		// return error if 
		if( ! $vertical_id ) :
			$post['error'] = "Vertical_id not found.";
			return $post;
			exit();
		endif;
		
		$site_vertical_id = $this->CI->validates->validate_vertical_id($vertical_id,$site_id);
		// set vertical id
		$post['site_vertical_id'] = $site_vertical_id;
		
		if( ! $site_vertical_id ) :
			$post['error'] = "Site Vertical_id not found.";
			return $post;
			exit();
		endif;
		
		
		###################################################
		#   now that the validation above is done lets get 
		#   a lead id to play with
		###################################################
		
		if(isset($post['lead_id'])) :

			return $this->CI->validates->existing_lead($post);
			exit();
		endif;

		// check to see if lead id exists
		if(isset($post['lead_site_vertical_id'])) :

			return $this->CI->validates->existing_vertical_id($post);
			exit();
			
		endif;
		
		//search for lead
		
		$lead_id = $this->CI->validates->search_for_lead($post);
		
		$post['lead_id'] = $lead_id;
		
		//if no lead create a new one
		if( ! $lead_id ) :
			
			$lead_id = $this->CI->validates->insert();
			$post['lead_id'] = $lead_id;
			
		endif;
		
		// create lead_site_vertical_id
		$post['lead_site_vertical_id'] = $this->CI->validates->insert_leads_sites_verticals($lead_id,$vertical_id);
		
		return $post;
	}
}