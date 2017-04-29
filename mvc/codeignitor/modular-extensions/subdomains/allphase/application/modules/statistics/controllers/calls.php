<?php

class Calls extends MX_Controller
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
		$data	= array();

		// if form was submitted, run _submit method
		if ($this->input->post())	return $this->_submit();

		// grab my data
		$response	= $this->platform->post(
			'five9/partner/calls',
			array(
				'partner_id'	=> $this->_partner['id'],
				'start_date'	=> date("Y-m-d"),
				'end_date'		=> date("Y-m-d")
			)
		);
		$data['calls']['today'] = isset($response['data'][0]) ? $response['data'][0] :  array();
		$data['calls']['today']['period'] = 'Today';
		
		$response	= $this->platform->post(
			'five9/partner/calls',
			array(
				'partner_id'	=> $this->_partner['id'],
				'start_date'	=> date("Y-m-d", strtotime('-1 day')),
				'end_date'		=> date("Y-m-d", strtotime('-1 day'))
			)
		);
		$data['calls']['yesterday'] = isset($response['data'][0]) ? $response['data'][0] :  array();
		$data['calls']['yesterday']['period'] = 'Yesterday';

		$response	= $this->platform->post(
			'five9/partner/calls',
			array(
				'partner_id'	=> $this->_partner['id'],
				'start_date'	=> date("Y-m-d", strtotime('-1 month')),
				'end_date'		=> date("Y-m-d")
			)
		);
		$data['calls']['mtd'] = isset($response['data'][0]) ? $response['data'][0] :  array();
		$data['calls']['mtd']['period'] = 'MTD';
		//echo"<pre>";print_r($data['calls']);
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Call Statistics');

		// set data variables
		$data['error']       = urldecode($error);
		
		$data['partner_id']  = $this->_partner['id'];
		
        // include custom js file
        $this->template->prepend_footermeta('<script type="text/javascript" src="/resources/modules/statistics/assets/js/calls.js"></script>');
		
		$this->template->append_metadata('
			<script src="/resources/reports/highcharts/js/highcharts.js"></script>
			<script src="/resources/reports/highcharts/js/modules/exporting.js"></script>
			<script src="/resources/reports/js/highcharts_defaults.js"></script>
		');

		$this->template->build('statistics/calls', $data);
	}

	/**
	 * This method logs in a user and sets needed sessions
	 * @return boolean
	 */
	private function _submit()
	{
		$this->debug->show($this->input->post(),true);
		// initialize variables
		
		redirect('statistics/calls');
	}
}