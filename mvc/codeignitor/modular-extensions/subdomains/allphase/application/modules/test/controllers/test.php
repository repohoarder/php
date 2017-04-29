<?php

class Test extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo $this->password->generate('admin');
	}

	public function it()
	{
		echo 'test/it';
	}

	public function password($password)
	{
		$pass 	= $this->password->generate($password);

		$this->debug->show($pass);
	}
}