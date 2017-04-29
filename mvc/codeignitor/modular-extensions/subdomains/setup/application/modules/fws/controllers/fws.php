<?php

class Fws extends MX_Controller {


	function index()
	{


		$params = array(
			'url' => 'freewebsite.com',
		);

		$this->load->library('curl');

		$resp = $this->platform->post(
			'curl_proxy/get',
			array(
				'api_key' => 'iwishiwasan0scarMayerwi3ner',
				'url'     => 'freewebsite.com'
			)
		);

		var_dump($resp);

	}

}