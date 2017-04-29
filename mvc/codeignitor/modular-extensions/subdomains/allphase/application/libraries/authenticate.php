<?php 

class Authenticate
{
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	public function __construct()
	{
		// get codeignitor instance
		$this->CI =& get_instance();

		// grab current page segments
		$page 		= $this->CI->uri->segments;

		// create array of "insecure" pages (pages that do not need auth'd)
		$insecure	= array(
			'signin',
			'resources',
			'pass',
			'cron'
		);

		// verify partner_id is set (unless this is the login page)
		if ( ! isset($page[1]) OR ( ! $this->CI->session->userdata('partner') AND ! in_array($page[1], $insecure))):

			// set session with page to redirect to (if logged out)
			$this->CI->session->set_userdata('_redirect',$this->CI->uri->uri_string());

			// redirect
			redirect('signin');
			
		endif;

		// if we made it here, then we have a partner_id set
		return TRUE;
	}
}