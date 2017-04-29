<?php 

class Service extends MX_Controller
{
	var $_num_months;
	public function __construct()
	{
		parent::__construct();
		$this->_num_months = array(0,1,6,12,24);
	}
	
	/**
	 * This method allows the admin to view all services
	 * @return [type] [description]
	 */
	public function view($page_id=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// grab services
		$services 	= $this->_get_services($page_id);

		// set template layout to use
		$this->template->set_layout('default');
		
		// set data variables
		$data['services']	= $services;
		// load view
		$this->template->build('service/view', $data);
	}

	/**
	 * This page adds a service to the system
	 */
	public function create($service_id=false)
	{
		// initialize variables
		$data	= array();
		$data['error']	= '';
		$data['num_months'] = $this->_num_months;
		
		if($this->input->post('brand_service_id')) :
			$service_id = $this->input->post('brand_service_id');
		endif;
		
		if($this->input->post()) :
			
			$post = $this->input->post(null,true);
			$response = $this->_submit();
			//exit();
			if( ! $response['success'] ) :
				
				$data['error'] = $response['error'];
				$data['service'] = $post;
				//var_dump($response);
				else:
					
					$data['error'] = "Service Updated";
				
			endif;
			
			
		endif;
		
		if($service_id) :
			
			$service = $this->platform->post("partner/service/get_service",array('service_id'=>$service_id));
			
			if($service['success']) :
				
				$services = $service['data']['services'];
				$services['defaults'] = $service['data']['defaults'];
				$services['variations'] = $service['data']['variations'];
				
				$data['service'] = $services;
				
			endif;
			
		endif;
		
		$breadcrumb = array("Services"=>"/service/view" ,"Add New" => "/service/create");
		if($service_id) :
			$breadcrumb["Editing ID:$service_id"] = "/service/create/$service_id";
			$breadcrumb['<< Edit'] = ''; 
		endif;
		$data['breadcrumb'] = $this->menu->createBreadCrumb($breadcrumb);
		// set template layout to use
		$this->template->set_layout('default');
		
		// get brands
		$brands =  $this->platform->post('partner/brands/get');
		
		$data['brands'] = $brands['data'];
		
		// load view
		$this->template->build('service/create', $data);
	}
	private function _submit(){
		
		$post = $this->input->post(null,true);
		$num_months = $this->_num_months;
		
		$prices_defaults = array();
		
		// create array for prices defaults normal default variation
		foreach($num_months as $month) :
			
			$prices = array();
			if(isset($post['num_months'.$month])) :
				
				$prices['price'] = $post['price'.$month];
				$prices['num_months'] = $month;
				$prices['setup_fee'] = $post['setup_fee'.$month];
				$prices['variant'] = $post['variant'.$month];
				$prices['cost'] = $post['cost'.$month];
				if(isset( $post['id'.$month])) :
					$prices['id'] = $post['id'.$month];
				endif;
				$prices_defaults[] = $prices;
				
			endif;
			
		endforeach;
		
		// loop thru your non basic variations
		foreach($post['variations'] as $id) :
			$prices = array();
			if(isset($post['numonths'][$id])) :
				
				if($id == 0 && !isset($post['addit'])):
					
					continue;
				
				else:
					
					$prices['price'] = $post['prices'][$id];
					$prices['num_months'] = $post['numonths'][$id];
					$prices['setup_fee'] = $post['setup_fees'][$id];
					$prices['variant'] = $post['variants'][$id];
					$prices['cost'] = $post['costs'][$id];
					
					if($id > 0) :
						$prices['id'] = $id;
					endif;
					
					$prices_defaults[] = $prices;
					if(!in_array($post['numonths'][$id],$num_months)) :
						
						$return['success'] = false;
						$return['error'] = 'You must select use recurrance of 0,1,6,12,24.';
						return $return;
						exit(0);
						
					endif;
				endif;
			endif;
		endforeach;
		
		if(empty($prices_defaults)) :
			
			$return['success'] = false;
			$return['error'] = 'You must select a recurrence.';
			return $return;
			exit(0);
			
		endif;
		
		$post['prices_defaults'] = $prices_defaults;
		//echo "<pre>";print_r($post);
		//exit();
		$response = $this->platform->post('partner/service/create',$post);
		
		return $response;
		
	}
	private function _get_services($page_id=FALSE)
	{
		// get all services
		$services 	= $this->platform->post('sales_funnel/service/get_all',array('page_id' => $page_id));
		
		// if unable to grab the services, default it to an empty array
		if ( ! $services['success'] OR empty($services['data']))
			$services['data']	= array();

		return $services['data'];
	}

}