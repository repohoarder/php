<?php 


class Video extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		// initialize variables
		$data	= array();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Welcome To '.$this->lang->line('brand_company'));
		
		// append the JS file
		$this->template->append_metadata('<script type="text/javascript" src="http://brainhost.com/welcome/js/flowplayer-3.2.6.min.js"></script>');
		
		// load view
		$this->template->build('welcome/video', $data);
	}
}
