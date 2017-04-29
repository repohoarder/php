<?php

class Bulletin extends MX_Controller {

	protected $_ads_module = 'ads4dough';

	function get($size = '300x250')
	{

		$data['ad'] = Modules::run($this->_ads_module.'/get/',$size);

		$this->load->view('ad_iframe');

	}


}