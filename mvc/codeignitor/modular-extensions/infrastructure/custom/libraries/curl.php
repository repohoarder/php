<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * cUrl
 * 
 * This class runs basic cUrl functions
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @method int|string|array|object	post(string $url, array $post);
 * @method int|string|array|object	get(string $url, string $get);
 * 
 */
class Curl
{

	/*
	 * Performs a cUrl POST
	 * 
	 * This method performs a basic cUrl POST and returns the response received
	 * 
	 * @author	John Thompson	<thompson2091 @ gmail.com>
	 * 
	 * @access	public
	 * 
	 * @example	post('http://www.ThecUrlPostURL',array('variable' => 'value'));
	 * 
	 * @param	string	$debug	This is the URL we need to cUrl
	 * @param	array	$post	The data to be POSTed to the cUrl URL 
	 * 
	 * @return	int|string|array|object
	 */
	public function post($url=false,$post=array())
	{
		// error handling
		if ( ! $url)
			throw new Exception('Valid URL not supplied.');
		
		// generate query string from post_data
		$query_string = http_build_query($post);
		
		// initialize curl
		$ch = curl_init();
		
		// set parameters
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 1000); //timeout in seconds		

		// run cUrl
		$response	= curl_exec ($ch);

		curl_close($ch);

		// return the response
		return $response;
	}	

	/*
	 * Performs a cUrl POST
	 * 
	 * This method performs a basic cUrl GET and returns the response received
	 * 
	 * @author	John Thompson	<thompson2091 @ gmail.com>
	 * 
	 * @access	public
	 * 
	 * @example	get('http://www.ThecUrlGetURL','variable1=value1&variable2=value2');
	 * 
	 * @param	string	$debug	This is the URL we need to cUrl
	 * @param	string	$get	The data to be sent via GET to the cUrl URL 
	 * 
	 * @return	int|string|array|object
	 */
	public function get($url=false,$get="")
	{
		// error handling
		if ( ! $url)
			throw new Exception('Valid URL not supplied.');
		
		// initialize curl
		$ch = curl_init();

		if (is_array($get)):

			$get = http_build_query($get);

		endif;

		// set cUrl url
		curl_setopt($ch, CURLOPT_URL, $url.'?'.$get);

		// set parameters
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// run cUrl
		$response = curl_exec($ch);

		curl_close($ch);

		// return the response
		return $response;
	}





	function post_async($url = FALSE, $post = array()) 
	{

		return $this->_async($url, $post, 'POST');
	}

	function get_async($url = FALSE, $get = array()) 
	{

		return $this->_async($url, $get, 'GET');
	}




	function _async($url, $params = array(), $type='POST') {
		
		$post_params = array();

		foreach ($params as $key => &$val):
		
			if (is_array($val)) $val = implode(',', $val);
			
			$post_params[] = $key.'='.urlencode($val);
		
		endforeach;

		$post_string = implode('&', $post_params);
		$parts       = parse_url($url);		
		$port        = isset($parts['port']) ? $parts['port'] : 80;
		$fsockhost   = $parts['host'];
		
		if ($parts['scheme'] == 'https'):
			
			$fsockhost = 'ssl://'.$parts['host'];
			$port = '443';
			
		endif;
		
		$fp = fsockopen($fsockhost, $port, $errno, $errstr, 30);

		// Data goes in the path for a GET request
		if('GET' == $type) $parts['path'] .= '?' . $post_string;

		$out = "$type ".$parts['path']." HTTP/1.1\r\n";
		$out.= "Host: ".$parts['host']."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: Close\r\n\r\n";
		
		// Data goes in the request body for a POST request
		if ('POST' == $type && isset($post_string)) $out .= $post_string;
		
		fwrite($fp, $out);
		fclose($fp);
	
	}
	
}