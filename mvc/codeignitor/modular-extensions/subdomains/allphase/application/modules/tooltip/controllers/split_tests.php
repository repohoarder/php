<?php

class Split_tests extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('split_tests');
	}
}