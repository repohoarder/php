<?php

class Campaigns extends MX_Controller
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
	
	/**
	 * The API Output Type
	 * 
	 * @var string
	 */
	var $_api_output_type	= 'json';
	
    function __construct() 
    {
        parent::__construct();
        
        // load the model(s)
        $this->load->library('traffic/remote');
        $this->load->library('traffic/stats');
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

	public function add()
	{

		$params   = $this->input->post(NULL, TRUE);

		$defaults = array(
			'hits'   => 0,
			'url'    => '',
			'cat'    => 9999,
			'region' => 'BK',
			'user'   => '', 
			'pass'   => '',
			'cap'    => 0
		);

		$not_required = array(
			'user', 
			'pass',
			'cap'
		);

		$required = array_diff_key(
			$defaults,
			array_flip($not_required)
		);

		$params = array_intersect_key($params, $defaults);
		$params = array_merge($defaults, $params);
		
		$params['hits']   = intval(preg_replace("/[^0-9]/", '', str_replace('k','000',$params['hits'])));
		$params['cat']    = str_pad(intval(preg_replace("/[^0-9]/", '', $params['cat'])),4,0,STR_PAD_LEFT);
		$params['region'] = trim(strtoupper(substr($params['region'],0,2)));

		if ( ! filter_var($params['url'], FILTER_VALIDATE_URL)):

			unset($params['url']);

		endif;

		$params  = array_filter($params);
		$missing = array_diff_key($required,$params);

		if (count($missing)):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Missing or invalid fields: '.implode(', ',array_keys($missing)))
			);

			return $this->index();

		endif;

		// add new traffic campaign
		$add 	= $this->remote->create($params);

		// set response 
		$this->_response	= ( ! $add['success'])
			? $this->api->response(FALSE,$add['error'])
			: $this->api->response(TRUE,$add['data']);

		// show response
		return $this->index();
	}

	public function get_categories()
	{

		$this->load->config('taxi');
		$cats = $this->config->item('categories');

		$this->_response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array('categories' => $cats)
		);

		return $this->index();
	}

	public function revoke()
	{

		$campaign_id = $this->input->post('campaign_id');

		if ( ! $campaign_id):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Empty campaign ID'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$ref = $this->remote->refund($campaign_id);

		// set response 
		$this->_response	= ( ! $ref['success'])
			? $this->api->response(FALSE, $ref['error'])
			: $this->api->response(TRUE,  $ref['data']);

		// show response
		return $this->index();

	}

}