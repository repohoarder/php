<?php

class Visitors extends MX_Controller
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
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date')   
        );
        
        // get visitors
        $visitors = $this->platform->post('partner/statistics/visitor/getvisitorsbydate',$post);

        // get sales
        $sales = $this->platform->post('partner/statistics/sale/getsalesbydate',$post);
        
        // merge the 2 arrays and calculate epc and conversion
        $json['visitors']    = $visitors['data']['visitors'];
        $json['visits']      = $visitors['data']['visits'];
        $json['conversion']  = ( $visitors['data']['visitors'] > 0 ) ? round( ($sales['data']['count'] /  $visitors['data']['visitors']) , 2) ."%" : 0 ."%" ;
        $json['epc']         = ( $visitors['data']['visitors'] > 0 )  ? "$".round(($sales['data']['total'] / $visitors['data']['visitors']) ,2) : "$0.00" ;
        $json['total']       = '$'.number_format($sales['data']['total'],2);
        $json['count']       = $sales['data']['count'];
        
        // set variable for values
        $this->_response['success'] = TRUE;
        $this->_response['data'] = $json;
        echo json_encode($this->_response);
		return;
	}

}
