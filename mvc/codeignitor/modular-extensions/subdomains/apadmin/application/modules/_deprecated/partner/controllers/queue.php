<?php 

class Queue extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		// only show queue for me
		if ( ! $this->session->userdata('_access')) exit;
		
		// initialize variables
		$data	= array();
		
		// grab pages
		$queue	= $this->platform->post('partner/account/queue',array('where' => array('active' => 0)));
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('All Phase Partner Queue');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['queue']	= $queue['data'];	// The available pages
		
		// load view
		$this->template->build('partner/queue', $data);
	}

	/**
	 * This method activates a partner (and runs fulfillment steps)
	 * @param  boolean $partner_id [description]
	 * @return [type]              [description]
	 */
	public function activate($partner_id=FALSE)
	{
		// run fulfillment for this partner
		$fulfill 	= $this->platform->post('fulfillment/fulfill/item/partner/'.$partner_id);

		// if errors, go to partner fulfillment page
		if ( ! $fulfill['success'])	redirect('partner/fulfillment');
		
		// return to partner queue
		redirect('admin/partner/queue');
	}

	/**
	 * This method deactivates a partner
	 * @param  boolean $partner_id [description]
	 * @return [type]              [description]
	 */
	public function deactivate($partner_id=FALSE)
	{
		echo 'hahaha you thought this functionality existed?  you\'re crazzzzzyyyy';
	}

	public function access()
	{
		$this->session->set_userdata('_access',TRUE);
		redirect('admin/partner/queue');
	}
}