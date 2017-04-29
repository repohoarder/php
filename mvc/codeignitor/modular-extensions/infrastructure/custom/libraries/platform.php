<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Platform
 * 
 * This class handles integration with an external platform.
 *
 * @version 1.1 Aug 27, 2012 - Added Exceptions
 * @version	1.0	July 28, 2012
 * 
 * @method array	post(string $api, array $postvars)	This method performs a cUrl to the external platform - accounting for all API auth.
 * 
 */
class Platform {
	
	/*
	 * This variable holds the CI instance object
	 * 
	 * @var object	$CI
	 */
	var $CI;
	
	
	public function __construct() {
		
		$this->CI = &get_instance();
		
		// set platform variables
		$this->platform = $this->CI->config->item('platform');	// This config is set in each subdomain(s) config folder
		
	}
	
	/**
	 * Performs cUrl POST to the External Platform
	 * 
	 * This method creates auth token and POSTs data via cUrl to the External Platform URL
	 * 
	 * @access	public
	 * 
	 * @example	post('ubersmith/client/add',array('first' => 'Matt'));
	 * 
	 * @param	string	$api		This is the API that we want to cUrl
	 * @param	array	$postvars	This is the data array that we want to cUrl to our Platform 
	 * 
	 * @return	array
	 */
	public function post($api, $postvars = array(), $config_overwrite = FALSE, $async = FALSE)
	{

		if ($config_overwrite !== FALSE && is_array($config_overwrite)):

			$this->platform = $config_overwrite;

		endif;


		if (substr($api, 0, 1)=='/'):

			$api = substr($api, 1);

		endif;
	
		// set the api user (app)
		$postvars['api_user']	= $this->platform['app'];
		
		// set data variables needed to generate api key
		$postvars['api_data']	= json_encode(
			array(
				'app'	=> ($_SERVER['SERVER_ADDR']=='127.0.0.1') ? $this->platform['app'] : $_SERVER['HTTP_HOST'],
				'ip'	=> $_SERVER['SERVER_ADDR'],
				'time'	=> date('U')
			)
		);
		
		// generate api key
		$postvars['api_data']	= $this->CI->security->encrypt($postvars['api_data'], $this->platform['salt']);

		$plat_url = $this->platform['url'];

		if (substr($plat_url,-1) != '/'):

			$plat_url .= '/';

		endif;



		$url = $this->platform['url'].$api;

		if ($async):

			$this->CI->curl->post_async($url, $postvars);
			return;

		endif;

		
		// cUrl the data to the platform's API
		$response = $this->CI->curl->post($url, $postvars);

		if (is_null($response)):

			throw new Exception('Platform API call failed: '.$api.' '.$response);
			return;

		endif;

		$return = json_decode($response, TRUE);

		if (is_null($return) || ! is_array($return) || ! array_key_exists('success',$return)) :

			throw new Exception($url.' Improperly formatted Platform API response'.' '.$response.' API: '.$api);
			return;

		endif;

		// return the decoded response (all responses from platform are json encoded)
		return $return;
	}


	public function post_async($api, $postvars = array(), $config_overwrite = FALSE)
	{

		$this->post($api, $postvars, FALSE, TRUE);
	}


}