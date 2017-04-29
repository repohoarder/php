<?php

class Test extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// initialize variables
		$data	= array();

		// set template layout to use
		$this->template->set_layout('bare_no_footer');

		// load view
		$this->template->build('test/test', $data);
	}
}