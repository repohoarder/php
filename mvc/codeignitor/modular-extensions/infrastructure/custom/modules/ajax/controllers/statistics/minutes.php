<?php

class Calls extends MX_Controller
{
	/**
	 * The array that holds the response of the API
	 * @var array
	 */
        var $_partner;
        var $_partner_id;
	var $_response	= array(
		'success'	=> FALSE,
		'error'		=> '',
		'data'		=> ''
	);

	public function __construct()
	{
		parent::__construct();
                $this->_partner	= $this->session->userdata('partner');
                $this->_partner_id = $this->_partner['id'];
	}

	/**
	 * This method returns the response json encoded
	 * @return json
	 */
	public function index()
	{
                $post = array(
                    'partner_id' => $this->_partner_id,
                    'start_date' => date("Y-m-d", strtotime($this->input->get('start_date'))),
                    'end_date' => date("Y-m-d", strtotime($this->input->get('end_date')))   
                );
                // prepend the $ sign to the fields
                $this->_response = $this->platform->post('partner/customer_support/total_calls/'.$this->_partner_id,$post);
                isset($this->_response['data']['calls']) ?
                $this->_response['data']['calls'] = $this->_response['data']['calls']
                 :'';
                isset($this->_response['data']['minutes']) ?
                $this->_response['data']['minutes'] = $this->_response['data']['minutes']
                 :'';
	        echo json_encode($this->_response);
		return;
	}

}