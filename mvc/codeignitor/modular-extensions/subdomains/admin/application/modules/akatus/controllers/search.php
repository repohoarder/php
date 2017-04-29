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
		$results	= $this->platform->post('akatus/search',array('search' => $search));
		
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Akatus Search');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['data']	= $results['data'];	// The available pages
		$data['noexitpop'] = true;
		// load view
		$this->template->build('akatus/search', $data);
	}
}
