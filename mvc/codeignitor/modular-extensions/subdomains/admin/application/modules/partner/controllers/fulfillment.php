<?php 

class Fulfillment extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		// initialize variables
		$data	= array();
		
		// grab partenr fulfillment queue errors
		$errors	= $this->platform->post('partner/fulfillment/errors');
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('All Phase Partner Fulfillment Errors');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['errors']	= $errors['data'];	// The available pages
		
		// load view
		$this->template->build('partner/fulfillment', $data);
	}

}