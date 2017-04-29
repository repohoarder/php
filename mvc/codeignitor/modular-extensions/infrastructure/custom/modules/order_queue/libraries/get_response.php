<?php

/**
* GetResponse email API functions
* Original by Matt Thompson
 * 
 * Modified on 12/27/2012  by Jamie Rohr
 * Changelog  --  Added get response jsonRPCClient library
*
*/
class Get_response {
	
	var $CI;
	var $vars;
	var $key;
	var $lists;
	var $_url;
	
	function __construct()
	{
		$this->_url	= 'http://api2.getresponse.com';
		
		// get CI instance
		$this->CI 		= &get_instance();
                
        //include the getresponse class
        require("rpcclient/jsonRPCClient.php");
	}	
	
	/*
	 * add a new getresponse contact
	 *
	 * @method add_contact() 
	 * 
	 * @param string $name - name of new contact
	 * @param string $email - email of new contact
	 * @param string $list - list to insert new contact into (names found in config/getresponse.php)
	 * @param array	$meta - array of custom fields to add - array(name => value)
	 * 
	 * @return array $response - from getresponse
	 * 
	 * @example add_contact('Matt', 'matt.thompson@freewebsite.com', 'clients', array('customfield1' => 'value1', 'customfield2' => 'value2'));
	 */
	public function add_contact($params)
	{
		$name		= $params['name'];
		$email		= $params['email'];
		$list		= $params['list'];
		$api_key	= $params['api_key'];
		$meta		= $params['meta'];
		
		$this->key = $api_key;
		// make sure this lists exists in our config
		if ( empty($list))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_list_name').$this->CI->error->code($this->CI, __DIR__,__LINE__));
			
		// make sure valid email
		if ( ! preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email').$this->CI->error->code($this->CI, __DIR__,__LINE__));		
		
		// add this domain to meta (saying where this email was added from)
		$meta['added_by']		= 	$_SERVER['HTTP_HOST'];
			
		// get campaign name from config
		$campaign_name = $list;
		
		// get campaign id from name
		$campaign_id = $this->_get_campaign_id($campaign_name);

		// see if we got an invalid campaign name
		if ( ! $campaign_id)
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_list_name').$this->CI->error->code($this->CI, __DIR__,__LINE__));
		
		// set meta to proper format
		$meta	= $this->_create_custom_meta_array($meta);

		// create array to submit to getresponse
		$data	= array(
			'method'	=> 'add_contact',
			'params'	=> array(
					'campaign'	=> $campaign_id,
					'action'	=> 'standard',
					'name'		=> $name,
					'email'		=> $email,
					'cycle_day'	=> 0,
					'ip'		=> $_SERVER['REMOTE_ADDR'],
					'customs'	=> $meta
			)
		);
		
		// grab response
		$response = $this->_call_resource($data);
		
		return $response;
	}
	
	
	
	
	/*
	 * get contact id for a getresponse contact
	 * @method _get_contact_id() 
	 * @author John Thompson
	 * 
	 * @param string $email - email of new contact
	 * 
	 * @return int $contact id
	 * 
	 * @example _get_contact_id('matt.thompson@freewebsite.com');
	 */
	private function _get_contact_id($email)
	{
			
		// make sure valid email
		if ( ! preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email').$this->CI->error->code($this->CI, __DIR__,__LINE__));	
		
		// initialize variables
		$contact_id = 0;
		
		// set data to pass
		$data	= array(
			'method'	=> 'get_contacts',
			'params'	=> array(
					'email'	=> array('EQUALS'	=> $email)
			)
		);
		
		// make the api call
		$response = $this->_call_resource($data);
		
		// make sure we got a successful response
		if ($response['success'] === FALSE)
			return FALSE;
		
		// grab the campaign id
                $contact_id = array_pop(array_keys($response['data']));
		
		return $contact_id;
	}
	
	/*
	 * get campaign id for a getresponse list
	 * @method _get_campaign_id() 
	 * @author John Thompson
	 * 
	 * @param string $campaign_name - name of a getresponse list
	 * 
	 * @return int $campaign id
	 * 
	 * @example _get_campaign_id('clients');
	 */
	private function _get_campaign_id($campaign_name)
	{
		
		// initialize variables
		$campaign_id = 0;
		
		# initialize JSON-RPC client
                $url = $this->_url;
                $client = new jsonRPCClient($url);

                # find campaign named 'test'
                $campaigns = $client->get_campaigns(
                $this->key,
                    array (
                     # find by name literally
                        'name' => array ( 'EQUALS' => $campaign_name )
                )
                );
                
                $campaign_id = array_pop(array_keys($campaigns));
                return $campaign_id;
		
	}
	
	/*
	 * creates array formatted for getresponse custom fields insert from a standard key => value array
	 * @method _create_custom_meta_array() 
	 * @author John Thompson
	 * 
	 * @param array meta meta array to be inserted as custom fields in getresponse
	 * 
	 * @return array formatted getresponse custom field array
	 * 
	 * @example _create_custom_meta_array(array('customfield1' => 'value1', 'customfield2' => 'value2'));
	 */
	private function _create_custom_meta_array($meta=array())
	{
		
		if(empty($meta)) return array();
		
		// initialize variables
		$newmeta = array();
		
		// iterate through meta key and values
		foreach($meta AS $key => $value):
			$newmeta[]	= array(
				'name'		=> $key,
				'content'	=> $value
			);
		endforeach;
		
		return $newmeta;
	}
	
	/*
	 * cUrl's data to getresponse's API
	 * @method _call_resource() 
	 * @author John Thompson
	 * 
	 * @param array data data array to be submitted to getresponse API
	 * 
	 * @return array array('success', 'data');
	 * 
	 * @example _call_resource(array('data' => '1'));
	 */
	private function _call_resource($data = null, $method = "POST")
	{
		
		$url = $this->_url;
		
                // create get response client
                $client = new jsonRPCClient($url);
                // set method
                $method = $data['method'];
               
                $response = $client->$method(
                $this->key,
                   $data['params']
                );
		
		// return error if no response
		if ( ! $response)
			return array(
				'success'	=> FALSE,
				'error'		=> $code
			);
		
		
		// return success data
		return array(
			'success'	=> TRUE,
			'data' 		=> $response,
		);
		
	}
}
