<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library will create a 
 * The configArr must consist
 * Author : Jamie Rohr
 * Date : 10-02-2012
 * 
 *
 */
class Securityit {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
	function ENCRYPTIT($string){
		$key= "thisissome!superlongKEYforEncryption341PleaseUSeWhiselyisspelledwrong";
		$result = '';
		for($i=0; $i<strlen($string); $i++)
		 {
		 	$char = substr($string, $i, 1);
		 	$keychar = substr($key, ($i % strlen($key))-1, 1);
		 	$char=chr(ord($char)+ord($keychar));
		 	$result.=$char;
		 }
	 	return base64_encode($result);
	}
	function DECRYPTIT($string){
		$key= "thisissome!superlongKEYforEncryption341PleaseUSeWhiselyisspelledwrong";
		$result='';
		$string=base64_decode($string);
		for($i=0;$i<strlen($string);$i++)
		{	
			$char=substr($string,$i,1);
			$keychar=substr($key,($i%strlen($key))-1,1);
			$char=chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}
}