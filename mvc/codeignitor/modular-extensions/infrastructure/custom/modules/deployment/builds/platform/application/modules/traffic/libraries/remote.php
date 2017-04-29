<?php

class Remote
{
	var $_api;

	public function __construct()
	{
		// get CI instance
		$this->CI 		= &get_instance();

		// load taxi config
		$this->CI->load->config('taxi');

		// load api config vars
		$this->_api 	= $this->CI->config->item('remote');
	}

	public function create($params) {		

		$function = 'start_campaign';

		$static = array(
			'returnto'   => 'http://www.hostingaccountsetup.com',
			'priority'   => 1,
			'properties' => 0
		);

		$params = array_merge($params, $static);
		

		// grab category from config
		$cats 	= $this->CI->config->item('categories');

		if ( ! array_key_exists($params['cat'],$cats)):

			$params['cat'] = '9999';
		
		endif;

		$post = $params;

		$post['vamount'] = $params['hits'];
		$post['catnumb'] = $params['cat'];

		unset($post['hits']);
		unset($post['cat']);

		if (isset($post['user'])):

			$post['camid'] = $post['user'];
			$post['campw'] = $post['pass'];

			unset($post['user']);
			unset($post['pass']);

			$function = 'start_campaign_advanced';

		endif;

		// submit information to create taxi campaign
		$response 	= $this->_connect($function, $post);

		return $response;

	}

	public function refund($campaign_id)
	{

		$params = array(
			'id'       => $campaign_id,
			'res'      => 2,
			'returnto' => 'http://www.hostingaccountsetup.com'
		);

		$response 	= $this->_connect('refund_campaign', $params);
		
		return $response;
	}


	public function _connect($function = 'start_campaign_advanced', $post = array())
	{
		// initialize variables
		$url             = $this->_api['url'];
		
		// add reseller id and pin to POST
		$post['rid']     = $this->_api['reseller_id'];
		$post['pin']     = $this->_api['pin'];
		
		// set version
		$post['version'] = $this->_api['version'];
		
		// if this function doesn't exist, return error
		if ( ! isset($this->_api['function'][$function])):
			return FALSE;
		endif;
		
		// grab function id
		$post['f']       = $this->_api['function'][$function];
		
		// curl the data over
		$response        = $this->CI->curl->post($url,$post);
		$response        = $this->_parse_response($response);

		return $response;
	}

	function _parse_response($response)
	{

		if(substr($response, 0, 5) == '<?xml'):

			$response = $this->CI->xml->to_array($response);

			if (isset($response['ERROR'])):

				$return = array(
					'success' => 0,
					'error'   => array($response['ERROR']),
					'data'    => array('response' => $response)
				);

				return $return;

			endif;

			$return = array(
				'success' => 1,
				'error'   => array(),
				'data'    => array('response' => $response)
			);

			return $return;

		endif;


		if( ! stristr($response, '302 Found')):

			$error = strip_tags(trim(str_replace('<br>', "\n", $response)));

			$return = array(
				'success' => 0,
				'error'   => array($error),
				'data'    => array('response' => $response)
			);

			return $return;

		endif;
		
		$doc   = new DomDocument();
		$doc->loadHTML($response);
		$query_string = FALSE;

		foreach ($doc->getElementsByTagName("a") as $a):

			if ( ! $a->hasAttribute("href")):

				continue;

			endif;

			$href  = trim($a->getAttribute("href"));	

			$query_string = trim(str_replace('?','',strstr($href,'?')));
			break;

		endforeach;
		
		if ( ! $query_string):
			
			$return = array(
				'success' => 0,
				'error'   => array('Unable to read response variables'),
				'data'    => array('response' => $response)
			);

			return $return;
		
		endif;


		@parse_str($query_string, $response);

		if (isset($response['ERROR'])):

			$return = array(
				'success' => 0,
				'error'   => array($response['ERROR']),
				'data'    => array('response' => $response)
			);

			return $return;

		endif;


		$return = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array('response' => $response)
		);

		return $return;
			

	}
}