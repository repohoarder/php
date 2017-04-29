<?php 

class Commission extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get($error=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// see if data was submitted
		if ($this->input->post())	return $this->_submit();

		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title($this->lang->line('brand_company').' Affiliate Commission(s)');
		
		// set data variables
		$data['error']		= urldecode($error);
		
		// load view
		$this->template->build('affiliate/commission', $data);
	}

	private function _submit()
	{
		// initialize variables
		$start 		= $this->input->post('start');
		$end 		= $this->input->post('end');

		// grab stats
		$stats 		= $this->platform->post('affiliate_software/commission/stats',array('start' => $start, 'end' => $end));

		// error handling
		if ( ! $stats['success'] OR empty($stats['data']))
			show_error('There was an error processing your request.  Please <a href="/affiliate/commission/get">Try Again</a>.');

		// create headers
		$csv 		= array();
		$csv[]		= array('affiliate_id','first','last','company','sales_count','revenue','refund_count','refunded_amount','refund_percentage','avg_lifetime_value');

		// iterate through all stats and add line item to CSV
		foreach ($stats['data'] AS $stat):

			// format reund and avg lifetime amounts
			if ($stat['num_refunds'] == 0):

				$stat['total_refunded'] 	= 0.00;
				$stat['refund_percent']		= 0;
				$stat['avg_lifetime_value']	= number_format(($stat['total_received'] / $stat['num_clients']),2);

			endif;

			// format average lifetime value
			$stat['avg_lifetime_value']		= number_format($stat['avg_lifetime_value'],2);

			// add line item to array
			$csv[]	= $stat;

		endforeach;

		// create CSV
		$this->csv->create('avg_lifetime_value',$csv,TRUE);
	}

}