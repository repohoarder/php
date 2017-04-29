<?php 

class Email extends MX_Controller
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
	
    function __construct() 
    {
        parent::__construct();
        
        // load library
        $this->load->library('getresponse');
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
	
	public function test()
	{
		// initialize variables
		$list	= $this->input->post('list');
		$name	= $this->input->post('name');
		$email	= $this->input->post('email');
		$meta	= $this->input->post('meta');
		
		$list	= 'affiliate_sign_up';
		$name	= 'test name';
		$email	= 'your@email.com';
		$meta	= array(
			'field' => 'value'
		);

		// error handling
		if ( ! $list OR empty($list))
			return $this->api->error_handling($this,$this->lang->line('invalid_list_name').$this->error->code($this, __DIR__,__LINE__));
			
		if ( ! $email OR empty($email))
			return $this->api->error_handling($this,$this->lang->line('invalid_email').$this->error->code($this, __DIR__,__LINE__));
			
		// default name to Friend if not set	
		$name	= ( ! $name OR empty($name))
			? 'Friend'
			: $name;
                
		// add contact
		$this->_response	= $this->getresponse->add_contact($name,$email,$list,$meta);
		
		return $this->index();
	}

	public function execute_queued_items()
	{

		$this->load->model('queue_model');

		$items = $this->queue_model->get_due_items();
		$done  = array();

		if ( ! $items):

			$this->_response = array(
				'success' => 0,
				'error'   => array('No queued items found'),
				'data'    => array()
			);
		
			return $this->index();

		endif;

		foreach ($items as $item):

			$params  = json_decode($item['params'], TRUE);
			
			$_backup = $_POST;
			$_POST   = array_merge($_POST, $params);
			
			$resp    = Modules::run($item['api']);
			$resp    = json_decode($resp, TRUE);

			var_dump($resp);

			$_POST   = $_backup;

			if (is_null($resp) || ! isset($resp['success']) || ! $resp['success']):

				continue;

			endif;

			$done[] = $item['id'];

		endforeach;

		$affected = $this->queue_model->mark_completed($done);

		if ($affected === FALSE):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Unable to mark items as completed'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$this->_response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array('rows_affected' => $affected)
		);

		return $this->index();

	}

	public function cancel_queued_item()
	{

		$item   = $this->input->post('queue_id');
		$column = 'id';

		if ( ! $item):

			$item   = $this->input->post('order_id');
			$column = 'order_id';

		endif;

		$this->load->model('queue_model');

		$affected = $this->queue_model->mark_cancelled($item, $column);

		$this->_response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array('rows_affected' => $affected)
		);

		return $this->index();

	}

	public function queue()
	{

		$this->load->model('queue_model');

		$api    = $this->input->post('api');
		$params = $this->input->post(NULL, TRUE);

		if (isset($params['api'])):
			unset($params['api']);
		endif;

		$insert_id = $this->queue_model->store($api, $params);

		if ( ! $insert_id):

			$this->_response = array(
				'success' => 0,
				'data'    => array(),
				'error'   => array('Unable to queue item. Params: '.json_encode(array($api, $params)))
			);

			return $this->index();

		endif;

		$this->_response = array(
			'success' => 1,
			'data'    => array('queue_id' => $insert_id),
			'error'   => array()
		);

		return $this->index();

	}
	
	public function add()
	{
		// initialize variables
		$list	= $this->input->post('list');
		$name	= $this->input->post('name');
		$email	= $this->input->post('email');
		$meta	= $this->input->post('meta');
		$ip 	= $this->input->post('ip');
		
		// error handling
		if ( ! $list OR empty($list))
			return $this->api->error_handling($this,$this->lang->line('invalid_list_name').$this->error->code($this, __DIR__,__LINE__));
			
		if ( ! $email OR empty($email))
			return $this->api->error_handling($this,$this->lang->line('invalid_email').$this->error->code($this, __DIR__,__LINE__));
			
		// default name to Friend if not set	
		$name	= ( ! $name OR empty($name))
			? 'Friend'
			: $name;
		
		// add contact
		$this->_response	= $this->getresponse->add_contact($name,$email,$list,$meta,$ip);
		
		return $this->index();
	}
	
	public function move()
	{
		// initialize variables
		$list	= $this->input->post('list');
		$email	= $this->input->post('email');
		
		// error handling (list)
		if ( ! $list OR empty($list))
			return $this->api->error_handling($this,$this->lang->line('invalid_list_name').$this->error->code($this, __DIR__,__LINE__));
			
		// error handling (email)
		if ( ! $email OR empty($email))
			return $this->api->error_handling($this,$this->lang->line('invalid_email').$this->error->code($this, __DIR__,__LINE__));		
		
		
		// move the contact
		$this->_response	= $this->getresponse->move_contact($email,$list);
		
		return $this->index();
	}
	
	public function update()
	{
		// initialize variables
		$list	= $this->input->post('list');
		$name	= $this->input->post('name');
		$email	= $this->input->post('email');
		$meta	= $this->input->post('meta');
		
		// error handling (list)
		if ( ! $list OR empty($list))
			return $this->api->error_handling($this,$this->lang->line('invalid_list_name').$this->error->code($this, __DIR__,__LINE__));
			
		// error handling (email)
		if ( ! $email OR empty($email))
			return $this->api->error_handling($this,$this->lang->line('invalid_email').$this->error->code($this, __DIR__,__LINE__));	
		
		// update the contact
		$this->_response	= $this->getresponse->update_contact($name,$email,$list,$meta);
		
		return $this->index();
	}

	public function campaign($campaign)
	{
		$this->_response 	= $this->getresponse->_get_campaign_id($campaign,FALSE);

		return $this->index();
	}
	
	public function delete()
	{
		// initialize variables
		$email	= $this->input->post('email');
			
		// error handling (email)
		if ( ! $email OR empty($email))
			return $this->api->error_handling($this,$this->lang->line('invalid_email').$this->error->code($this, __DIR__,__LINE__));	
		
		// update the contact
		$this->_response	= $this->getresponse->delete_contact($email);
		
		return $this->index();
	}
	
	// quick function to parse campaigns and get correct campaign id for our config array
	public function parse_campaigns(){
		
			$this->_response	= $this->getresponse->parse_campaigns();
	}	
}