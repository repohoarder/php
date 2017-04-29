<?php 

class Paypal extends MX_Controller
{
	/**
	 * Decline Page
	 * 
	 * @var declined
	 */
	var $declined;
	
	/**
	 * Thank You Page
	 * 
	 * @var completed
	 */
	var $completed;
	
	/**
	 * MCSD Page (free website setup)
	 * 
	 * @var mcsd
	 */
	var $mcsd;
	
	/**
	 * Percentage
	 * 
	 * @var percentage
	 */
	var $percentage;
	
	protected $_paypal_defaults;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->config('paypal_form');

		$this->lang->load('paypal_offer');
		$this->lang->load('paypal_loading');
		$this->lang->load('paypal_checkout');

		//$this->load->lang('paypal_declined');

		$this->_paypal_defaults = $this->config->item('paypal_form_production');
		//$this->_paypal_defaults = $this->config->item('paypal_form_sandbox');

		// billing/completed/sale/order/339297/paypal

		// set default thank you and decline pages
		$this->declined		= 'billing/declined';
		$this->completed	= 'completed/sale/';
		
		// initialize percentage to give credit for
		$this->percentage	= $this->config->item('paypal_discount_percentage');
	}

	function show_records()
	{

		$data['all_columns'] = array(
			'id',
			'order_id',
			'date_offered_paypal',
			'date_sent_to_paypal',
			'invoice_id',
			'hosting',
			'period',
			'total',
			'paid',
			'cancelled',
			'returned',
			'cron_submitted',
			'last_checked',
			'error_count',
			'error_text',
			'completed',
			'ipn_payment_date',
			'ipn_subscriber_id',
			'ipn_email',
			'ipn_payer_id',
			'ipn_response'
		);

		$data['sortable_columns']  = array(
			'id',
			'order_id',
			'date_offered_paypal',
			'date_sent_to_paypal',
			'invoice_id',
			'hosting',
			'period',
			'total',
			'paid',
			'cancelled',
			'returned',
			'cron_submitted',
			'last_checked',
			'error_count',
			'completed',
			'ipn_payment_date',
			'ipn_email',
		);

		$data['searchable_columns'] = array(
			'order_id',
			'cancelled',
			'paid',
			'date_offered_paypal',
			'returned',
			'completed',
			'date_sent_to_paypal',
			'invoice_id',
			'cron_submitted',
			'period',
			'total',
			'hosting',
			'ipn_payment_date',
			'ipn_email',
			'last_checked'
		);

		$data['operators'] = array(
			'equals'      => array(
				'operator' => '=',
				'use_val2' => FALSE,
				'text'     => 'is equal to',
			),
			'greaterthan' => array(
				'operator' => '>',
				'use_val2' => FALSE,
				'text'     => 'is greater than',
			),
			'lessthan'    =>  array(
				'operator' => '<',
				'use_val2' => FALSE,
				'text'     => 'is less than',
			),
			'notequal'    =>   array(
				'operator' => '!=',
				'use_val2' => FALSE,
				'text'     => 'is not equal to',
			),
			'between'     =>    array(
				'operator' => 'BETWEEN',
				'use_val2' => TRUE,
				'text'     => 'is between'
			)
		);




		$defaults = array(
			'offset'        => 0,
			'num_rows'      => 30,
			'column'        => 'date_offered_paypal',
			'sort'          => 'DESC',
			'search'        => 'ipn_payment_date',
			'operation'     => 'between',
			'search_value'  => '',
			'search_value2' => ''
		);

		$params = $defaults;

		foreach ($params as $key => $value):

			if ($this->input->post($key)):

				$params[$key] = $this->input->post($key);

			endif;

		endforeach;

		$data['params']   = $params;
		$data['response'] = $this->platform->post('ubersmith/paypal/get_records', $params);


		if ($this->input->post('output') == 'csv'):

			$this->load->view('records_csv', $data);
			return;

		endif;

		$this->load->view('records_table', $data);
	}

	function ipn()
	{
		$this->load->library('curl');

		$uber_ipn = $this->config->item('paypal_uber_ipn');
		$post     = $this->input->post(NULL, TRUE);

		@mail('travis.loudin@brainhost.com','attempt IPN [BH]',json_encode($post)."\n\n".json_encode($uber_ipn));

		if ( ! $post):

			$post = array();

		endif;

		$custom = (isset($post['custom']) ? json_decode($post['custom'], TRUE) : array());

		$plat_response = $this->platform->post(
			'ubersmith/paypal/record_ipn',
			array(
				'ipn_post' => $post
			)
		);


		$arr = array(
			'plat_response' => $plat_response,
			'post'          => $post
		);

		if ( ! $plat_response['success']):

			@mail('travis.loudin@brainhost.com','failed to record IPN [BH]',json_encode($arr));
			var_dump($arr);
			return;

		endif;

		if ($post['txn_type'] != 'subscr_payment'):

			@mail('travis.loudin@brainhost.com','non-payment IPN [BH]',json_encode($arr));
			var_dump($arr);
			return;

		endif; 
		
		$response  = $this->curl->post($uber_ipn, $post);
		
		$completed = $this->_complete_order($custom['paypal_id'], $custom['order_id']);
		
		$output    = array(			
			'url'               => $uber_ipn,
			'post'              => $post,
			'complete_order'    => $completed,
			'platform_response' => $plat_response,
			'response'          => $response,
		);

		$body  = 'Order: http://my.brainhost.com/admin/ordermgr/order_view.php?order_id='    . $custom['order_id']."\n";
		$body .= 'Invoice: http://my.brainhost.com/admin/clientmgr/popup_viewinv.php?invid=' . $custom['invoice_id']."\n\n\n";
		$body .= 'Response: ' . json_encode($post);

		@mail(implode(',',$this->config->item('paypal_ipn_notify')),'PP Payment received [Brain Host]',$body);

		var_dump($output);
	
	}

	/**
	 * Index
	 * 
	 * Invalid use of this controller - redirect to decline page
	 */
	public function index()
	{
		return $this->returned(FALSE);
	}

	function test_credit($order_id)
	{
		
		$response = $this->platform->post('ubersmith/order/get',array('order_id' => $order_id));
		
		$order    = $response['data'];

		var_dump($order['total']);
		var_dump($this->_calculate_credit_amount_total($order));

	}
	
	/**
	 * Offer
	 * 
	 * This method shows the PayPal Decline Page
	 */
	public function offer($order_id=FALSE)
	{
		// if no order id is passed, then show original decline page
		if ( ! $order_id) return $this->returned(FALSE);
				
		// initialize variables
		$data	= array();
		
		// verify this order id hasn't already seen this page
		$paypal			= $this->platform->post('ubersmith/paypal/get_info_by_order',array('order_id' => $order_id));
		
		// if user is already in table, redirect to decline
		if ($paypal['success'] === FALSE) return $this->returned(FALSE);
		
		// insert into paypal table
		$insert			= $this->platform->post('ubersmith/paypal/insert',array('order_id' => $order_id));
		
		// grab paypal id and send to next page
		if ($insert['success'] === FALSE) return $this->returned(FALSE);
		
		// set paypal_id
		@$paypal_id		= $insert['data']['paypal_id'];
		
		// verify we got a paypal id
		if ( ! $paypal_id OR ! is_numeric($paypal_id)) return $this->returned(FALSE);
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title($this->lang->line('paypal_title'));
		
		// append the CSS file
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/declined_paypal.css" type="text/css" />');
		if ($this->session->userdata('_language')=='portuguese')
		{
			$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/lang/portuguese/declined_paypal.css" type="text/css" />');
		}
		
		// set data variables
		$data['order_id']	= $order_id;
		$data['paypal_id']	= $paypal_id;
		$data['language']	= $this->session->userdata('_language');
		if (!$data['language'])
		{
			$data['language'] = 'english';
		}
		
		// load view
		$this->template->build('paypal/declined', $data);
	}
	
	/**
	 * Loading
	 * 
	 * This method sets up the user to be redirected to paypal
	 */
	public function loading($order_id=FALSE,$paypal_id=FALSE)
	{
		// if no order attached, then send to decline page
		if ($order_id === FALSE OR ! is_numeric($order_id)) return $this->returned(FALSE);
		
		// if we have no paypal id, then show them the paypal decline page
		if ($paypal_id === FALSE OR ! is_numeric($paypal_id)) return $this->declined($order_id);
		
		// grab order details
		$response		= $this->platform->post('ubersmith/order/get',array('order_id' => $order_id));
		
		// if we were unable to grab order info, redirect to decline page
		if ($response['success'] == FALSE) return $this->returned(FALSE);
		
		// initialize variables
		$client_id		= $response['data']['client_id'];
		$order			= $response['data']['info'];		// This is the order info
		$invoice_id		= $order['invid'];
		$affiliate_id	= $order['affiliate_id'];
		$offer_id		= $order['offer_id'];
		$build_type		= $order['wordpress_installation'];
		
		// verify this order hasn't already seen this page
		$paypal			= $this->platform->post('ubersmith/paypal/get_info_by_order',array('order_id' => $order_id));
		
		// if user has already been offered discount, then show default decline page
		if ($paypal['data']['info']['date_sent_to_paypal'] != '0000-00-00 00:00:00' OR $paypal['success'] == FALSE) return $this->returned(FALSE);
		
		// disregard current invoice
		$disregard		= $this->platform->post('ubersmith/invoice/disregard/'.$client_id.'/'.$invoice_id,array());
		
		// perform invoice disregard error checking?
		if ($disregard['success'] == FALSE) return $this->returned(FALSE);
		
		// set all services (except hosting) to one-time fee
		$this->_reset_invoice_packs($invoice_id);

		// get credits from initial invoice
		// reapply to client
		$invoice_info	= $this->platform->post('ubersmith/invoice/get/invoice_id/'.$invoice_id,array());
		if ($invoice_info['success']):

			$credits = (isset($invoice_info['data']['credits'])) ? $invoice_info['data']['credits'] : array();

			foreach ($credits as $credit):

				$new_credit	= $this->platform->post(
					'ubersmith/credit/add',
					array( 
						'client_id' => $client_id, 
						'credit'    => $credit['amount'], 
						'type'      => 'comp', 
						'reason'    => $credit['reason'], 
						'comment'   => $credit['reason'].': '.__FILE__
					)
				);

			endforeach;

		endif;


		
		// determine credit amount
		$credit			= $this->_calculate_credit_amount_total($order);
		
		// if we have no credit amount, then we are unable to add credit
		if ($credit == FALSE)	return $this->returned(FALSE);
		
		// add a credit to the client
		$update			= $this->platform->post(
			'ubersmith/credit/add',
			array(
				'client_id' => $client_id, 
				'credit'    => $credit, 
				'type'      => 'comp', 
				'reason'    => $this->lang->line('paypal_credit'), 
				'comment'   => 'Paypal 15% OFF: '.__FILE__
			)
		);


		// error handling updating order
		if ($update['success'] == FALSE)	return $this->returned(FALSE);
		
		// generate new invoice
		$invoice		= $this->platform->post('ubersmith/invoice/generate/'.$client_id,array());
		
		// error handling generate invoice
		if ($invoice['success'] == FALSE)	return $this->returned(FALSE);
				
		// set invoice id
		$invoice_id		= $invoice['data']['invid'];
		
		// add affiliate tracking to backup data (meta) & remove affiliate tracking
		$post	= array(
			'client_id'							=> $client_id,
			'meta_affiliate_id'					=> '991',
			'meta_ppc_backup_affiliate_data'	=> 'ID: '.$affiliate_id.', Build: '.$build_type.', Offer: '.$offer_id
		);
		
		$this->platform->post('ubersmith/client/update',$post);
		
		// show the paypal loading form
		return $this->_load_form($response['data'],$invoice_id,$paypal_id);
	}
	
	/**
	 * Returned
	 * 
	 * This method determines whether the PayPal payment was successful or not and redirects the user to the proper place
	 */
	public function returned($success=TRUE,$paypal_id=FALSE,$order_id=FALSE, $invoice_id = FALSE)
	{	
		// send CS an email saying there was a paypal transaction attempt 
		//$this->_send_cs_email($success,$paypal_id);
		
		
		if ($paypal_id AND $order_id):
		
			// mark returned in paypal
			$this->platform->post('ubersmith/paypal/mark_returned',array('paypal_id' => $paypal_id, 'cancelled' => !$success));
			
			// validate payment
			$this->platform->post('ubersmith/paypal/is_paid',array('paypal_id' => $paypal_id));
			
			// verify successful payment
			//if ($paid['success'] == FALSE OR $paid['data']['paid'] == FALSE) return $this->returned(FALSE);
		
		endif;
		
		// redirect user based on success or not
		return ($success)
			? $this->_success($paypal_id,$order_id,$invoice_id)
			: $this->_declined($paypal_id);
	}
	
	private function _declined($paypal_id)
	{		
		redirect($this->declined);
		return;
	}
	
	private function _success($paypal_id=FALSE,$order_id=FALSE, $invoice_id = FALSE)
	{		
		/*
		if ($paypal_id AND $order_id):
			// update paypal table
			$this->_complete_order($paypal_id, $order_id);
		endif;
		*/
		
		// redirect user
		redirect($this->completed.'invoice/'.$invoice_id.'/paypal');
		return;
	}

	private function _get_invoice_packs($invoice_id)
	{

		$invoice_info	= $this->platform->post('ubersmith/invoice/get/invoice_id/'.$invoice_id,array());

		if ( ! $invoice_info['success']):

			return FALSE;

		endif;

		$packs = array();

		if (isset($invoice_info['data']['packs'])):

			foreach ($invoice_info['data']['packs'] as $pack):

				if ( ! $pack['packid']):

					continue;

				endif;

				$pack_info = $this->platform->post('ubersmith/package/get/pack_id/'.$pack['packid']);

				if ( ! $pack_info['success']):

					continue;

				endif;

				$packs[] = array_shift($pack_info['data']);

			endforeach;

		endif;

		return $packs;

	}

	private function _get_hosting_plan_ids()
	{

		$plans = array(30,6,5,42,42,46,41,40,39,38,37,36,35,34,33,32,10,9,8,7,6,5,61,62,63,77,80,81);

		//$remote = json_decode(@file_get_contents('https://orders.brainhost.com/hosting_plan_json_list.php'), TRUE);

		if (is_array($remote) && count($remote)):

			$plans = $remote;

		endif;

		return $plans;

	}

	private function _reset_invoice_packs($invoice_id)
	{

		$packs = $this->_get_invoice_packs($invoice_id);

		if ( ! $packs || ! is_array($packs) || empty($packs)):

			return FALSE;

		endif;

		$hosting_plans = $this->_get_hosting_plan_ids();

		foreach ($packs as $pack):

			$params = array('period' => 0, 'billed' => 0);

			if (in_array($pack['plan_id'], $hosting_plans)):

				$params = array('billed' => 0);

			endif;

			$update = $this->platform->post('ubersmith/package/update/'.$pack['packid'], $params);

		endforeach;

		return;

	}

	private function _calculate_credit_amount_total($order)
	{
		$total = 0;

		for ($i = 1; $i <= 100; $i++):

			$pack = 'pack' . $i; 

			if ( ! isset($order[$pack])):

				break;

			endif;

			$total += $order[$pack]['cost'];

		endfor;		

		$credit = round($total * ($this->percentage / 100),2);

		return $credit; 

	}
	
	
	private function _calculate_credit_amount($order,$count=1)
	{
		// initialize variables
		$pack		= 'pack'.$count;
		
		// grab percentage to use
		$percentage	= $this->percentage / 100;
		
		// grab hosting plan id's
		$hosting_packs	= $this->_get_hosting_plan_ids();
		
		// see if this pack exists, if not then we were unable to find the hosting package
		if ( ! isset($order[$pack])) return FALSE;
		
		// if this is not a hosting pack, increment $count and try next pack
		if ( ! in_array($order[$pack]['plan_id'],$hosting_packs)):
		
			// update counter
			$count++;
			
			// try next pack
			return $this->_calculate_credit_amount($order,$count);
		
		endif;
		
		// we have the hosting pack, grab the amount
		$amount		= $order[$pack]['price'];
		
		// return the credit amount
		return round($amount * $percentage,2);
	}
	
	private function _load_form($order,$invoice_id,$paypal_id)
	{
		
		if ( ! $invoice_id):

			// redirect to cc decline
			return $this->returned(FALSE);

		endif;
		
		
		// set order id
		$order_id = $order['order_id'];
		
		$info     = $order['info'];
		$hosting  = $order['info']['pack1'];
		
		

		$data['paypal']               = $this->_paypal_defaults;
		
		// set return URL
		$data['paypal']['return_url'] = $data['paypal']['return_url'].'1/'.$paypal_id.'/'.$order_id.'/'.$invoice_id.'/'; 	// Append paypal_id to end of URL
		
		// set cancel URL
		$data['paypal']['cancel_url'] = $data['paypal']['cancel_url'].'0/'.$paypal_id.'/'.$order_id.'/'.$invoice_id.'/';	// Append paypal_id and FALSE boolean to end of URL
		
		
		// GET INVOICE BY ID
		$invoice    = $this->platform->post('ubersmith/invoice/get/invoice_id/'.$invoice_id,array());
		
		// SET INVOICE TOTAL TO NEW INVOICE'S TOTAL - $data['paypal']['invoice_total']
		$data['paypal']['invoice_total']   = $invoice['data']['amount_unpaid'];
		
		$data['paypal']['item_name']       = $this->lang->line('paypal_checkout');
		
		//$data['paypal']['invoice_total'] = $order['total'];
		$data['paypal']['hosting_total']   = $hosting['price'];
		$data['paypal']['hosting_months']  = $hosting['period'];

		$data['paypal']['custom_data']     = json_encode(
			array(
				'client_id'  => $order['client_id'],
				'order_id'   => $order_id,
				'invoice_id' => $invoice_id,
				'paypal_id'  => $paypal_id
			) 
		);

		$data['paypal']['first_name'] = $info['first'];
		$data['paypal']['last_name']  = $info['last'];
		
		$data['paypal']['address']    = $info['address'];
		$data['paypal']['city']       = $info['city'];
		$data['paypal']['state']      = $info['state'];
		$data['paypal']['country']    = $info['country'];
		$data['paypal']['zip']        = $info['zip'];
		
		$data['paypal']['email']      = $info['email'];


		// mark this as sent in paypal table
		$post	= array(
			'paypal_id'      => $paypal_id,
			'invoice_id'     => $invoice_id,
			'total_price'    => $data['paypal']['invoice_total'],
			'hosting_price'  => $data['paypal']['hosting_total'],
			'hosting_period' => $data['paypal']['hosting_months']
		);
		$sent = $this->platform->post('ubersmith/paypal/mark_sent', $post);
		
		//@mail('travis.loudin@brainhost.com','mark sent response',json_encode($sent));

		$this->load->view('loading', $data);
	}
	
	function _complete_order($paypal_id, $order_id)
	{

		$completed = FALSE;

		$response = $this->platform->post(
			'ubersmith/paypal/mark_paid',
			array(
				'paypal_id' => $paypal_id
			)
		); 

		if ( ! $response['success']):

			$this->platform->post(
				'ubersmith/paypal/add_error',
				array(
					'paypal_id' => $paypal_id,
					'error'     => 'Error '.__LINE__.': Unable to mark row as paid '
				)
			);

			return $completed;

		endif;

		$response = $this->platform->post(
			'ubersmith/order/process/verify_payment/'.$order_id.'/true'
		);

		if ( ! $response['success']):

			$this->platform->post(
				'ubersmith/paypal/add_error',
				array(
					'paypal_id' => $paypal_id,
					'error'     => 'Error '.__LINE__.': '.json_encode($response['error'])
				)
			);

			return $completed;

		endif;


		$response = $this->platform->post(
			'ubersmith/order/process/register_domain/'.$order_id
		);

		if ( ! $response['success']):

			$this->platform->post(
				'ubersmith/paypal/add_error',
				array(
					'paypal_id' => $paypal_id,
					'error'     => 'Error '.__LINE__.': '.json_encode($response['error'])
				)
			);

		endif;


		$order_data = $this->platform->post('ubersmith/order/get',
			array(
				'order_id' => $order_id
			)
		);

		$client_id = (isset($order_data['data']['client_id'])) ? $order_data['data']['client_id'] : FALSE;

		if ( ! $client_id):

			$this->platform->post(
				'ubersmith/paypal/add_error',
				array(
					'paypal_id' => $paypal_id,
					'error'     => 'Error '.__LINE__.': Unable to get order info for CC deletion'
				)
			);
		
		else:

			$this->platform->post('ubersmith/client/update',array('client_id' => $client_id, 'meta_paypal' => 'yes'));

			/*
			$card_data = $this->platform->post(
				'ubersmith/credit_cards/get',
				array(
					'client_id' => $client_id
				)
			);

			if ( ! $card_data['success']):

				$this->platform->post(
					'ubersmith/paypal/add_error',
					array(
						'paypal_id' => $paypal_id,
						'error'     => 'Error: Unable to retrieve CC numbers'
					)
				);
	
			else:

				$cards = $card_data['data']['credit_cards'];

				foreach ($cards as $card):

					$delete = $this->platform->post(
						'ubersmith/credit_cards/delete',
						array(
							'billing_info_id' => $card['billing_info_id']
						)
					);

					if ( ! $delete['success']):

						$this->platform->post(
							'ubersmith/paypal/add_error',
							array(
								'paypal_id' => $paypal_id,
								'error'     => 'Error: Unable to delete CC - '.$billing_info_id
							)
						);
			
					endif;

				endforeach;

			endif;
			*/ 

		endif;
		
		$response = $this->platform->post(
			'ubersmith/paypal/mark_complete',
			array(
				'paypal_id' => $paypal_id
			)
		);

		$completed = $response['success'];

		return $completed;

	}

	public function cron()
	{

		$response = $this->platform->post('ubersmith/paypal/get_paid_dropoffs');

		if ( ! $response['success']):

			echo 'No PayPal dropoffs';
			return;

		endif;

		echo 'Num rows: '.count($response['data']['rows'])."\n\n";

		foreach ($response['data']['rows'] as $row):

			var_dump($row);

			//echo "\n";

			$cron_response = $this->platform->post(
				'ubersmith/paypal/mark_cron_submission',
				array(
					'paypal_id' => $row['id']
				)
			);

			/*
			$completed = $this->_complete_order($row['id'], $row['order_id']);

			var_dump($completed);
			*/
			
			echo "\n\n";

		endforeach;

		return;

	} 
	
	public function send_cs_email($success,$paypal_id)
	{
		// grab paypal customer details
		$paypal		= $this->platform->post('ubersmith/paypal/get_info',array('paypal_id' => $paypal_id));
		
		// error handling
		if ( ! $paypal['success'] OR empty($paypal['data'])) return;
		
		// grab customer details
		$paypal		= $paypal['data'];
		
		// create email message
		
		// send email
		
		return;
	}
} 