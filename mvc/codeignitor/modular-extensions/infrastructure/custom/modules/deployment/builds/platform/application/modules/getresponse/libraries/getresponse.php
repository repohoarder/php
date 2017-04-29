<?php

/**
* GetResponse email API functions
* Original by Matt Thompson
 * 
 * Modified on 12/27/2012  by Jamie Rohr
 * Changelog  --  Added get response jsonRPCClient library
*
*/
class Getresponse {
	
	var $CI;
	var $vars;
	var $key;
	var $lists;
	
	function __construct()
	{
		// get CI instance
		$this->CI 	 = &get_instance();
		
		// load getresponse config
		$this->CI->load->config("getresponse");
		
		// grab config items
		$this->vars	 = $this->CI->config->item($this->CI->load->_ci_cached_vars['domain']);	// loads the proper config vars from global variable set

		// set api key
		$this->key	 = $this->vars['api_key'];
		
		// grab lists
		$this->lists = $this->vars['lists'];
                
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
	public function add_contact($name,$email,$list,$meta = array(),$ip=FALSE)
	{
		// make sure this lists exists in our config
		if ( ! isset($this->lists[$list]) OR empty($this->lists[$list]))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_list_name').$this->CI->error->code($this->CI, __DIR__,__LINE__));
			
		// make sure valid email
		if ( ! preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email').$this->CI->error->code($this->CI, __DIR__,__LINE__));		
		
		// add this domain to meta (saying where this email was added from)
		$meta['added_by']		= 	$this->CI->load->_ci_cached_vars['domain'];
			
		// get campaign name from config
		$campaign_name = $this->lists[$list];
		
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
					'ip'		=> (! $ip)? $_SERVER['REMOTE_ADDR']: $ip,
					'customs'	=> $meta
			)
		);
		
		// grab response
		$response = $this->_call_resource($data);
		
		return $response;
	}
	
	/*
	 * update a getresponse contact
	 * @method update_contact() 
	 * @author John Thompson
	 * 
	 * @param string $name - name of contact
	 * @param string $email - email of contact
	 * @param string $list - list contact belongs to (names found in config/getresponse.php)
	 * @param array	$meta - array of custom fields to add - array(name => value)
	 * 
	 * @return array $response - from getresponse
	 * 
	 * @example update_contact('Matt', 'matt.thompson@freewebsite.com', 'clients', array('customfield1' => 'new_value1', 'customfield2' => 'new_value2'));
	 */
	public function update_contact($name,$email,$list,$meta = array())
	{
		
		// make sure this lists exists in our config
		if ( ! isset($this->lists[$list]) OR empty($this->lists[$list]))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_list_name').$this->CI->error->code($this->CI, __DIR__,__LINE__));
			
		// make sure valid email
		if ( ! preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email').$this->CI->error->code($this->CI, __DIR__,__LINE__));	
		
		// add this domain to meta (saying where this email was updated from)
		$meta['last_updated_by']		= 	$this->CI->load->_ci_cached_vars['domain'];
			
		// get campaign name from config
		$campaign_name = $this->lists[$list];
		
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
					'action'	=> 'update',
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
	 * move getresponse contact to a new list
	 * @method move_contact() 
	 * @author John Thompson
	 * 
	 * @param string $email - email of new contact
	 * @param string $list - list to insert new contact into (names found in config/getresponse.php)
	 * 
	 * @return array $response - from getresponse
	 * 
	 * @example move_contact('matt.thompson@freewebsite.com', 'partials');
	 */
	public function move_contact($email,$list)
	{
		
		// make sure this lists exists in our config
		if ( ! isset($this->lists[$list]) OR empty($this->lists[$list]))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_list_name').$this->CI->error->code($this->CI, __DIR__,__LINE__));
			
		// make sure valid email
		if ( ! preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email').$this->CI->error->code($this->CI, __DIR__,__LINE__));	
		
		// get contact id
		$contact_id		= $this->_get_contact_id($email);
		
		// get campaign name from config
		$campaign_name = $this->lists[$list];
		
		// get campaign id
		$campaign_id	= $this->_get_campaign_id($campaign_name);
		
		// see if we got an invalid campaign name
		if ( ! $campaign_id)
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_list_name').$this->CI->error->code($this->CI, __DIR__,__LINE__));
		
		// create array to submit to getresponse
		$data	= array(
			'method'	=> 'move_contact',
			'params'	=> array(
					'contact'	=> $contact_id,
					'campaign'	=> $campaign_id
				
			)
		);
		
		// grab response
		$response = $this->_call_resource($data);
		
		return $response;		
	}
	
	/*
	 * delete a getresponse contact
	 * @method delete_contact() 
	 * @author John Thompson
	 * 
	 * @param string $email - email of new contact
	 * 
	 * @return array $response - from getresponse
	 * 
	 * @example delete_contact('matt.thompson@freewebsite.com');
	 */
	public function delete_contact($email)
	{
			
		// make sure valid email
		if ( ! preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email').$this->CI->error->code($this->CI, __DIR__,__LINE__));	
		
		// get contact id
		$contact_id		= $this->_get_contact_id($email);
		
		// create array to submit to getresponse
		$data	= array(
			'method'	=> 'delete_contact',
			'params'	=> array(
					'contact'	=> $contact_id
				
			)
		);
		
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
	public function _get_campaign_id($campaign_name,$return=true)
	{
		
		if($return) :
			return $campaign_name;
			exit();
		endif;
		// initialize variables
		$campaign_id = 0;
		
		# initialize JSON-RPC client
                $url = $this->vars['url'];
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
			if(empty($value)) :
				$value= 'not specified';
			endif;
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
		
		$code='';
		$url = $this->vars['url'];
		
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
	public function parse_campaigns($listname=false){
		
		if(! $listname ) :
			foreach($this->lists as $k=>$v) :
				echo $k . "=" .$this->_get_campaign_id($v,false)."<br>";
			endforeach;
		else:
			echo $listname . "=" .$this->_get_campaign_id($listname,false)."<br>";
		endif;
	}
}