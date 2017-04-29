<?php

class Testing extends MX_Controller {


	function index()
	{

		$response 	= $this->curl->post('http://admin.code.bh/essent/partials/data',array('first_name' => 'matt', 'last_name' => 'thompson', 'email' => 'thompson2091+4@gmail.com', 'date' => '2013-01-24 00:00:00', 'ip' => '127.0.0.1'));

		$this->debug->show(json_decode($response,true),true);

	}

}