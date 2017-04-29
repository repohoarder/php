<?php

class Video extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();

		// default variables
		$this->_logo 		= 'http://a.hostingaccountsetup.com/resources/apmcsd/img/logo.png';
		$this->_video		= 'http://3ad6d0cb6f5c71565427-0ceefa5c30f5e4f21bf782807a37e6a1.r85.cf1.rackcdn.com/Generic_Sales_Video_1-7.flv';
		$this->_company 	= 'Hosting Account Setup';
	}
        
    public function index($partner_id=FALSE,$error=FALSE)
	{
		// initialize variables
		$data		= array();
		$partner_id	= ( ! $partner_id)? 1: $partner_id;	// default partner id to 1 if not found

		// grab partner details
		$details 	= $this->platform->post('partner/account/details',array('partner_id' => $partner_id));

		// if unable to grab details, then redirect to partner id 1
		if ( ! $details['success'] OR empty($details['data'])):
			redirect('tour/1');return;
		endif;

		// set data variables
		$data['partner_id']		= $partner_id;
		$data['video']			= $this->_video;

		// see if user has company name set
		$data['company']		= (isset($details['data'][0]['website'][0]['company_name']) AND $details['data'][0]['website'][0]['company_name'] != '')? $details['data'][0]['website'][0]['company_name']: $this->_company;

		// see if user has custom logo
		$data['logo']			= ($this->_is_image($details['data'][0]['website'][0]['logo_file']))? $details['data'][0]['website'][0]['logo_file']: $this->_logo;

		// set the page's title
		$this->template->title('Welcome to '.$data['company']);

		// load view
		$this->template->build('tour/video', $data);
	}

	private function _is_image($url)
	{
		// see if we get data back
		$image 	= @getimagesize($url);

		return (is_array($image))
			? TRUE 
			: FALSE;
	}
}