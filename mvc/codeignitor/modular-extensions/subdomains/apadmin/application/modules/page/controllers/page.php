<?php 

class Page extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * This method allows the admin to view all services
	 * @return [type] [description]
	 */
	public function view($funnel_id=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// grab services
		$pages 	= $this->_get_pages($funnel_id);

		// set template layout to use
		$this->template->set_layout('default');
		
		// set data variables
		$data['pages']	= $pages;

		// load view
		$this->template->build('page/view', $data);
	}

	/**
	 * This page adds a service to the system
	 */
	public function create($page_id=FALSE)
	{
		// initialize variables
		$data				= array();
		
		$data['error']	= '';
		if ($this->input->post())
		{
			$data['error']	= $this->_submit();
		}
		
		if ($page_id)
		{
			$data['page']	= $this->_get_page($page_id);
		}
		
		$data['page_types']		= array
		(
			'pre_billing',
			'post_billing',
			'billing',
			'completed'
		);
		
		$data['page_terms']		= array
		(
			0,
			1,
			6,
			12,
			24
		);
		
		$data['page_themes']	= array
		(
			'allphase_full_funnel',
			'allphase_funnel'
		);
		
		$data['page_layouts']	= array
		(
			'',
			'upsell'
		);

		// set template layout to use
		$this->template->set_layout('default');

		// load view
		$this->template->build('page/create', $data);
	}

	public function actions($page_id)
	{
		// initialize variables
		$data		= array();
		
		if ($this->input->post())
		{
			$data['error']	= $this->_submit_action($page_id);
		}
		
		// grab services
		$actions 	= $this->_get_actions($page_id);

		// set template layout to use
		$this->template->set_layout('default');
		
		// set data variables
		$data['actions']		= $actions;

		// load view
		$this->template->build('page/actions', $data);
	}
	
	public function services($page_id)
	{
		// initialize variables
		$data		= array();
		
		if ($this->input->post())
		{
			$data['error']	= $this->_submit_service($page_id);
		}
		
		// grab services
		$services		= $this->_get_services($page_id);
		$all_services 	= $this->_get_all_services($page_id);

		// set template layout to use
		$this->template->set_layout('default');
		
		// set data variables
		$data['services']		= $services;
		$data['all_services']	= $all_services;

		// load view
		$this->template->build('page/services', $data);
	}
	
	private function _get_page($page_id)
	{
		// get all page
		$page	= $this->platform->post('sales_funnel/page/get_by_id', array('id'=>$page_id));
		
		// if unable to grab the services, default it to an empty array
		if ( ! $page['success'] OR empty($page['data']))
			$page['data']	= array();

		return $page['data'];
	}

	private function _get_pages()
	{
		// get all page
		$pages 	= $this->platform->post('sales_funnel/page/get_all');
		
		// if unable to grab the services, default it to an empty array
		if ( ! $pages['success'] OR empty($pages['data']))
			$pages['data']	= array();

		return $pages['data'];
	}
	
	private function _get_actions($page_id)
	{
		// get all page
		$actions = $this->platform->post('sales_funnel/action/get_by_page', array('page_id'=>$page_id));
		
		// if unable to grab the services, default it to an empty array
		if ( ! $actions['success'] OR empty($actions['data']))
			$actions['data']	= array();

		return $actions['data'];
	}
	
	private function _get_services($page_id)
	{
		// get all page
		$services = $this->platform->post('sales_funnel/service/get_by_page', array('page_id'=>$page_id));
		
		// if unable to grab the services, default it to an empty array
		if ( ! $services['success'] OR empty($services['data']))
			$services['data']	= array();

		return $services['data'];
	}
	
	private function _get_all_services($page_id)
	{
		// get all page
		$services = $this->platform->post('sales_funnel/service/get_all', array('brand_id'=>4));
		
		// if unable to grab the services, default it to an empty array
		if ( ! $services['success'] OR empty($services['data']))
			$services['data']	= array();

		return $services['data'];
	}
	
	private function _submit()
	{
		$post		= $this->input->post();
		
		// set theme if not set on brainhost HACKKKK IT
		if(!isset($post['theme'])) :
			$post['theme'] = '';
		endif;
		
		$response	= $this->platform->post('sales_funnel/page/add', $post);
		
		if($response['success']) :
			return 'Successfully added';
		endif;
		$err='';
		if(is_array($response['error'])):
			foreach($response['error'] as $k=>$v) :
				$err .=  $k." = ". $v;
			endforeach;
			return $err;
		endif;
		return $response['error'];
	}
	
	private function _submit_action($page_id)
	{
		$post			= $this->input->post();
		
		$action			= array(
			'page_id'	=> $page_id,
			'name'		=> $post['name']
		);
		
		$response		= $this->platform->post('sales_funnel/action/add', $action);
		
		return $response['error'];
	}
	
	private function _submit_service($page_id)
	{
		$post				= $this->input->post();
		
		$service			= array(
			'page_id'		=> $page_id,
			'service_id'	=> $post['service_id']
		);
		
		$response			= $this->platform->post('sales_funnel/service/add', $service);
		
		return $response['error'];
	}
}