<?php

class Log extends MX_Controller 
{

	public function __construct()
	{

		parent::__construct();
	}

	public function index()
	{
		redirect('log/in');
	}

	public function in()
	{
		if ($this->input->post()) return $this->_submit();

		$this->template->set_layout('login');

		$this->template->build('in');
	}

	private function _submit()
	{
		$username 	= $this->input->post('username');
		$password 	= $this->input->post('password');

		redirect('dashboard');
	}
}