<?php 

class Search extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		// initialize variables
		$data	= array();
		
		$search = $this->input->post('search') ?  $this->input->post('search') : '';
		// grab pages
		$results	= $this->platform->post('custom_merchant/process/search',array('search' => $search));
		
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Akatus Search');
		
		
		// set data variables
		$data['data']	= $results['data'];	// The available pages
		$data['noexitpop'] = true;
		// load view
		$this->template->build('akatus/search', $data);
	}
}

