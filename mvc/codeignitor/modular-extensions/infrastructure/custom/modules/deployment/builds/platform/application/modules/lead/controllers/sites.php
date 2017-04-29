<?php

class Verticals extends MX_Controller
{
	/**
	 * The return value of the API
	 * 
	 * @var array
	 */
	var $_response 	= array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);

	public function __construct()
	{
		parent::__construct();
                
         // load config file for database
        $this->load->config('lead');
                
		// load pricings model
		$this->load->model('leads/admin');
	}

	/**
	 * Index
	 * 
	 * This method returns the output as json
	 * 
	 * @access	public
	 * 
	 * @example	index() 
	 * 
	 * @return	json
	 */
	public function index(){
		
		// set data variable
		$data['output']	= $this->_response;
		
		// output
		$this->load->view($this->load->_ci_cached_vars['_api_output_type'],$data);
	}
    public function add(){
            
          
        $url = $this->input->post('url');
		$key = $this->input->post('key');
		$key_salt = $this->input->post('key_salt');
		
		
		
		if( empty($url) ) :
			return $this->api->error_handling($this,$this->lang->line('invalid_domain').$this->error->code($this, __DIR__,__LINE__));
		endif;
		
		if( empty($key) ) :
			return $this->api->error_handling($this,$this->lang->line('invalid_api_key').$this->error->code($this, __DIR__,__LINE__));
		endif;
		
		if( empty($key_salt) ) :
			return $this->api->error_handling($this,$this->lang->line('invalid_salt').$this->error->code($this, __DIR__,__LINE__));
		endif;
		
		$details = $this->admin->insert_site($this->input->post(null,true));
		// set response
		$this->_response	= (is_numeric($details) AND ! empty($details))
			? $this->api->response(TRUE,$details)
			: $this->api->response(FALSE,$details);

		return $this->index();
		
      }
	  
	  public function edit() {
		  
		$url = $this->input->post('url');
		$key = $this->input->post('key');
		$key_salt = $this->input->post('key_salt');
		$site_id = $this->input->post('site_id');
		
		if( empty($site_id) ) :
			return $this->api->error_handling($this,$this->lang->line('invalid_id').$this->error->code($this, __DIR__,__LINE__));
		endif;
		
		if( empty($url) ) :
			return $this->api->error_handling($this,$this->lang->line('invalid_domain').$this->error->code($this, __DIR__,__LINE__));
		endif;
		
		if( empty($key) ) :
			return $this->api->error_handling($this,$this->lang->line('invalid_api_key').$this->error->code($this, __DIR__,__LINE__));
		endif;
		
		if( empty($key_salt) ) :
			return $this->api->error_handling($this,$this->lang->line('invalid_salt').$this->error->code($this, __DIR__,__LINE__));
		endif;
		
		$details = $this->admin->edit_site($this->input->post(null,true));
		// set response
		$this->_response	= ($details === true)
			? $this->api->response(TRUE,$details)
			: $this->api->response(FALSE,$details);

		return $this->index();
	  }
        
       


}


