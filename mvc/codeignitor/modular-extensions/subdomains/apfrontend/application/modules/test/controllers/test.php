<?php

class Test extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo 'test page';
	}

	public function it()
	{
		echo 'test/it';
	}

}