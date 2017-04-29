<?php

class Landers extends MX_Controller
{
	/**
	 * The array of PArtner Information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');

		// set default video
		$this->_video 	= 'http://3ad6d0cb6f5c71565427-0ceefa5c30f5e4f21bf782807a37e6a1.r85.cf1.rackcdn.com/Generic_Sales_Video_1-7.flv';

		// set default text 
		$this->_text 	= 'A Special Offer For You!';
	}

	public function index($landing_page_id=1,$error=FALSE)
	{
		// initialize variables
		$data	= array();

		// if user POSTed data, run submit function
		if ($this->input->post())	return $this->_submit();

		// grab partner website information
		$landers 	= $this->platform->post('partner/lander/get',array('landing_page_id' => $landing_page_id, 'partner_id' => $this->_partner['id']));

		// if unable to grab website data, set as empty array (to avoid errors)
		if ( ! $landers['success'] OR ! $landers['data'] OR empty($landers['data']))	$landers	= array('data' => array());
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Manage Landing Page');

		// set data variables
		$data['error']				= urldecode($error);
		$data['landing_page_id']	= $landing_page_id;
		$data['landers']			= $landers['data'][0];	// custom landing page variables
		$data['video']				= $this->_video;		// set default video
		$data['text']				= $this->_text;			// set default text
		$data['url']				= 'http://a.hostingaccountsetup.com/special/offer/'.$landing_page_id.'/'.$this->_partner['id'];
		
		// load view
		$this->template->build('manage/landers', $data);
	}

	private function _submit()
	{
		// initialize variables
		$landing_page_id	= $this->input->post('landing_page_id');
		$partner_id 		= $this->_partner['id'];
		$video 				= $this->input->post('video');
		$text 				= $this->input->post('text');

		// if video is empty, default it
		if ( ! $video OR $video == '')
			$video 	= $this->_video;

		// generate post array
		$post 		= array(
			'landing_page_id'	=> $landing_page_id,
			'partner_id'		=> $partner_id,
			'video'				=> $video,
			'text'				=> $text
		);

		// insert/update landing page custom variables
		$update 	= $this->platform->post('partner/lander/update',$post);

		// if there was an error, return it
		if ( ! $update['success'] OR ! $update['data']):
			
			redirect('manage/landers/'.$landing_page_id.'/There was an error updating your custom variables.');
			return;

		endif;

		// if we made it here, then everything was successful
		redirect('manage/landers/'.$landing_page_id.'/Custom variables updated successfully.');

		return;
	}
}