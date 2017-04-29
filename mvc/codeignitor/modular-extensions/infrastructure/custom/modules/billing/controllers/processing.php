<?php 

class Processing extends MX_Controller
{

	/**
	 * Funnel Version
	 * 
	 * @var  _funnel_version This variable holds the current funnel id
	 */
	var $_funnel_version;

	/**
	 * Partner ID
	 * 
	 * @var  _partner This varibale holds the current partner array
	 */
	var $_partner;

	/**
	 * Funnel Type
	 * 
	 * @var  _funnel_type  This variable holds the current funnel_type
	 */
	var $_funnel_type;

	/**
	 * ID
	 * 
	 * @var  _id This is the order/client id
	 */
	var $_id;

	/**
	 * Slug
	 * 
	 * @var  _slug The slug of the next page to show
	 */
	var $_slug;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Index
	 * 
	 * This method determines how to process the order/client and processes
	 */
	public function index()
	{
		$this->sale('completed');
	}

	/**
	 * Sale
	 * 
	 * This method determines how to process the order/client and processes it
	 */
	public function sale($slug='completed',$type='order',$id=FALSE)
	{
		// initialize variables
		$this->_funnel_type		= $type;
		$this->_funnel_version	= $this->session->userdata('funnel_id');
		$this->_partner 		= $this->session->userdata('partner_id');
		$this->_id 				= ( ! $id)
			? $this->session->userdata('_id')		// No id was passed, use session variable
			: $id;									// User passes an id, process this instead of session

		// set next page slug
		$this->_slug = $slug;
		
		// create method variable
		$method      = $this->_funnel_type;

		// run method
		$this->$method($this->_id);
	}

	public function session()
	{
		// grab funnel type from _type session
		$type 	= $this->session->userdata('_type');

		// grab order/client id from _id session
		$id 	= $this->session->userdata('_id');

		// redirect this page to billing/processing/sale/completed/_type/_id
		redirect("billing/processing/sale/completed/".$type."/".$id);
	}

	/**
	 * Order
	 * 
	 * This method processes an order
	 */
	public function order($id = FALSE)
	{
		// initialize variables
		$success         = FALSE;		
		$queue_type      = $this->_get_queue_type($id);
		
		// check for custom merchant
		//$custom_merchant = $this->session->userdata('partner_info');
		//$merchant        = isset($custom_merchant['merchant']['name']) ? $custom_merchant['merchant']['name'] : false;
		
		$success         = $this->_process('order', $id, $queue_type);

		$resp = NULL;
		
		//if ( ! $merchant) :
			
			
			$api  = 'ubersmith/order/process/generate_invoice/'.$id;

			$resp = $this->platform->post($api);

			#### Freshly added goodness			
			$resp 	= $this->_verify_payment($id);

			/*
			$success['success']                   = FALSE;
			$success['data']['submit']['success'] = FALSE;

			if ($resp['success'] OR $this->session->userdata('ip_address') == '74.218.103.238'):

				$success['success']                   = TRUE;
				$success['data']['submit']['success'] = TRUE;

			endif;
		*/
			#### End Freshly added goodness
			/*
		else :
			
			//echo 'custom merchant junk goes here';
			//echo '<br/><br/>';
			
			$success['success']                   = FALSE;
			$success['data']['submit']['success'] = FALSE;
			
			$this->load->library('custom_merchant');
			$custom = $this->custom_merchant->process();
			
			if($custom['success']) :
				$success['success']                   = TRUE;
				$success['data']['submit']['success'] = TRUE;
			endif;			

		endif;
		*/
		
		$accepted = ($resp['success']);


		$cookie = array(
		    'name'   => 'ordersubmitted',
		    'value'  => json_encode(
		    	array(
					'id'      => $id,
					'success' => $accepted
		    	)
		    ),
		    'expire' => '86500',
		);
		
		$this->input->set_cookie($cookie); 

		if ( ! $accepted):

			return $this->_decline($resp['error']);

		endif;

		$this->_mark_order_submitted($id);

		# Need to make this asynchronous
		$resp = $this->platform->post('ubersmith/order/process/register_domain/'.$id.'/0/'.$queue_type);


		return $this->_success('order', $id);
	}

