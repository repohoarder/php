<?php 

class Error extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->four_o_four();
	}
	
	public function four_o_four()
	{
		// set the layout to use (grabbed from bonus config for this page)
		$this->template->set_layout('bare');

		// load this page's title from the language library
		$this->template->title('Error: 404');

		// appen custom stylesheet
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/error/assets/css/four_o_four.css">');
		
		// build the page
		$this->template->build('four_o_four');
	}
}
