<?php 
class New_account extends MX_Controller
{
	function index()
	{
		// set the page's title
		$this->template->title('AP Funnel');

		// set template layout to use
		$this->template->set_layout('bare');

		// Load custom js and css for this page
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/new_account/assets/css/billing.css" />');
		$this->template->append_metadata('<script src="/resources/modules/new_account/assets/js/billing.js"></script>');

		// load view
		$this->template->build('billing');

		//echo 'test';//$this->api->error_code($this, __DIR__,__LINE__);
	}
}
?>