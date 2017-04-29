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
		$this->CI->load->library('session');
		
		// create array of "insecure" pages (pages that do not need auth'd)
		$insecure	= array(
			'login',
			'resources',
			'logout',
			'cron'
		);

		// verify partner_id is set (unless this is the login page)
		if ( ! isset($page[1]) OR ( ! $this->CI->session->userdata('login_id') AND ! in_array($page[1], $insecure)) ):

			// set session with page to redirect to (if logged out)
			$this->CI->session->set_userdata('_redirect',$this->CI->config->item('subdir').'/'.$this->CI->uri->uri_string());

			// redirect
			redirect($this->CI->config->item('subdir').'/login/auth');
			
		endif;

		// if we made it here, then we have a partner_id set
		return TRUE;
	}
}
