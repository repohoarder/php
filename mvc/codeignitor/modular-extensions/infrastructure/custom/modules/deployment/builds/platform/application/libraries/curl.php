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
	public function get_auth_header($url=false,$get="", $auth = '') 
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

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$auth));

		// run cUrl
		$response = curl_exec($ch);

		// return the response
		return $response;
	}
	 */

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
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		// run cUrl
		$response = curl_exec ($ch);

		// close curl handler
		curl_close($ch);

		// return the response
		return $response;
	}	

	/*
	 * Performs a cUrl GET
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
		
		$get     = (is_array($get) ? http_build_query($get) : $get);
		//$headers = (is_array($headers) ? $headers : array());

		// initialize curl
		$ch = curl_init();

		// set cUrl url
		curl_setopt($ch, CURLOPT_URL, $url."?".$get);

		// set parameters
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// run cUrl
		$response = curl_exec ($ch);

		// return the response
		return $response;
	}

	/*
	public function post_get($url=FALSE,$post=array())
	{
		// error handling
		if ( ! $url)
			throw new Exception('Valid URL not supplied.');
		
		// generate query string from post_data
		$postdata = http_build_query($post);

		// initialize cUrl
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

		// execute cUrl
		$response = curl_exec($ch);

		// close cUrl handler
		curl_close($ch);

		return $response;
	}
	*/

}