<?php

class Welcome extends MX_Controller
{
	
	/**
	 * Video File
	 * 
	 * The variable holds the video file FLV URL
	 */
	var $_video_file;

	/**
	 * Description
	 * 
	 * This variable holds the page text to be displayed
	 */
	var $_description;

	/**
	 * Page Title
	 * 
	 * This is the default page title
	 */
	var $_page_title;

	public function __construct()
	{
		parent::__construct();

		// load language library
		$this->lang->load('welcome');

		// set default video URL
		$this->_video_file	= 'http://8beeda049e5d49c340dd-9e0fc90ca393afce126174d62bc6e1d5.r61.cf1.rackcdn.com/Welcome_Video_Brain_Host.flv';

		// set default page title
		$this->_page_title	= $this->lang->line('welcome_title');
	}

	public function index()
	{
		// set default template layout
		$this->template->set_layout('bare');

		// set default title
		$this->template->title($this->_page_title);

		// load javascript and video code
		$this->template->prepend_footermeta('
			<script type="text/javascript" src="/resources/brainhost/js/flowplayer/flowplayer-3.2.10.min.js"></script>
			<!-- this will install flowplayer inside previous A- tag. -->
			<script>
				flowplayer("player", "/resources/brainhost/js/flowplayer/flowplayer-3.2.5.swf", {
					plugins:  {
						controls: null,
					}
				});
			</script>
		');

		// set data variables
		$data['video']			= $this->_video_file;

		$this->template->build('welcome', $data);
	}

	public function marketing()
	{
		// set page title
		$this->_page_title	= $this->lang->line('marketing_title');

		// set video URL
		$this->_video_file	= 'http://8beeda049e5d49c340dd-9e0fc90ca393afce126174d62bc6e1d5.r61.cf1.rackcdn.com/Internet_Marketing_Videos_Brain_Host.flv';

		// show page
		return $this->index();
	}

	public function advertising()
	{
		// set page title
		$this->_page_title	= $this->lang->line('advertising_title');

		// set video URL
		$this->_video_file	= 'http://8beeda049e5d49c340dd-9e0fc90ca393afce126174d62bc6e1d5.r61.cf1.rackcdn.com/Online_Ads_Brain_Host.flv';

		// show page
		return $this->index();
	}

	public function email()
	{
		// set page title
		$this->_page_title	= $this->lang->line('email_title');

		// set video URL
		$this->_video_file	= 'http://8beeda049e5d49c340dd-9e0fc90ca393afce126174d62bc6e1d5.r61.cf1.rackcdn.com/How-to_create_an_Email_File_Brain_Host.flv';

		// show page
		return $this->index();
	}

}