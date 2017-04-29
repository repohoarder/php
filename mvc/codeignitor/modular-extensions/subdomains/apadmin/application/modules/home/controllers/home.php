<?php 

class Home extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * This method allows the admin to view all services
	 * @return [type] [description]
	 */
	public function index()
	{
		// initialize variables
		$data	= array();

		//var_dump($this->session->all_userdata());
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('All Phase Admin Home');

		// set data array
		
		
		// load view
		$this->template->build('home/home', $data);
	}


}