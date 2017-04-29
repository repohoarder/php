<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Alertboxes {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
	public function linkedGoogleAccount(){
		$html='';
		$this->CI->load->library('googlecalendar');
		// check to see if this user has google account linked
		$has_access = $this->CI->googlecalendar->checkGoogleSetup();
		// if the user has access grab calendars.
		if($has_access) :
			//create client
			$client = $this->CI->googlecalendar->googleClient();
			// create calendar
			$cal = $this->CI->googlecalendar->googleCal($client);
			if(!$this->CI->googlecalendar->validateToken($client)):
			 	$authUrl = $this->CI->googlecalendar->createAuthLink($client);
			 	$html = $this->CI->googlecalendar->createAuthError($authUrl);
			 	else:
			 	$html .= $this->CI->googlecalendar->checkDefaultCalendar();
			endif;
		endif;
		return $html;
		
	}
	
	
}