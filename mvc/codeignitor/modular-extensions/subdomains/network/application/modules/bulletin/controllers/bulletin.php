<?php

class Bulletin extends MX_Controller {

	protected $_ads_module = 'ads4dough';

	/**
	 * This function has been deprecated due to change in ad code from Jeff on 4/10/2013
	 * @param  string $size [description]
	 * @return [type]       [description]
	 *
	function get($size = '300x250')
	{

		$data['ad'] = Modules::run($this->_ads_module.'/get',$size);

		$data['dimensions'] = explode('x',$size);

		$this->load->view('ad_iframe', $data);

	}
	*/

	function get($size = '300x250')
	{
		// set data variables
		$data['size']	= $size;

		$this->load->view('ads',$data);
	}


}