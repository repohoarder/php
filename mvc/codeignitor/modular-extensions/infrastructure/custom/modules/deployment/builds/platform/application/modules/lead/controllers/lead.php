<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lead extends MX_Controller 
{
	
	public $_response 	  = array(
		'success' 	=> 0,
		'error'		=> array(),
		'data'		=> array()
	);
	
    public function __construct() 
    {
        parent::__construct();

        // load leads library
        $this->load->library('leads');
	}
	
	public function index()
	{
		// set data variable
		$data['output']	= $this->_response;
		
		// output
		$this->load->view('json',$data);//$this->load->view($this->load->_ci_cached_vars['_api_output_type'],$data);
	}

	/**
	 * This method adds a new lead (or adds new data to an existing one)
	 * @param varchar 	ip 						required
	 * @param int     	lead_id 				required (optionally)
	 * @param int 		lead_site_vertical_id 	required (optionally)
	 * @param int  		site_id 				required (optionally)
	 * @param int  		vertical_id 			required (optionally)
	 * @param varchar 	vertical_slug 			required (optionally)
	 * @param varchar 	email 					required (optionally)
	 * @param varchar 	phone 					required (optionally)
	 * @param varchar 	name 					required (optionally)
	 * @param varchar 	address 				optional
	 * @param varchar 	city 					optional
	 * @param varchar 	state 					optional
	 * @param varchar 	zip 					optional
	 * @param varchar 	country 				optional
	 */
	public function add()
	{
		##### TEST
		$_POST 	= array(
			'vertical_slug'		=> 'bizop',
			'key'				=> 'dsfdsf43refsdf43erdf',
			'ip'				=> '98.222.333.44',
			'email'				=> 'thompson2091+5678@gmail.com',
			'phone'				=> '(330) 555-5712',
			'name'				=> 'John M. Beeson'
		);

		// initialize variables
		$ip 	= $this->input->post('ip');
		$email 	= $this->input->post('email');
		$phone 	= $this->input->post('phone');
		$name	= $this->input->post('name');

		// make sure we got a valid IP
		if ( ! $ip)
			return $this->api->error_handling($this,$this->lang->line('required_ip').$this->error->code($this, __DIR__,__LINE__));

		// make sure we got required fields ((phone & name) OR email)
		if ( ! $email AND ( ! $phone AND ! $name))
			return $this->api->error_handling($this,$this->lang->line('required_email_phone_name').$this->error->code($this, __DIR__,__LINE__));

		// add lead
		$add 	= $this->leads->add($this->input->post());

		// set response
		$this->_response 	= (is_array($add) AND ! empty($add))
			? $this->api->response(TRUE,$add)
			: $this->api->response(FALSE,$add);

		// show response
		return $this->index();
	}

}