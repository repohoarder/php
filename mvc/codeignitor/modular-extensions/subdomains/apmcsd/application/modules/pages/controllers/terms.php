<?php

class Terms extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		// set default logo to load
		$this->_logo			= 'http://a.hostingaccountsetup.com/resources/apmcsd/img/logo.png';
	}

	public function index()
	{
        $this->template->set_theme('apmcsd');

		// set template layout to use
		$this->template->set_layout('default');

		// set the page's title
		$this->template->title('Terms & Conditions');

		// set data varibales
		$data['logo']	= $this->_get_logo();

		// load view
		$this->template->build('pages/terms', $data);
	}

	public function _get_logo()
	{
		return ($this->session->userdata('_logo'))? $this->session->userdata('_logo'): $this->_logo;
	}
}