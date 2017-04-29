<?php

class Signin extends MX_Controller
{
	/**
	 * The ID of this Partner
	 * @var int
	 */
	var $_partner_id;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner_id	= $this->session->userdata('partner_id');
	}

	public function index($error=FALSE,$skip_submit=FALSE)
	{
		// initialize variables
		$data	= array();

		// if URI Segments is empty, then we need to do a redirect
		if (empty($this->uri->segments))	redirect('signin');

		// if form was submitted, run _submit method
		if ($this->input->post() AND $skip_submit === FALSE)	return $this->_submit();

		// set template layout to use
		$this->template->set_layout('pre-login');
		
		// set the page's title
		$this->template->title('Partner Login');

		// set data variables
		$data['error']	= urldecode($error);
		
		// load view
		$this->template->build('signin/signin', $data);
	}

	/**
	 * This method logs in a user and sets needed sessions
	 * @return boolean
	 */
	private function _submit()
	{
		// initialize variables
		$username 	= $this->input->post('username');
		$password 	= $this->input->post('password');
		
		// verify login credentials
		$login 		= $this->platform->post('partner/login/user',array('username' => $username, 'password' => $password));

		// if login was unsuccessful, show error
		if ( ! $login['success'] OR ! $login['data'])				return $this->index('Invalid username/password.',TRUE);

		// set needed sessions
		if ( ! $this->_set_login_sessions($login['data']['id'])) 	return $this->index('Unable to set partner sessions.',TRUE);

		// set redirect URL
		$redirect 	= $this->session->userdata('_redirect');

		// set redirect URL
		$redirect 	= ( ! $redirect OR $redirect == 'signin')
			? 'home'		// No redirect URL specified, just redirect to home page
			: $redirect;	// The redirect URL to send user to

		// redirect somewhere
		redirect($redirect);
	}

	private function _set_login_sessions($partner_id=FALSE)
	{
		// make sure we ahve a valid partner id
		if ( ! $partner_id OR ! is_numeric($partner_id))	return FALSE;

		// grab partner details
		$partner 	= $this->platform->post('partner/account/details',array('partner_id' => $partner_id));

		// make sure we grabbed valid partner data
		if ( ! $partner['success'] OR ! $partner['data'] OR empty($partner['data']))	return FALSE;

		// set needed sessions
		$this->session->set_userdata('partner',$partner['data'][0]);

		return TRUE;
	}
	
	
}