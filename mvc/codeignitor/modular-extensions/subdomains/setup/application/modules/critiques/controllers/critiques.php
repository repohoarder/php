<?php

class Critiques extends MX_Controller {


	function index()
	{

		$this->template->set_layout('bare');
		$this->template->build('critiques/critiques');

	}


}