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
	}

	public function partner()
	{
		// grab current page
		$page 		= $this->CI->uri->segments;

		// create array of "insecure" pages (pages that do not need auth'd)
		$insecure	= array(
			'signin',
			'resources'
		);

		// verify partner_id is set (unless this is the login page)
		if ( ! $this->CI->session->userdata('partner') AND ! in_array($page[1], $insecure))	redirect('signin');

		//$this->CI->debug->show($this->CI->session->userdata('partner'),true);

		// if we made it here, then we have a partner_id set
		return TRUE;
	}
}