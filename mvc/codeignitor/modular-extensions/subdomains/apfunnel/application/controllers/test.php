<?php 
class Test extends CI_Controller
{
	function index()
	{
		// set the page's title
		$this->template->title('AP Funnel');

		// set template layout to use
		$this->template->set_layout('default');

		// load view
		$this->template->build('test');

		//echo 'test';//$this->api->error_code($this, __DIR__,__LINE__);
	}
}
?>