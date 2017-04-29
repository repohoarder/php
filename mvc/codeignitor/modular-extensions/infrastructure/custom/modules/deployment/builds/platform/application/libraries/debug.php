<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Debug
 * 
 * This class helps user's to properly debug their applications by spitting results
 * into easy to read formats
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @method void show(array|object $debug, boolean $exit);
 * @method void dump(array|object $debug, boolean $exit);
 * 
 */
class Debug
{
	
	/*
	 * Prints array/object into a readable format for debugging easier
	 * 
	 * This method was made so that you could easily print an object and/or
	 * an array into an easy to read format.  Ideal for debugging.
	 * 
	 * @author	John Thompson	<thompson2091 @ gmail.com>
	 * 
	 * @access	public
	 * 
	 * @example	show(array('success' => TRUE, 'data' => array('my','data','is','here')),TRUE);
	 * 
	 * @param	array|object	$debug	This is the object/array to be shown
	 * @param	boolean		$exit	When true it will exit() 
	 * 
	 * @return	void
	 */
	function show($debug,$exit=false)
	{
		
		// print the array/object passed
		print "<pre>";
		print_r($debug);
		print "</pre>";

		// if exit bool, then exit the page
		if($exit) exit;
		
		return;
	}
	
	/*
	 * Var_dump's array|object into a readable format for debugging easier
	 * 
	 * This method was made so that you could easily var_dump an object|array
	 * into an easy to read format.  Ideal for debugging.
	 * 
	 * @author	John Thompson	<thompson2091 @ gmail.com>
	 * 
	 * @access	public
	 * 
	 * @example	dump(array('success' => TRUE, 'data' => array('my','data','is','here')),TRUE);
	 * 
	 * @param	array|object	$debug	This is the object/array to be dumped
	 * @param	boolean		$exit	When true it will exit() 
	 * 
	 * @return	void
	 */
	function dump($debug,$exit=false)
	{
		
		// print the array/object passed
		print "<pre>";
		var_dump($debug);
		print "</pre>";

		// if exit bool, then exit the page
		if($exit) exit;
		
		return;
	}
	
}