<?php

class Five9 extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();

	}
	public function index(){
		
		// refresh refunds
		$do = $this->platform->post('five9/reports/runreport', array());
		var_dump($do);
	}

}
