<?php 

class Pricing extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Maybe the index page can have some pretty little buttons of stuff to do with pricing
	 */
	public function index()
	{
		// initialize variables
		$data	= array();
		
		// grab pages
		//$queue	= $this->platform->post('partner/account/queue',array('where' => array('active' => 0)));
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Pricing');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['queue']	= '';	// The available pages
		
		// load view
		$this->template->build('partner/queue', $data);
	}
	public function edit()
	{
		// initialize variables
		$data	= array();
		
		$brands = $this->platform->post('partner/brands/get',array());
		$brand_id = ! $this->input->post('brand_id') ? 4 : $this->input->post('brand_id');
		// grab pages
		$pricing	= $this->platform->post('partner/pricing/get_defaults',array('brand_id'=>$brand_id));
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Pricing');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		$this->template->append_metadata('<script type="text/javascript" src="/resources/modules/pages/assets/js/script.js"></script>');

		// set data variables
		$data['pricing']	= $pricing['data'];	// The available pages
		$data['brands']		= $brands['data'];
		$data['brand_id']	= $brand_id;
		$data['noexitpop']	= true;
		// load view
		$this->template->build('partner/editpricing', $data);
	}
}

