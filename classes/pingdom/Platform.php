<?php

namespace Pingdom;

class Platform
{
	var $_url 		= 'https://api.pingdom.com/api/2.0/';
	var $_username 	= 'matt.thompson@stack.com';
	var $_password 	= 'matthew20!';
	var $_key 		= 'vyl2vc0oxfkz3wk8tr2qmegj5ppacw1h';

	public function __construct($url=FALSE)
	{
		// if URL, override
		if ($url)
			$this->_url 	= $url;
	}

	public function Post($method='',$data=array(),$request='POST')
	{
		// init vars
		$url 	= $this->_url.$method;

		// init curl 
		$ch 	= curl_init();

		// set target URL
		curl_setopt($ch, CURLOPT_URL, $url);

		// set user and password
		curl_setopt($ch, CURLOPT_USERPWD, $this->_username.":".$this->_password);

		// set api key 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("App-Key: ".$this->_key));

		// if data, then set POST params
		if ($request == 'POST'):

			// set post fields
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

		endif;

		// ignore SSL certification (for local testing)
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 0);

		// execute
		$response 	= json_decode(curl_exec($ch),TRUE);

		// grab cUrl response headers and code
		$headers 	= curl_getinfo($ch, CURLINFO_HEADER_OUT);
		$code    	= curl_getinfo($ch, CURLINFO_HTTP_CODE);

		// close curl 
		curl_close($ch);

		// return response
		return json_decode($response); 
	}
}