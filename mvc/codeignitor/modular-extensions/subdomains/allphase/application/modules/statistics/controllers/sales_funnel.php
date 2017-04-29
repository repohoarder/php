<?php

class Sales_funnel extends MX_Controller
{
	/**
	 * The array of PArtner Information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}

	public function index($start = FALSE, $end = FALSE, $error = FALSE)
	{
		// initialize variables
		$data		= array();
		$stats 		= array('data' => array());

		// default start and end date's
		if ( ! $start)	$start 	= date('Y-m-d');
		if ( ! $end)	$end 	= date('Y-m-d');

		// if form was submitted, run _submit method
		if ($this->input->post())	return $this->_submit();

		// grab default funnel for this partner
		$default 	= $this->platform->post('sales_funnel/version/get_default',array('partner_id' => $this->_partner['id']));

		// make sure we got a valid default funnel
		if ($default['success'] AND isset($default['data'][0]['funnel_id'])):

			// set funnel id
			$funnel_id 	= $default['data'][0]['funnel_id'];

			// grab my data
			$stats		= $this->platform->post('partner/statistics/funnel/hits_actions/'.$this->_partner['id'].'/'.$funnel_id.'/'.$start.'/'.$end, array());

		endif; 	// end making sure we grabbed a valid default funnel


		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Sales Funnel Statistics');

		// set data variables
		$data['error']      = urldecode($error);
		$data['stats']		= $stats['data'];

		$this->template->build('statistics/sales_funnel', $data);
	}

	/**
	 * This method logs in a user and sets needed sessions
	 * @return boolean
	 */
	private function _submit()
	{
		$this->debug->show($this->input->post(),true);
		// initialize variables
		
		redirect('statistics/sales');
	}
}