	private function _verify_payment($id,$max=1)
	{
		// make API call
		$resp = $this->platform->post('ubersmith/order/process/verify_payment/'.$id,array());

		// if response unsuccessful, then try again
		if ( ! $resp['success']):

			// verify payment
			$resp 	= $this->platform->post('ubersmith/payment/verify',array('order_id' => $id));

			/*
			// increment counter
			$max  	= $max+1;

			// if max == 5, then we've tried enough
			if ($max >= 5)
				return $resp;

			// retry verify payment API call
			return $this->_verify_payment($id,$max);
			*/

		endif;

		// return response
		return $resp;
	}

	function _get_queue_type($order_id)
	{

		$queue_type = 'hosting';

		$order_info = $this->platform->post(
			'ubersmith/order/get',
			array(
				'order_id' => $order_id
			)
		);

		if ( ! $order_info['success']):

			return $queue_type;

		endif;

		$queue_id = $order_info['data']['order_queue_id'];

		if ( ! $queue_id):

			return $queue_type;

		endif;

		$resp = $this->platform->post(
			'ubersmith/order_queue/get_order_queue_type',
			array(
				'queue_id' => $queue_id
			)
		);

		if ( ! $resp['success']):

			return $queue_type;

		endif;


		return $resp['data']['type'];
	}


	function _mark_order_submitted($id)
	{

		if ( ! $id):

			return;

		endif;

		$resp = $this->platform->post(
			'ubersmith/order/get',
			array(
				'order_id' => $id
			)
		);

		if ( ! $resp['success']):

			return;

		endif;
		
		$order        = $resp['data'];
		
		$p['success'] = FALSE;

		if (isset($order['client_id']) && $order['client_id']):

			// update client id in ubersmith  and get response clients list
			$this->_update_get_response($order['client_id']);
			
			$p = $this->platform->post(
				'partner/order/update',
				array(
					'order_id'   => $id,
					'client_id'  => $order['client_id'],
					'visitor_id' => $this->session->userdata('visitor_id')
				)
			);

		endif;

		
		$q = $this->platform->post(
			'partner/order/submitted',
			array(
				'order_id'   => $id,
				'visitor_id' => $this->session->userdata('visitor_id')
			)
		);

		return ($p['success'] && $q['success']);
	}

	/**
	 * Client
	 * 
	 * This method processes an order for a client
	 */
	public function client($id=FALSE)
	{
		// initialize variables
		$success 	= FALSE;
		
		// process the order
		$success 	= $this->_process('client',$id);
		
		// run decline or success depending on successful or not
		return ($success['success'] === TRUE)
			? $this->_success('invoice', $success['data']['generate']['data']['invid'])	// Pass the invoice id to thank you pg
			: $this->_decline();
	}

	/**
	 * Decline
	 * 
	 * This method redirects to the decline page
	 */
	private function _decline($error)
	{

		$redirect = 'billing/declined/index/'.$error;

		// redirect to decline page
		if ($this->_funnel_type == 'order'):

			$redirect = 'paypal/offer/'.$this->_id;

		endif;

		redirect($redirect);
		return;
	}

	/**
	 * Success
	 * 
	 * This method redirects to the proper "Completed" page - order submission was successful
	 */
	private function _success($type='order',$id=FALSE)
	{
		// see if we need to add a charitable donation
		if ($this->session->userdata('_charity'))
			$this->_charity($type,$id);

		// send data to thrive
		# $this->_thrive($type,$id);

		// insert record into partners_orders
		# $this->_update_visitor_order($type,$id);

		// redirect to thank you page
		redirect('completed/sale/'.$type.'/'.$id.'/'.$this->_slug);
		return;
	}

	/*
	function _update_visitor_order($type, $id)
	{

		if ($type != 'order' || ! $id):

			return;

		endif;

		$order = $this->platform->post(
			'ubersmith/order/get',
			array(
				'order_id' => $id
			)
		);

		return;

	}
	*/


	/**
	 * Process
	 * 
	 * This method processes an order or client (invoice)
	 */
	private function _process($type, $id, $queue_type = 'hosting',$action='add_services')
	{
		// create post array
		$post	= array(
			'type'				=> $type,
			'_id'				=> $id,
			'order_action_id'	=> $action,
			'queue_type'        => $queue_type
		);
		
		// submit sale
		$submit	= $this->platform->post('crm/cart/submit', $post);

		return $submit;
	}

