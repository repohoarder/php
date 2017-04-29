<?php

class Debugger extends MX_Controller {

	function index()
	{

		$this->load->config('debug');

		if ( ! in_array($this->session->userdata('ip_address'), $this->config->item('debug_ips'))):

			show_404();
			return;

		endif;

		$this->load->view('debugger');

	}
	
}