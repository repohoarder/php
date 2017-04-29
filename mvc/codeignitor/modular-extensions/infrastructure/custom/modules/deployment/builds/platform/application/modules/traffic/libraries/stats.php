<?php

class Stats
{
	var $_api;

	public function __construct()
	{
		// get CI instance
		$this->CI 		= &get_instance();

		// load taxi config
		$this->CI->load->config('taxi');

		// load api config vars
		$this->_api 	= $this->CI->config->item('stats');
	}

	public function retrieve($campaign_id=FALSE,$password=FALSE)
	{
		// error handling
		if ( ! $campaign_id)
			return FALSE;

		if ( ! $password)
			return FALSE;

		// set method to run
		$function 	= 'retrieve_stats';

		// create parameters array
		$params 	= array(
			'id'	=> $campaign_id,
			'pw'	=> $password
		);

		$response 	= $this->_connect($function,$params);

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

	private function _parse_response($response)
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