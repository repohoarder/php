<?php 

class Partner extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * This page adds a service to the system
	 */
	public function errors()
	{
		// initialize variables
		$data	= array();

		// set template layout to use
		$this->template->set_layout('default');

		// load view
		$this->template->build('fulfillment/partner/errors', $data);
	}

	public function queue()
	{		
		// initialize variables
		$data	= array();
		
		// grab pages
		$queue	= $this->platform->post('partner/account/queue',array('where' => array('active' => 0)));
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('All Phase Partner Queue');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['queue']	= $queue['data'];	// The available pages
		
		// load view
		$this->template->build('fulfillment/partner/queue', $data);
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
		if ( ! $fulfill['success'])	$this->debug->show($fulfill,true);
		
		// we need to add subdir functionality for subdirectory on live site
		$subdir 	= @str_replace('/','',$this->config->item('subdir'));

		// return to partner queue
		redirect($subdir.'/fulfillment/partner/queue');
	}

	public function deactivate()
	{
		echo 'you are crazy for thinking this functionality was here.';
	}
}