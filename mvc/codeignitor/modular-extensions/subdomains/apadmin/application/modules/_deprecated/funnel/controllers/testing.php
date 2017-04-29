<?php 

class Testing extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Testing
	 * 
	 * This method will administer our split testing features
	 */
	public function index()
	{
		// initialize variables
		$data	= array();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Funnel Split Testing Software');
		
		// load view
		$this->template->build('funnel/testing', $data);
	}
}


