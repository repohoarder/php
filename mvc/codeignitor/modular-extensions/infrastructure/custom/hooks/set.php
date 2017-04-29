<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Set
{
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	public function __construct()
	{
		// get codeignitor instance
		$this->CI =& get_instance();
	}

	public function language()
	{
		// make sure valid codeignitor object
		if ( ! is_object($this->CI))
			return true;

		// see if language session is set
		if ($this->CI->session->userdata('_language')):

			// set language config item
			$this->CI->config->set_item('language',$this->CI->session->userdata('_language'));

		endif;

		return;
	}
}