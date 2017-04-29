<?php

/*
 * URI
 * 
 * This class formats URI strings.
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @method array	response(boolean $success, array $data)
 * @method array	obj_to_arr(object|array $obj)
 * @method string	query_string(array $arr)
 * 
 */
class Uri
{
	
	/*
	 * Creates query string from array
	 * 
	 * This method will take an array and format it into a URL query string
	 * 
	 * @author	John Thompson	<thompson2091 @ gmail.com>
	 * 
	 * @access	public
	 * 
	 * @example	string(array('variable1' => 'value1', 'variable2' => 'value2'))
	 * 
	 * @param	array	$arr	The array to be converted into a query string 
	 * 
	 * @return	string
	 */
	public function string($arr=array())
	{

		// generate query string from get array
		$query_string 	= http_build_query($arr);

		// we need to string replace %5B and %5D with [ and ]
		$query_string	= str_replace('%5B','[',str_replace('%5D',']',$query_string));
		
		// we need to replace %40 with @
		$query_string	= str_replace('%40','@',$query_string);
		
		// return the query string
		return $query_string;
	}

}

?>