<?php 
class Pages extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('footer',$this->session->userdata('_language'));
		$this->lang->load('pages',$this->session->userdata('_language'));
	}
	
	public function index()
	{
		// set the page's title
		$this->template->title('AP Funnel');

		// set template layout to use
		$this->template->set_layout('bare');

		// Load custo js and css for this page
		//$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/new_account/assets/css/style.css" />');
		//$this->template->append_metadata('<script src="/resources/modules/new_account/assets/js/script.js"></script>');

		// load view
		$this->template->build('test');

		//echo 'test';//$this->api->error_code($this, __DIR__,__LINE__);
	}

	public function terms()
	{
		// initialize variables
		$data	= array();

		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Terms and Conditions');
		
		// load view
		$this->template->build('pages/terms', $data);
	}

	public function privacy()
	{
		// initialize variables
		$data	= array();

		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Privacy Policy');
		
		// load view
		$this->template->build('pages/privacy', $data);
	}

	public function about()
	{
		// initialize variables
		$data	= array();

		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('About Us');
		
		// load view
		$this->template->build('pages/about', $data);
	}
}
?>