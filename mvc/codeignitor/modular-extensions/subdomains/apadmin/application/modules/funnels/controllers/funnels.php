<?php 

class Funnels extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * This method allows the admin to view all services
	 * @return [type] [description]
	 */
	public function view($partner_id=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// grab pages
		$funnels	= ($partner_id)? $this->_get_partner_funnels($partner_id): $this->_get_all_funnels();
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('All Phase Partner Funnels Listing');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['partner_id']	= $partner_id;
		$data['list']		= $funnels;		// The available funnels
		
		// load view
		$this->template->build('funnels/view', $data);
	}

	/**
	 * This page adds a service to the system
	 */
	public function create()
	{
		$data	= array();
		$data['error'] = '';
		$data['page']['is_default'] = 1;
		$data['page']['default_page_id'] = '31';
		
		$pages = $this->platform->post('sales_funnel/page/get_all');
		
		if($pages['success']) :
			$data['pages'] =  $pages['data'];
		endif;
		
		if($this->input->post()) :
			$data['error'] = $this->_create_funnel();
		endif;
		// set template layout to use
		$this->template->set_layout('default');

		// load view
		$this->template->build('funnels/create', $data);
	}

	public function prices($funnel_id=FALSE,$partner_id=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// grab pages
		$prices	= $this->_get_partner_funnel_prices($partner_id,$funnel_id);
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('All Phase Partner Funnel Pricings Listing');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['list']	= $prices;	// The available prices
		
		// load view
		$this->template->build('funnels/prices', $data);
	}

		/**
	 * assign funnels to a partner
	 */
	public function assign()
	{
		// initialize variables
		$data	= array();
		$partner_id = $this->input->post('partner_id') ? $this->input->post('partner_id') : 1 ;
		if($this->input->post('funnels')) :
			$this->_assign_funnel();
		endif;
		$partners = $this->platform->post('partner/account/listing');
		
		
		$funnels = $this->platform->post('partner/funnels/get_all_relation', array('partner_id'=> $partner_id));
		
		$data['funnels'] = $funnels['data'];
		
		$data['partners'] = $partners['data'];
		$data['partner_id'] = $partner_id;
		// set template layout to use
		$this->template->set_layout('default');

		// load view
		$this->template->build('funnels/assign', $data);
	}
	
	/**
	 * submit function to assign funnels to a partner
	 */
	
	private function _assign_funnel(){
		$funnels = $this->input->post('funnels');
		$partner_id = $this->input->post('partner_id');
		
		if(!$partner_id) :
			return 'No partner id selected';
		endif;
		
		$this->platform->post('partner/funnels/assign_multiple',array('funnels'=>$funnels,'partner_id'=>$partner_id));
	}

	public function statistics()
	{
		// initialize variables
		$data	= array();

		// grab stats
		$stats 	= $this->_get_funnel_stats();

		// set template layout to use
		$this->template->set_layout('default');

		// set data variables
		$data['statistics']	= $stats;

		// load view
		$this->template->build('funnels/statistics', $data);
	}

	/**
	 * This method grabs funnel statistics
	 * @return [type] [description]
	 */
	private function _get_funnel_stats()
	{
		//$stats 	= $this->platform->post('');
	}

	/**
	 * This method gets partner funnel prices
	 * @param  boolean $partner_id [description]
	 * @param  boolean $funnel_id  [description]
	 * @return [type]              [description]
	 */
	private function _get_partner_funnel_prices($partner_id=FALSE,$funnel_id=FALSE)
	{
		// get all prices
		$prices 	= $this->platform->post('partner/pricing/get_all', array('partner_id'=>$partner_id, 'funnel_id'=>$funnel_id));
		
		// if unable to grab the services, default it to an empty array
		if ( ! $prices['success'] OR empty($prices['data']))
			$prices['data']	= array();

		return $prices['data'];
	}

	private function _get_all_funnels()
	{
		// get all services
		$funnels 	= $this->platform->post('sales_funnel/funnel/get_all');
		//$this->debug->show($funnels,true);
		// if unable to grab the services, default it to an empty array
		if ( ! $funnels['success'] OR empty($funnels['data']))
			$funnels['data']	= array();

		return $funnels['data'];
	}

	private function _get_partner_funnels($partner_id=FALSE)
	{
		// get all services
		$funnels 	= $this->platform->post('partner/funnels/get', array('partner_id'=>$partner_id));
		
		// if unable to grab the services, default it to an empty array
		if ( ! $funnels['success'] OR empty($funnels['data']))
			$funnels['data']	= array();

		return $funnels['data'];
	}



}