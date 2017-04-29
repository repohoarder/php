<?php

class Update_default_pricing extends MX_Controller
{
	
	var $_response	= array(
		'success'	=> FALSE,
		'error'		=> '',
		'data'		=> ''
	);

	public function __construct()
	{
		     
	}

	/**
	 * This method returns the response json encoded
	 * @return json
	 */
	public function index()
	{
		$post = array(
			'id' =>  $this->input->get('id'),
			'price' => $this->input->get('price'),
			'setup_fee' => $this->input->get('setup_fee') ,
			'cost'		=>  $this->input->get('cost')
		);
                // prepend the $ sign to the fields
        $this->_response = $this->platform->post('partner/pricing/update_defaults',$post);
              
	    echo json_encode($this->_response);
		return;
	}

}
