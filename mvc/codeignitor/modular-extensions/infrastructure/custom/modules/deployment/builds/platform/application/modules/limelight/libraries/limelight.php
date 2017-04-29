<?php

class Limelight
{
	var $_url;
	var $_username;
	var $_password;

	public function __construct($params)
	{
		$this->CI 	= &get_instance();

		// load config
		$this->CI->load->config('limelight');

		// load config vars
		$config 		= $this->CI->config->item('application');

		// set global variables
		$this->_url 		= $config[$params['application']]['url'];
		$this->_username 	= $config[$params['application']]['username'];
		$this->_password 	= $config[$params['application']]['password'];
	}

	public function add_prospect($vars=array())
	{
		$url 	= 'transact.php';

		// create post array
		$post 	= array(
			'method'		=> 'NewProspect',
			'campaignId'	=> $vars['campaign_id'],				// MaxLeanX
			'firstName'		=> $vars['first'],
			'lastName'		=> $vars['last'],
			'address1'		=> $vars['address'],
			'city'			=> $vars['city'],
			'state'			=> $vars['state'],
			'zip'			=> $vars['zip'],
			'country'		=> $vars['country'],
			'phone'			=> $vars['phone'],
			'email'			=> $vars['email'],
			'AFID'			=> $vars['affiliate_id'],
			'SID'			=> $vars['subid'],
			'ipAddress'		=> $vars['ip']
		);

		// add prospect
		$prospect 	= $this->post($url,$post);

		// parse response
		parse_str($prospect, $limelight);

		// set return variable (either prospect id or error message)
		$return 	= (isset($limelight['errorFound']) AND ! empty($limelight['errorFound']))? $limelight['declineReason']: (int)$limelight['prospectId'];

		// return prospect ID or error
		return $return;
	}

	public function get_prospect_info($prospect_id)
	{
		// set API url
		$url 	= 'membership.php';

		// set POST vars
		$post 	= array(
			'method'		=> 'prospect_view',
			'prospect_id'	=> $prospect_id
		);

		return $this->post($url,$post);
	}

	public function add_prospect_sale($vars=array())
	{
		$url 	= 'transact.php';

		// create post array
		$post 	= array(
			'method'				=> 'NewOrderWithProspect',
			'prospectId'			=> $vars['prospect_id'],
			'upsellCount'			=> 0,
			'productId'				=> $vars['product_id'],
			'campaignId'			=> $vars['campaign_id'],
			'shippingId'			=> $vars['shipping_id'],
			'creditCardType'		=> $vars['card_type'],
			'creditCardNumber'		=> $vars['credit_card_number'],
			'expirationDate'		=> $vars['exp_month'].$vars['exp_year'],
			'CVV'					=> $vars['cvv'],
			'tranType'				=> 'Sale',
			'billingSameAsShipping'	=> 'yes',
			'product_qty_1'			=> 1,
			//'forceGatewayId'		=> 2
		);

		// if gateway id was passed, force it
		if (isset($vars['gateway_id']) AND $vars['gateway_id'] AND is_numeric($vars['gateway_id']))
			$post['forceGatewayId']	= $vars['gateway_id'];

		// attempt sale
		$sale 	= $this->post($url,$post);

		// parse the response
		parse_str($sale, $limelight);

		// return response
		return $limelight;
	}

	public function get_product($product_id)
	{
		// set API URL
		$url 	= 'membership.php';

		// set POST vars
		$post 	= array(
			'method'		=> 'product_index',
			'product_id'	=> $product_id
		);

		// get product
		$product = $this->post($url,$post);

		// parse response
		parse_str($product,$limelight);

		// return
		return $limelight;
	}

	public function post($url,$post=array())
	{
		$url 	= $this->_url.$url;

		// add username and password to post
		$post['username']	= $this->_username;
		$post['password']	= $this->_password;

		// generate query string from post_data
		$query_string = http_build_query($post);
	
		// initialize curl
		$ch = curl_init();
		
		// set parameters
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// run cUrl
		$response	= curl_exec ($ch);

		curl_close($ch);

		// return the response
		return $response;
	}

}