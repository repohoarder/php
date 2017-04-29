<?php 

class Statistics extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Statistics
	 * 
	 * This method will show our sales funnel statistics
	 */
	public function index()
	{
		// initialize variables
		$data	= array();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Sales Funnel Statistics');
		
		// load view
		$this->template->build('funnel/statistics', $data);
	}
}


