<?php

class Whoiss
{
	public function __construct()
	{
		$this->CI 	= &get_instance();

		// include whois libraries
	   	include_once('phpwhois/whois.main.php');
	   	include_once('phpwhoisutils/whois.utils.php');

	   	// set whois object
	   	$this->_whois 	= new Whois();
	   	$this->_util 	= new utils;

	   	// load regex config
	   	$this->CI->load->config('regex');

	   	// load regex config item
	   	$this->_regex	= $this->CI->config->item('regex');
	}

	public function lookup($domain)
	{
		// initialize variables
		$response 	= array();

		// error handling

		// perform whois lookup
		$whois 	= $this->_whois->Lookup($domain);

		// return error if one is received
		if (empty($whois['rawdata']))
			return $this->CI->api->response(FALSE,$this->_whois->Query['errstr']);

		// grab HTML
		$response	= $this->_util->showHTML($whois);

		// return the array of emails
		return $this->CI->api->response(TRUE,$response);
	}

	public function email($domain)
	{
		// initialize variables
		$response 	= array();

		// error handling

		// perform whois lookup
		$whois 	= $this->_whois->Lookup($domain);

		// return error if one is received
		if (empty($whois['rawdata']))
			return $this->CI->api->response(FALSE,$this->_whois->Query['errstr']);

		// iterate data
		foreach ($whois['rawdata'] AS $key => $value):

			// see if we are able to match an email address
			if (preg_match($this->_regex['email'],$value,$matches)):

				// if a match is found, add it to the response array
				$response[]	= $matches[0];

			endif;

		endforeach;

		// if the response array is empty, then we were unable to find an email
		if (empty($response))
			return $this->CI->api->response(FALSE,'Unable to find an email address.');

		// return the array of emails
		return $this->CI->api->response(TRUE,$response);
	}
}