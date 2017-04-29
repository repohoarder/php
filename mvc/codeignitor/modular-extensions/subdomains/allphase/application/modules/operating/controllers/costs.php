<?php

class Costs extends MX_Controller
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
		// load calculate library
		$this->load->library('Expense_calculator');
	}

	public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();
		$trans = array();
		$expenses = array();
		$searchrange = array();
		
		$year = date("Y",time());
		$month = date("m",time());
		// default start and end date's
		$start	= "$year-$month-01";
		$end 	= date('Y-m-d',time());
		
		// set other dates for below
		$today = $end;
		$yesterday = date('Y-m-d',strtotime($today) - 24*60*60);
		$dateArr = array($today,$yesterday);
		
		if($this->input->post()) :
			$searchrange = $this->_submit();
		endif;
		// if this page starts to load very slowly, may need to pass this thru and return one big json array
		$methods = array(
			"transactions"=> "getexpensedata",
			"expenses" => "getpartnerexpenses",
			"cronrefunds" => "cronrefunds",
			"chargebacks" => "getchargebackdata",
			"refunds" => "getrefunddata"
			);
		
		
		$config = array(
			'partner_id' =>$this->_partner['id'],
			'startdate'	=> $start,
			'enddate'	=> $end,
			'start_date'=> $start,
			'end_date'	=> $end,
			'methods'	=> $methods
		);
	
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Operating Costs');
		
		
		// get transaction data
		$do = $this->platform->post('partner/expense/doexpensecron',$config);
		$transactions = $this->platform->post('partner/expense/get',$config);
		
		
		// get expenses data
		$partner_expenses = $this->platform->post('partner/expense/partnerexpenses',$config);
		
		if($partner_expenses['success']) :
			// set expenses based on partners id
			$expenses = $partner_expenses['data'][$this->_partner['id']];
		endif;
		
		
		
		// build view arrays 
		$trans = $this->_buildtransaction($transactions,$dateArr);
		$fees = $this->_buildfees($trans,$expenses);
	
		// get chargebacks
		$chargebacks = $this->platform->post('partner/expense/partnerchargebacks',$config);
		$chargeback = $this->_buildchargebacks($chargebacks,$dateArr,$expenses);
		
		// refresh refunds
		$runrefunds = $this->platform->post('partner/expense/dorefundcron',$config);
		// get refunds
		$refunds = $this->platform->post('partner/expense/partnerrefunds',$config);
		$refund = $this->_buildrefunds($refunds,$dateArr,$expenses);
		
		// get five9 calls
		$calls = $this->platform->post('five9/partner/calls_by_day',$config);
		$call  = $this->_buildcalls($calls,$dateArr,$expenses);
		
		// get tickets (
		$tickets = $this->_post_tickets($dateArr,$expenses);
		$freebie = $this->platform->post('partner/expense/free_trial_range',$config);
		$free = $this->_calc_freebie($freebie,$dateArr);
		// set data variables
		$data['error']		= urldecode($error);
		$data['refund']		= $refund;
		$data['chargeback']	= $chargeback;
		$data['fees']		= $fees;
		$data['breakdown']	= $trans;
		$data['calls']		= $call;
		$data['yesterday']	= $yesterday;
		$data['today']		= $today;
		$data['range']		= $searchrange;
		$data['tickets']	= $tickets;
		$data['freebie']	= $free;
		
		// load view
		$this->template->build('operating/costs', $data);
		
	}

	private function _submit(){
		$dateArr = array();
		
		$s = ! $this->input->post('startdate') ? date("Y-m-d",time()) :$this->input->post('startdate');
		$e = ! $this->input->post('enddate') ? date("Y-m-d",time()) :$this->input->post('enddate');
		
		
		$start = date("Y-m-d",strtotime($s));
		$end = date("Y-m-d",strtotime($e));
		
		$config = array(
			'partner_id' =>$this->_partner['id'],
			'startdate'	=> $start,
			'enddate'	=> $end,
			'start_date'=> $start,
			'end_date'	=> $end,
			'methods'	=> ''
		);
		
		// get transaction data
		$do = $this->platform->post('partner/expense/doexpensecron',$config);
		$transactions = $this->platform->post('partner/expense/get',$config);
		
		// get expenses data
		$partner_expenses = $this->platform->post('partner/expense/partnerexpenses',$config);
		
		if($partner_expenses['success']) :
			// set expenses based on partners id
			$expenses = $partner_expenses['data'][$this->_partner['id']];
		endif;
		
		// build view arrays 
		$trans = $this->_buildtransaction($transactions,$dateArr);
		$fees = $this->_buildfees($trans,$expenses);
		
		// get chargebacks
		$chargebacks = $this->platform->post('partner/expense/partnerchargebacks',$config);
		$chargeback = $this->_buildchargebacks($chargebacks,$dateArr,$expenses);
		
		// get refunds
		$refunds = $this->platform->post('partner/expense/partnerrefunds',$config);
		$refund = $this->_buildrefunds($refunds,$dateArr,$expenses);
		
		// get five9 calls
		$calls = $this->platform->post('five9/partner/calls_by_day',$config);
		$call  = $this->_buildcalls($calls,$dateArr,$expenses);
		
		$tickets = $this->_post_to_tickets($config);
		$ticket = $this->_calc_ticket_cost($tickets,$dateArr,$expenses);
		
		$freebie = $this->platform->post('partner/expense/free_trial_range',$config);
		$free = $this->_calc_freebie($freebie,$dateArr);
		
		// set data variables
		$data['refund']		= $refund;
		$data['chargeback']	= $chargeback;
		$data['fees']		= $fees;
		$data['breakdown']	= $trans;
		$data['daterange']	= date("m/d/y",strtotime($start)) . " - " .date("m/d/y",strtotime($end));
		$data['calls']		= $call;
		$data['tickets']	= $ticket;
		$data['freebie']	= $free;
		return $data;
	}
	/**
	 * build transactional data
	 * @param type $transactions
	 * @param type $dateArr
	 * @return int
	 */
	private function _buildtransaction($transactions,$dateArr){
		
		$trans = array();
		if($transactions['success']):
			foreach($transactions['data'] as $key=>$record) :
				$date = $record['datekey'];
				$type = $record['ptype'];
				
				// build totals array for transactions
				if( ! isset($trans['total'][$type])) :
					$trans['total'][$type]['amount']	= $record['pack_amount'] ;
					$trans['total'][$type]['cost']		= $record['cost'];
					$trans['total'][$type]['count']	= 1;
					
				else :
					$trans['total'][$type]['amount']	+= $record['pack_amount'] ;
					$trans['total'][$type]['cost']		+= $record['cost'];
					$trans['total'][$type]['count']		+= 1;
				endif;
				
				// only build today and yesterday arrays
				if (in_array($date,$dateArr)) :
					// build date range arrays.
					if(!isset($trans[$date][$type])) :
						$trans[$date][$type]['amount']		= $record['pack_amount'];
						$trans[$date][$type]['cost']		= $record['cost'];
						$trans[$date][$type]['count']		= 1;
					else :
						$trans[$date][$type]['amount']		+= $record['pack_amount'];
						$trans[$date][$type]['cost']		+= $record['cost'];
						$trans[$date][$type]['count']		+= 1;
					endif;
				endif;
				
			endforeach;
		endif;
		return $trans;
	}
	/**
	 * calculate processing and merchant fees
	 * @param type $trans
	 * @param type $expenses
	 * @return type
	 */
	private function _buildfees($trans,$expenses){
		
		$fees = array();
		// load calculate library
		
		foreach($trans as $date=>$type):
			
			foreach($trans[$date] as $k):
			
			if( ! isset($fees[$date] ) ) :
				
				$fees[$date]['revenue']			= $k['amount'];
				$fees[$date]['processingfees']  = $this->expense_calculator->calculatetype(1,$expenses[1],$k['amount'],$k['count']);
				$fees[$date]['reserves']		= $this->expense_calculator->calculatetype(5,$expenses[5],$k['amount'],$k['count']);
				
			else:
				
				$fees[$date]['revenue']			+= $k['amount'];
				$fees[$date]['processingfees']  += $this->expense_calculator->calculatetype(1,$expenses[1],$k['amount'],$k['count']);
				$fees[$date]['reserves']		+= $this->expense_calculator->calculatetype(5,$expenses[5],$k['amount'],$k['count']);
				
			endif;
			endforeach;
			
		endforeach;
		return $fees;
	}
	/**
	 * loop thru refunds and calculate
	 * 
	 * @param type $refunds
	 * @param type $dateArr
	 * @param type $refunds
	 * @return array
	 */
	private function _buildrefunds($refunds,$dateArr,$expenses){
		
		//lets build some refund arrays biaatchs!!
		$refund = array();
		if($refunds['success']):
			
			foreach($refunds['data'] as $key=>$record) :
			
				$date = date("Y-m-d",strtotime($record['date_refunded']));
				
				// build totals array for transactions
				if( ! isset($refund['total'])) :
					$refund['total']['amount']	= $record['refund_amount'] ;
					$refund['total']['count']	= 1 ;
				else :
					$refund['total']['amount']	+= $record['refund_amount'] ;
					$refund['total']['count']	+= 1 ;
				endif;
				
				// only build today and yesterday arrays
				if (in_array($date,$dateArr)) :
					// build date range arrays.
					if(!isset($refund[$date])) :
						$refund[$date]['amount']		= $record['refund_amount'];
						$refund[$date]['count']			= 1;
					else :
						$refund[$date]['amount']		+= $record['refund_amount'];
						$refund[$date]['count']			+= 1;
					endif;
				endif;
				
			endforeach;
		endif;
		
		$fees = array();
		// load calculate library
		
		foreach($refund as $date=>$k):
			
			if( ! isset($fees[$date] ) ) :			
				$fees[$date]['refund']			=  $this->expense_calculator->calculatetype(2,$expenses[2],$k['amount'],$k['count']);
			else:			
				$fees[$date]['refund']			+=  $this->expense_calculator->calculatetype(2,$expenses[2],$k['amount'],$k['count']);			
			endif;
		
		endforeach;
		
		// return both arrays if we need them
		$return['fees'] = $fees;
		$return['refunds'] = $refund;
		return $return;
	}
	private function _buildchargebacks($chargebacks,$dateArr,$expenses){
		
		//lets build some refund arrays biaatchs!!
		$chargeback = array();
		if($chargebacks['success']):
			
			foreach($chargebacks['data'] as $key=>$record) :
			
				$date = date("Y-m-d",strtotime($record['date_added']));
				
				// build totals array for transactions
				if( ! isset($chargeback['total'])) :
					$chargeback['total']['amount']	= $record['amount'] ;
					$chargeback['total']['count']	= 1 ;
				else :
					$chargeback['total']['amount']	+= $record['amount'] ;
					$chargeback['total']['count']	+= 1 ;
				endif;
				
				// only build today and yesterday arrays
				if (in_array($date,$dateArr)) :
					// build date range arrays.
					if(!isset($chargeback[$date])) :
						$chargeback[$date]['amount']		= $record['amount'];
						$chargeback[$date]['count']			= 1;
					else :
						$chargeback[$date]['amount']		+= $record['amount'];
						$chargeback[$date]['count']			+= 1;
					endif;
				endif;
				
			endforeach;
		endif;
		$fees = array();
		// load calculate library
		
		foreach($chargeback as $date=>$k):
			
			if( ! isset($fees[$date] ) ) :			
				$fees[$date]['refund']			=  $this->expense_calculator->calculatetype(3,$expenses[3],$k['amount'],$k['count']);
			else:			
				$fees[$date]['refund']			+=  $this->expense_calculator->calculatetype(3,$expenses[3],$k['amount'],$k['count']);			
			endif;
		
		endforeach;
		
		// return both arrays if we need them
		$return['fees'] = $fees;
		$return['chargebacks'] = $chargeback;
		return $return;
	}
	/**
	 * create an array for the report call data
	 * 
	 * @param type $calls
	 * @param type $dateArr
	 * @param type $expenses
	 * @return array
	 */
	private function _buildcalls($calls,$dateArr,$expenses){
		
		//lets build some refund arrays biaatchs!!
		$call = array();
		if($calls['success']):
			
			foreach($calls['data'] as $date=>$record) :
			
				$date = date("Y-m-d",strtotime($record['date']));
				
				// build totals array for transactions
				if( ! isset($call['total'])) :
					$call['total']['amount']	= 1 ;
					$call['total']['count']		= $record['num_minutes'] ;
				else :
					$call['total']['amount']	+= 1;
					$call['total']['count']	+= $record['num_minutes'] ;
				endif;
				
				// only build today and yesterday arrays
				if (in_array($date,$dateArr)) :
					// build date range arrays.
					if(!isset($call[$date])) :
						$call[$date]['amount']		= 1;
						$call[$date]['count']		= $record['num_minutes'] ;
					else :
						$call[$date]['amount']		+= 1;
						$call[$date]['count']		+= $record['num_minutes'] ;
					endif;
				endif;
				
			endforeach;
		endif;
		
		$fees = array();
		// load calculate library
		
		foreach($call as $date=>$k):
			
			if( ! isset($fees[$date] ) ) :			
				$fees[$date]['calls']			=  $this->expense_calculator->calculatetype(6,$expenses[6],$k['amount'],$k['count']);
			else:			
				$fees[$date]['calls']			+=  $this->expense_calculator->calculatetype(6,$expenses[6],$k['amount'],$k['count']);			
			endif;
		
		endforeach;
		
		// return both arrays if we need them
		$return['callfee'] = $fees;
		$return['calls'] = $call;
		return $return;
	}
	/**
	 * make 3 calls to get the 3 date ranges for tickets for the report
	 * @param type $dateArr
	 * @param type $expenses
	 * @return type
	 */
	private function _post_tickets($dateArr,$expenses){
		
		// initialize dates
		$m = date('Y',time())."-".date('m',time())."-01";
		$todaysdate = date("Y-m-d", time());
		$yesterdaysdate = date("Y-m-d", strtotime('-1 day'));
		$firstofmonth= date("Y-m-d", strtotime($m));
		
		$return = array();
		
		$today= $this->_post_to_tickets(array(
				'partner_id'	=> $this->_partner['id'],
				'start_date'	=> $todaysdate,
				'end_date'		=> $todaysdate
			)
		);
		// set todays array
		$return[$todaysdate]['replies'] = ( ! $today['data']['replies'] ) ? 0 : $today['data']['replies'];
		
		$yesterday	= $this->_post_to_tickets(array(
				'partner_id'	=> $this->_partner['id'],
				'start_date'	=> $yesterdaysdate,
				'end_date'		=> $yesterdaysdate
			)
		);
		// set yesterdays replies
		$return[$yesterdaysdate]['replies'] = ( ! $yesterday['data']['replies'] ) ? 0 : $yesterday['data']['replies'];
		$month	= $this->_post_to_tickets(array(
				'partner_id'	=> $this->_partner['id'],
				'start_date'	=> $firstofmonth,
				'end_date'		=> $todaysdate
			)
		);
		$return['total']['replies'] = ( ! $month['data']['replies'] ) ? 0 : $month['data']['replies'];
		
		$tickets = $this->_calc_ticket_cost($return, $dateArr, $expenses);
		return $tickets;
	}
	/**
	 * make platform post to get tickets
	 * @param type $config
	 * @return type array
	 */
	private function _post_to_tickets($config){
		$return= $this->platform->post(
			'partner/customer_support/total_tickets/'.$this->_partner['id'],
			$config
		);
	
		return $return;
	}
	/**
	 * Use the calculator library to calculate the cost of the tickets
	 * @param type $tickets
	 * @param type $dateArr
	 * @param type $expenses
	 * @return array
	 */
	private function _calc_ticket_cost($tickets,$dateArr,$expenses) 
	{
		$fees = array();
		foreach($tickets as $date=>$k):
			
			if(isset($k['replies'])) :
				if( ! isset($fees[$date] ) ) :
					$fees[$date]['replies']			=  $this->expense_calculator->calculatetype(7,$expenses[7],'',$k['replies']);
				else:			
					$fees[$date]['replies']			+=  $this->expense_calculator->calculatetype(7,$expenses[7],'',$k['replies']);			
				endif;
			endif;
		endforeach;
		
		// return both arrays if we need them
		$return['ticketfee'] = $fees;
		$return['tickets'] = $tickets;
		return $return;
	}
	
	private function _calc_freebie($freebie,$dateArr){
		
		//lets build some freebie arrays biaatchs!!
		$free = array();
		if($freebie['success']):
			
			foreach($freebie['data'] as $date=>$record) :
			
				$date = date("Y-m-d",strtotime($record['invdate']));
				
				// build totals array for transactions
				if( ! isset($call['total'])) :
					$free['total']['count']			= 1 ;
					$free['total']['amount']		= $record['cost'] ;
				else :
					$free['total']['count']		+= 1;
					$free['total']['amount']	+= $record['cost'] ;
				endif;
				
				// only build today and yesterday arrays
				if (in_array($date,$dateArr)) :
					// build date range arrays.
					if(!isset($free[$date])) :
						$free[$date]['count']		= 1;
						$free[$date]['amount']		= $record['cost'] ;
					else :
						$free[$date]['count']		+= 1;
						$free[$date]['amount']		+= $record['cost'] ;
					endif;
				endif;
				
			endforeach;
		endif;
		$return= $free;
		return $return;
	}
	
}