<?php

class Calculate_revenue extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('calculate_revenue');
	}
}