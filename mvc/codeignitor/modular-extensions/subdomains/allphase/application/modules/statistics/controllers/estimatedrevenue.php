<?php

class Estimatedrevenue extends MX_Controller
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

	public function index( $error = FALSE)
	{
		// initialize variables
		$data	= array();
		$plans = array();
		$extras = array();
		
		$plans['Monthly'] = 0.00;
		$plans['6 Months'] = 0.00;
		$plans['Yearly'] = 0.00;
		// if form was submitted, run _submit method
		if ($this->input->post())	return $this->_submit();
		
		$periods = array(
				1 => "Monthly",
				6 => "6 Month",
				12 => "Yearly",
				24 => "2 Years"
		);
		// grab my current all plan recurring data
		$response	= $this->platform->post('partner/statistics/recurringrevenue/estimated',array('partner_id' => $this->_partner['id']));
		// get averages of all plan data
		$average	= $this->platform->post('partner/statistics/recurringrevenue/averageprices',array('partner_id' => $this->_partner['id']));
		// get refund percent of partner
		$percent = $this->platform->post('partner/partnerrefunds/getpartnerpercent',array('partner_id' => $this->_partner['id']));
		$refundpercent = $percent['data'];
		
		
		
		
		// calculate
		if($average['success']) :
			foreach($average['data'] as $k=>$record):
				$estimatedrefunds = round( $record['average'] * $refundpercent);
				$period = $periods[$record['period']];
				if(isset($extras[$record['period']])) :
					
					$extras[$period] += ( $record['average'] - $estimatedrefunds )  * $record['period'] * $record['amount'];
				else:
					$extras[$period] = ( $record['average'] - $estimatedrefunds ) * $record['period'] * $record['amount'];
				endif;
			endforeach;
		endif;
		
		
		if($response['success']) :
			$rev = $response['data'];
			foreach($rev as $k=>$record) :
				
				$period = $periods[$record['period']];
				$month = $record['signup_month'];
				$amount = $record['amount'];
				$count = $record['sales'];
				
				if( ! isset($plans[$period])) :
					$plans[$period]  = $record['amount'] * $record['sales'];
				else:
					$plans[$period]  += $record['amount'] * $record['sales'];
				endif;
				
			endforeach;
		endif;
		
		foreach($plans as $period=>$amount):
			if(isset($extras[$period])) :
				$plans[$period] += $extras[$period]; 
			endif;
		endforeach;
		//echo "<pre>"; print_r($extras);echo"</pre>";
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Sales Statistics');

		// set data variables
		$data['error']       = urldecode($error);
		
		$data['partner_id']  = $this->_partner['id'];
		
		// load view
		$data['plans'] = $plans ;
		
		

		$this->template->build('statistics/revenue', $data);
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
