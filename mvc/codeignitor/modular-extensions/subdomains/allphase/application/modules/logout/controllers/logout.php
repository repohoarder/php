<?php

class Logout extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// delete all sessions
		$this->_delete_all_sessions();

		// redirect user to signin
		redirect('signin');
	}

	private function _delete_all_sessions()
	{
		// grab all sessions
		$sessions 	= $this->session->all_userdata();

		// iterate through each session
		foreach ($sessions AS $key => $value):

			// unset session
			@$this->session->unset_userdata($key);

		endforeach;

		return;
	}
}