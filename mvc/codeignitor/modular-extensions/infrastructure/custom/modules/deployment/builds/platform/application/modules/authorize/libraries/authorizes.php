<?php

class Authorizes
{
	public function __construct()
	{
		// get codeignitor instance
		$this->CI 	=&get_instance();

		// load config
		$this->CI->load->config('authorize');
	}

	public function charge($vars=array())
	{
		// grab config API variables
		$api 		= $this->CI->config->item('api');

		// initialize API variables
		$url 		= $api['url'];
		$key 		= $api['key'];			// my authorize.net account (1257357)
		$id 		= $api['id'];
		$delimiter	= $api['delimiter'];	// this is the delimiter the response will be in

		// initialize post array
		$post 	= array(

			// set api login vars
			"x_login"			=> $key,
			"x_tran_key"		=> $id,

			// set authorize variables
			"x_version"			=> "3.1",		// api version
			"x_delim_data"		=> "TRUE",		// always TRUE for auth_capture transactions
			"x_delim_char"		=> $delimiter,	// delimiter character
			"x_relay_response"	=> "FALSE",		// always FALSE for auth_capture

			// set transacion type
			"x_type"			=> "AUTH_CAPTURE",
			"x_method"			=> "CC",

			// set credit card details
			"x_card_num"		=> $vars['credit_card'],		// Credit Card Number
			"x_exp_date"		=> $vars['credit_card_exp'],	// format MMYY
			"x_amount"			=> $vars['amount'],
			"x_description"		=> $vars['description'],

			// set billing details
			"x_first_name"		=> $vars['first'],
			"x_last_name"		=> $vars['last'],
			"x_address"			=> $vars['address'],
			"X_city"			=> $vars['city'],
			"x_state"			=> $vars['state'],
			"x_zip"				=> $vars['zip'],
			"x_country"			=> $vars['country'],

			// set customer details
			"x_phone"			=> $vars['phone'],
			"x_email"			=> $vars['email'],
			"x_customer_ip"		=> $vars['ip'],

		);

		// build http query string
		$post 		= http_build_query($post);

		$ch 		= curl_init($url); // initiate curl object

		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // use HTTP POST to send form data
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		
		// grab response
		$response 	= curl_exec($ch); // execute curl post and store results in $post_response
		
		curl_close ($ch); // close curl object

		// This line takes the response and breaks it into an array using the specified delimiting character
		$response 	= explode($delimiter,$response);

		// set return array
		$return 	= array(
			'response_code'			=> $response[0],
			'response_code_sub'		=> $response[1],
			'response_reason'		=> $response[3],
			'response_reason_code'	=> $response[2],
			'authorization_code'	=> $response[4],
			'avs_response'			=> $response[5],
			'transaction_id'		=> $response[6],
			'invoice_id'			=> $response[7],
			'description'			=> $response[8],
			'amount'				=> $response[9],
			'transaction_type'		=> $response[11],
			'customer_id'			=> $response[12],
			'order_id'				=> $response[37],
			'cvv_response'			=> $response[38],
			'cc_last_four'			=> $response[50],
			'balance_on_card'		=> $response[54]
		);

		// return response
		return $return;
	}

	public function refund()
	{
		
	}
}