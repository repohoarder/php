<?php 

class Affiliate extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function pricing($partner_id=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// grab 
		//$queue	= $this->platform->post('partner/account/listing');

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('All Phase Partner Affiliate Pricing');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/partner/assets/css/listing.css">');
		
		// set data variables
		//$data['list']	= $queue['data'];	// The available pages
		
		// load view
		$this->template->build('partner/affiliate/pricing', $data);
	}

}