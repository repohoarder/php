<?php


class Banner extends MX_Controller
{
	/**
	 * Data
	 * 
	 * @var  _data This variable holds the data variables needed for the view
	 */
	var $_data;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Index
	 * 
	 * This method displays the banner
	 */
	public function index()
	{
		// load the view
		$this->load->view('banner', $this->_data);
	}

	public function four_o_four()
	{
		// set needed data variables
		$this->_data['banner_width']	= '728';
		$this->_data['banner_height']	= '90';
		$this->_data['banner_src']		= '/resources/modules/banner/assets/img/banner-free-website.jpg';
		$this->_data['banner_alt']		= 'This is some alt text for the banner';
		$this->_data['banner_href']		= $this->lang->line('affiliate_link_404');

		// load the view
		return $this->index();
	}
}