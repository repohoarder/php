<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auth Library
 * 
 * This class handles API Authorization for The Platform
 * 
 * @version	1.0	August 15,2012
 * 
 * @package Auth
 * 
 * @method json		authenticate()			This method authenticates API POST as well as sets GLOBAL varibles for Platform
 * @method boolean	validate(array $post)	This method validates API POST to make sure proper auth vars were passed & properly formatted
 * 
 */
class Auth {

	/**
	 * The Codeignitor Object
	 */
	var $CI;

	protected $_debug_ips = array(
		'192.64.180.131',
		'99.109.49.118',
		'98.100.69.22',
		'74.218.103.238',
		'70.228.69.110',
		'99.51.212.133'
	);
	
	function __construct() 
	{
		$this->CI =& get_instance();

	}


	/**
	 * Perform Platform API Authentication
	 * 
	 * This method performs authentication of The Platform's API.  It will verify proper credentials have been passed and then set
	 * global variables for The Platform depending on what application is using it. 
	 * 
	 * @access	public
	 * 
	 * @example	authenticate()
	 * 
	 * @return json
	 */
	public function authenticate()
	{
		// if debugging		
		$debug = (strpos($_SERVER['QUERY_STRING'],'debug') !== FALSE && (in_array($_SERVER['REMOTE_ADDR'],$this->_debug_ips) || $_SERVER['SERVER_ADDR'] == '127.0.0.1')) ? TRUE : FALSE;

		// if we are debugging, just return without running auth
		if ( ! $debug):
		
		/*
			// validate the api post
			if ( ! $this->validate($this->CI->input->post())):

				// log failed API call
				$this->_write_log($this->CI->uri->segment_array(),$this->CI->input->post(),FALSE);
	
				// show error
				echo json_encode($this->CI->api->response(FALSE, $this->CI->lang->line('invalid_api_auth')));
				exit;
				
			endif;
		*/

		else:
			
			//$_POST['api_user']	= str_replace('debug=','',$_SERVER['QUERY_STRING']);
			
			$aryQueries=explode('&', $_SERVER['QUERY_STRING']);
			foreach ($aryQueries as $strQuery)
			{
				$key=substr($strQuery, 0, strpos($strQuery, '='));
				$value=substr($strQuery, strpos($strQuery, '=')+1);
				
				$_POST[$key]=urldecode($value);
				
				if ($key=='debug')
				{
					$_POST['api_user']=$value;
				}
			}
			unset($aryQueries, $strQuery, $key, $value);

		endif;	// end debug
	
		// grab api variables for this application
		$config	= $this->CI->config->item($this->CI->input->post('api_user'));

		// set the api output type if not passed (default to json)
		$config['_api_output_type']	= $this->_api_output_type();
		
		// load config variables into GLOBAL variables
		$this->CI->load->vars($config);
		
		// log successful API call
		$this->_write_log($this->CI->uri->segment_array(),$this->CI->input->post(),TRUE);
	}
	
	/**
	 * Validates POST data
	 * 
	 * This method validates the POST data to make sure proper authentication variables were passed and properly formatted 
	 * 
	 * @author	John Thompson	<thompson2091 @ gmail.com>
	 * 
	 * @access	public
	 * 
	 * @example	validate(array('api_user' => 'user', 'api_data' => 'dsafsafsdfX-==')))
	 * 
	 * @param	array	$post	This is the array that holds API Authentication variables
	 * 
	 * @return	boolean
	 */
	public function validate($post=array())
	{
		// initialize variables
		$application	= $post['api_user'];
		
		// attempt to grab API information for this user
		$api			= $this->CI->config->item($application);
		
		// if unable to load config data for this application, FAIL api auth - not a valid user
		if (empty($api) OR $api === FALSE) return FALSE;

		// grab salt from config (for this application)
		$salt			= $api['salt'];
		
		// decode the apikey
		$decoded = $this->CI->security->decrypt($post['api_data'], $salt);	
		$decoded = json_decode($decoded, TRUE);

		
		// make sure we have all data we were expecting
		if ((empty($decoded['app'])		OR ! isset($decoded['app'])) 
			OR (empty($decoded['time'])	OR ! isset($decoded['time'])) 
			OR (empty($decoded['ip'])	OR ! isset($decoded['ip'])))
				return FALSE;
		
		// validate timestamp within range
		$range = array(
			date('U',strtotime('-6 hours')), 
			date('U',strtotime('+6 hours'))
		);
		if (!($decoded['time'] > $range[0] && $decoded['time'] < $range[1])) return FALSE;
		
		// verify application passed in data matches the api_user
		if ($decoded['app'] != $application) return FALSE;

		return TRUE;
	}
	
	/**
	 * API Output Type
	 * 
	 * This method determines the API Output Type to use for this API Call
	 * 
	 * @return string
	 */
	private function _api_output_type()
	{
		// grab this application's config to see if output type is defined
		$config	= $this->CI->config->item($this->CI->input->post('api_user'));
		
		// default output type to json if not passed i POST and not set in config for this application
		return ($this->CI->input->post('api_output_type') !== FALSE)
			? $this->CI->input->post('api_output_type')
			: (isset($config['api_output_type']))
				? $config['api_output_type']
				: 'json';
	}
	
	/**
	 * Write Log
	 * 
	 * This method writes a log file with successful or failed API Authentication message
	 * 
	 * @param	array	$uri		This param holds the URI request array
	 * @param	array	$post		This param holds the POSTed values
	 * @param	boolean	$success	This boolean determines if failed or successful auth 	
	 * 
	 * @return	boolean
	 */
	private function _write_log($uri,$post,$success=TRUE)
	{
		// initialize variables
		$dir		= $uri[1];				// set directory to the module we are attempting to call
		$file		= date('Y-m-d').'.txt';	// set filename to current date
		$message	= 
<<<MSG

[success_message]
[uri_message]
[post_message]
******************************************************
MSG;
		
		// create log message
		$success_message	= ($success === TRUE) ? 'API Auth Success: '.date('H:i:s') : 'API Auth Failure: '.date('H:i:s');
		$uri_message		= 'URI - '.json_encode($uri);
		$post_message		= 'Request - '.json_encode($post);	

		// find and replace variables with log message
		$message    = str_replace('[success_message]',$success_message,$message);
		$message    = str_replace('[uri_message]',$uri_message,$message);
		$message    = str_replace('[post_message]',$post_message,$message);
		
		// write the log
		return $this->CI->logger->write($dir,$file,$message);
	}
	
}