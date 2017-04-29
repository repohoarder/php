<?php

class Payouts extends MX_Controller
{
	/**
	 * The array of Partner Information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}

	public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();

		// if form was submitted, run _submit method
		if ($this->input->post())	return $this->_submit();

		// grab my data  ->  this will return last 5 payouts
		$response	= $this->platform->post('partner/statistics/lastpayout/getlastpayout',array('partner_id' => $this->_partner['id']));
		
                // set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Payout Statistics');

		// set data variables
		$data['error']	= urldecode($error);

		// load view
                $data['payoutdata'] = isset($response['data']) ? $response['data'] :  array();
        
              
		$this->template->build('statistics/payout', $data);
	}

	/**
	 * This method logs in a user and sets needed sessions
	 * @return boolean
	 */
	private function _submit()
	{
		$this->debug->show($this->input->post(),true);
		// initialize variables
		
		redirect('statistics/payouts');
	}
}
