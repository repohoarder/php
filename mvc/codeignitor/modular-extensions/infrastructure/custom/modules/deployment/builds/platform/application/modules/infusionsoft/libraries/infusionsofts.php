<?php

class Infusionsofts
{
	public function __construct()
	{
		$this->CI 	= &get_instance();

		// include the Infusionsoft SDK
		require("isdk.php");  

		// connect to database
		
	}

	public function sale()
	{
		// initialize variables
		$response 	= array();

		// error handling

		// return the array of emails
		return $this->CI->api->response(TRUE,$response);
	}


}