<?php 

class Mapping extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Mapping
	 * 
	 * This method will hold the keys to the drag & drop funnel admin interface
	 */
	public function index()
	{
		// initialize variables
		$data	= array();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('The Greatest Tool Ever Built');
		
		// append css assets
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/funnel/assets/css/Plumb/draggableConnectorsDemo.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/funnel/assets/css/Plumb/jsPlumbDemo.css">');
		
		// prepend js assets
		$this->template->prepend_footermeta('<script src="/resources/modules/funnel/assets/js/Plumb/demo-helper-jquery.js"></script>');
		$this->template->prepend_footermeta('<script src="/resources/modules/funnel/assets/js/Plumb/demo-list.js"></script>');
		$this->template->prepend_footermeta('<script src="/resources/modules/funnel/assets/js/Plumb/draggableConnectorsDemo-jquery.js"></script>');
		$this->template->prepend_footermeta('<script src="/resources/modules/funnel/assets/js/Plumb/draggableConnectorsDemo.js"></script>');
		$this->template->prepend_footermeta('<script src="/resources/modules/funnel/assets/js/Plumb/jquery.jsPlumb-1.3.15-all-min.js"></script>');
		$this->template->prepend_footermeta('<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>');
		
		// load view
		$this->template->build('funnel/mapping', $data);
	}
	
	public function test()
	{
		// initialize variables
		$data	= array();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('The Greatest Tool Ever Built');
		
		// append js assets
		$this->template->append_metadata('<script src="/resources/modules/funnel/assets/js/Plumb/jquery.jsPlumb-1.3.15-all-min.js"></script>');
		$this->template->append_metadata('<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>');
		
		// load view
		$this->template->build('funnel/mapping_test', $data);
	}
	
}