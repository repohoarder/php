<?php

class Opt_out extends MX_Controller {


	function index()
	{

		$this->lang->load('opt');

		$this->template->set_layout('bare');

		$this->template->title($this->lang->line('opt_out_title'));
		
		$this->template->build('opt_out');
	}

}