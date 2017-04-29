<?php

class Operatingcosts extends MX_Controller
{
	/**
	 * The array of PArtner Information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

	}
	public function index(){
		exit(0);
	}
	public function refundcron(){
		$start 	= date('Y-m-d',time() - 24*60*60);
		$end 	= date('Y-m-d',time());
		$config = array(
			'startdate'	=> $start,
			'enddate'	=> $end
		);
		
	
		// refresh refunds
		$do = $this->platform->post('partner/expense/dorefundcron',$config);
		var_dump($do);
	}
	public function salescron(){
		$start 	= date('Y-m-d',time() - 24*60*60);
		$end 	= date('Y-m-d',time());
		$config = array(
			'startdate'	=> $start,
			'enddate'	=> $end
		);
		// get transaction data
		$do = $this->platform->post('partner/expense/doexpensecron',$config);
		var_dump($do);
	}
}