	/**
	 * This method adds a charity package to this type/id
	 * @param  string  $type [description]
	 * @param  boolean $id   [description]
	 * @return [type]        [description]
	 */
	private function _charity($type='order',$id=FALSE)
	{
		// error handling
		if ( ! $id)
			return FALSE;

		// create add array
		$add 	= array(
			'type'	=> $type,
			'id'	=> $id
		);

		// add the charitable donation
		return $this->platform->post('charity/dollar', $add);
	}

	

	/**
	 * This method sends the order info to Thrive
	 * @param  string  $type [description]
	 * @param  boolean $id   [description]
	 * @return [type]        [description]
	 */
	private function _thrive($type='order',$id=FALSE)
	{
		// initialize variables
		$add 	= array();

		// make sure we got a valid type
		if ($type != 'order' AND $type != 'client')
			return FALSE;

		// make sure we received an id
		if ( ! $id OR ! is_numeric($id))
			return FALSE;

		// see fi order type is order or client
		if ($type == 'order'):

			// get order information
			$order 	= $this->platform->post('ubersmith/order/get',array('order_id' => $id));

			// if we were unable to grab order info, then return false
			if ( ! $order['success'] OR ! isset($order['data']['info']) OR ! is_array($order['data']['info']))
				return FALSE;

			// create add array for thrive
			$add 	= array(
				'first_name'	=> @$order['data']['info']['first'],
				'last_name'		=> @$order['data']['info']['last'],
				'email'			=> @$order['data']['info']['email'],
				'city'			=> @$order['data']['info']['city'],
				'state'			=> @$order['data']['info']['state'],
				'zip'			=> @$order['data']['info']['zip'],
				'country'		=> @$order['data']['info']['country'],
				'phone'			=> @$order['data']['info']['phone'],
				'domain_name'   => @$order['data']['info']['core_domain_name']
			);

		endif;

		// if we have data to add, then send off to Thrive
		if (is_array($add) AND ! empty($add))
			$this->platform->post('thrive/lead/add',$add);

		// if user made it here, then adding to thrive was unsuccessful
		return FALSE;
	}
	
	/**
	* Determine if an order has been paid.
	* @param int $order_id
	*/
	public function testit(){
		$s = $this->_order_is_paid(2184);
		var_dump($s);
	}
	private function _order_is_paid($order_id) {
	
		$order_id = intval($order_id);
		
		$order = $this->platform->post(
			'ubersmith/order/get',
			array(
				'order_id' => $order_id
			)
		);
		$order_info = $order['data'];
	
		if (!$this->_validate_order_info($order_id, $order_info) || !array_key_exists('info',$order_info)):
		
			return FALSE;
			
		endif;
		
		if (!array_key_exists('client_id',$order_info)):
			
			return FALSE;
			
		endif;
		
		
		if (array_key_exists('payment_status',$order_info['info']) && ($order_info['info']['payment_status'] == 'Pre-Auth Failed') || $order_info['info']['payment_status'] == 'Charge Failed'):
		
			return FALSE;
			
		endif;
		
		
		if (array_key_exists('payment_type',$order_info['info']) && strtolower($order_info['info']['payment_type']) == 'paypal'):
			
			if ($order_info['info']['payment_status'] == 'Completed' && $order_info['info']['inv_balance'] == '0.00' && $order_info['info']['payment_amount'] == $order_info['total']):
				
				return TRUE;
				
			endif;
		
		endif;
	
		
		if (array_key_exists('invid',$order_info['info'])):
					
			$inv = $this->platform->post(
				'ubersmith/invoice/get/invoice_id/'.$order_info['info']['invid']
			);
			$invoice_info = $inv['data'];
			
			if (array_key_exists('paid',$invoice_info) && $invoice_info['paid'] == '1'):
				
				return TRUE;
				
			endif;			
		
		endif;	
	
		return FALSE;
	
	}
	function _validate_order_info($order_id, $order_info) {
		
		
		$valid = TRUE;
		
		if (!$order_info || !is_array($order_info) || implode('', array_slice($order_info,0,1)) == 'failed'):
			
			$valid = FALSE;
			
		endif;
		
		return $valid;
	
	}
	
	// update get response list
	private function _update_get_response($client_id) {
		
		
			if( ! $client_id ) :
				return;
			endif;
			$email_params = $this->session->userdata('getresponse');
			// add email to clients from getresponses
			$email_params['meta'] = array('clientid' => $client_id);
			$email_params['list']  = 'clients';
			
			$resp = $this->platform->post('esp/update',$email_params);
			
	}
}