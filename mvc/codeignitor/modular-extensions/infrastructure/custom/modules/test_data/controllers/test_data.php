<?php

class Test_data extends MX_Controller {


	function index()
	{
		$this->load->library('cache');

		$this->cache->get('testkey');


	}

	function info()
	{
		phpinfo();
	}

}