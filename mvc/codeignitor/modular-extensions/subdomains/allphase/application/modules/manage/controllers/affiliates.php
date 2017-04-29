<?php

class Affiliates extends MX_Controller
{
	/**
	 * The array of PArtner Information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}

	public function index()
	{
		// initialize variables
		$data	= array();
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Manage Affiliates');
		
		// set data variables
		$data['partner']	= $this->_partner;

		// load view
		$this->template->build('manage/affiliates', $data);
	}
}