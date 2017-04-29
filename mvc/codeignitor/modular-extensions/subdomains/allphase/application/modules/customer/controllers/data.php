<?php

class Data extends MX_Controller
{
	/**
	 * The array of PArtner Information
	 * @var array
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}
	public function index($start=FALSE,$end=FALSE,$error=FALSE)
	{
		// initialize variables
		$data	= array();

                // default to grab 1 weeks worth of data
		$start  =  (  $this->input->post('start'))  ?	$this->input->post('start') 	: date("m/d/Y",strtotime("-1 weeks"));
		$end    =  (  $this->input->post('end'))    ?	$this->input->post('end') 	: date("m/d/Y");
                
		// if form was submitted, run _submit method
		if ($this->input->post()):
                    
			// run search and return results for view
			$customers = $this->_submit();
             
        else :
		
			// get customer data for this partner
			$customers 	= $this->platform->post('partner/customer/data',array('partner_id' => $this->_partner['id'], 'start' => $start, 'end' => $end));
        endif;
               
		// if unable to grab customer data, set to empty array
		if ( ! $customers['success'])	$customers['data'] 	= array();

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Customer Data');
                
		// set data variables
		$data['error']		= urldecode($error);
		$data['customers']	= $customers['data'];
		$data['start']		= $start;
		$data['end']		= $end;
		
		// load view
		$this->template->build('customer/data', $data);
	}
       
	/**
	 * This method logs in a user and sets needed sessions
	 * @return boolean
	 */
	private function _submit()
	{
		// initialize variables
		$start 	= $this->input->post('start');
		$end 	= $this->input->post('end');
        $export = $this->input->post('export');
        $exportfee = $this->input->post('exportitfee');     
		
		// get customer data for this partner
		$api = $exportfee == 0 ? 'partner/customer/data':'partner/customer/data_breakdown';
		
		$customers 	= $this->platform->post($api,array('partner_id' => $this->_partner['id'], 'start' => $start, 'end' => $end));
		
                if($export == 0 && $exportfee == 0) :
                    return $customers;
                    exit();
                else:
                    // set array to create CSV from
		
		$customers 	= $customers['data'];
                
                // get header keys and reverse array  
                $customers[] = array_keys($customers[0]);
                $customers = array_reverse($customers);
               
		// load CSV library
		$this->load->library('csv');
		
		// prompt CSV download
		$this->csv->create('customers.csv',$customers,TRUE);
                exit(0);
                endif;
	}
        
}