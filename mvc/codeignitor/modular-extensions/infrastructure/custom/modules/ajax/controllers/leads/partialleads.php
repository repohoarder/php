<?php

class Partialleads extends MX_Controller
{
	/**
	 * The array that holds the response of the API
	 * @var array
	 */
        var $_leadid;
	var $_response	= array(
		'success'	=> FALSE,
		'error'		=> '',
		'data'		=> ''
	);

	public function __construct()
	{
		parent::__construct();
                if(  ! $this->session->userdata('leadid')) :
                    $this->session->set_userdata('leadid',0);
                endif;
                $this->_leadid	= $this->session->userdata('leadid');
                
	}

	/**
	 * This method returns the response json encoded
	 * @return json
	 */
	public function index()
	{
            
                // build post array  this array needs to have keys that are EQUAL to the database fields.
                // if they are not set to the correct fields they will not be inserted
                $post = array(
                    'signupid' => isset($this->_leadid)? $this->_leadid  : 0,
                    'updatefields' => $this->input->post()   
                );
                
               
                //the response will return only an array data['id'] if successfull
                $this->_response = $this->platform->post('leads/partial/newlead',$post);
                
                // set the lead id into the session if it is not set.
                if( empty($this->_leadid) ) :
                    if(isset($this->_response['data']['id'])) :
                         $this->session->set_userdata('leadid',$this->_response['data']['id']);
                    endif;
                endif;
                
                 // if this field is email send it to get response
                if(  $this->input->post('email')) :
                    
                    $params['email'] = $this->input->post('email');
                    $params['list'] = 'new_partials';
                    $emailadd = $this->platform->post('esp/add',$params);
                    
                endif;
                
	        echo json_encode($this->_response);
		return;
	}

}
