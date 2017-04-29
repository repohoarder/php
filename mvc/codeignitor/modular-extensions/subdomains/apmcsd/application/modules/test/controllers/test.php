<?php

class Test extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->template->set_theme('test_theme');

		// set template layout to use
		$this->template->set_layout('test_layout');

		// set the page's title
		$this->template->title('Testing yo');

		$data = array();

		// load view
		$this->template->build('offer/video', $data);
	}

	public function it()
	{
		echo 'test/it';
	}

}