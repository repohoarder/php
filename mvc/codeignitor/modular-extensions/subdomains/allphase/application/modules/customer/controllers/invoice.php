<?php

class Invoice extends MX_Controller
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
	public function index($invoice=FALSE)
	{
		// initialize variables
		$data		= array();
        $userdata	= array();
        
		// get customer data for this partner
		$invoicedata 	= $this->platform->post('partner/customer/invoice',array('partner_id' => $this->_partner['id'], 'invoice' => $invoice));
               
		// if unable to grab customer data, set to empty array
		if ( ! $invoicedata['success'])	$invoicedata['data'] 	= array();
                
		// see if invoice inforamtion was set
        if(isset($invoicedata['data'][0])) :
            // grab userdata from invoice
            $userdata = $invoicedata['data'][0];
        endif;

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Invoice Data');
        
		// set data variables
		$data['lineitems']		= $invoicedata['data'];
		$data['customer']       = $userdata;

		// load view
		$this->template->build('customer/invoice', $data);
	}


        
}
