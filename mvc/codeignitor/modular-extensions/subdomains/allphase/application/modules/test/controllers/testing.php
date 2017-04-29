<?php

class Testing extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo 'testing page';
	}

	public function me()
	{
		echo 'testing/me';
	}

}