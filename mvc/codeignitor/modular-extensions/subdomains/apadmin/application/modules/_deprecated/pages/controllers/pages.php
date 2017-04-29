<?php 

class Pages extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Mapping
	 * 
	 * This method shows the pages available for our funnels
	 */
	public function index()
	{
		// initialize variables
		$data	= array();
		
		// grab pages
		$pages	= $this->platform->post('sales_funnel/page/get_all',array());
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Sales Funnel: Show Pages');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['pages']	= $pages['data'];	// The available pages
		
		// load view
		$this->template->build('pages/pages', $data);
	}
	
	public function add()
	{
		// initialize variables
		$data	= array();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Sales Funnel: Add Page');
		
		// load view
		$this->template->build('pages/add', $data);		
	}
	
	public function edit($id=FALSE,$error=FALSE)
	{
		// if data was POSTed, then run submit function
		if($this->input->post())	return $this->_edit();

		// if no id, then redirect page to show pages
		if ( ! $id) redirect('pages');

		// initialize variables
		$data		= array();

		// grab page details
		$page 		= $this->platform->post('sales_funnel/page/get_by_id',array('id' => $id));

		// if we were unable to find page, then redirect to all pages
		if ( ! $page['success'] OR ! $page['data']) redirect('pages');

		// grab page actions
		$actions	= $this->platform->post('sales_funnel/page/get_actions',array('id' => $id));

		// if we were unable to find page actions, set actions to empty array
		if ( ! $actions['success'] OR ! $actions['data'])	$actions = array('data' => array());
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Sales Funnel: Page Actions');

		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['page']		= $page['data'];
		$data['actions']	= $actions['data'];
		$data['error']		= $error;

		// load view
		$this->template->build('pages/edit', $data);
	}

	private function _edit()
	{
		// initialize variables
		$id 	= $this->input->post('page_id');
		$name	= $this->input->post('name');

		// error handling
		if ( ! $id OR ! is_numeric($id))	redirect('pages');
		if (empty($name) OR ! $name)		redirect('pages/edit/'.$id.'/Invalid Action Name');

		$this->platform->post('sales_funnel/action/add',array('page_id' => $id, 'name' => $name));

		redirect('pages/edit/'.$id);
	}
}