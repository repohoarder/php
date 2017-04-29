<?php

class Pagseguro extends MX_Controller {

	/**
	 *  TODO
	 * 
	 *  NEED TO ADD TRACKING TO THIS
	 *
	 *  might need to do something if notify type is refund or cancel etc as well
	 *
	 *  finish thanks() -- check if invoice is paid, 
	 *  	possibly put on delay so notify can catch up
	 * 
	 */
	
	function __construct()
	{
		parent::__construct();

		$this->load->config('pagseg');
			
		/*
		
		// dunno why I did this... but I'm leaving it

		$a = 1;
		$b = (($c = 0) === FALSE ? 'c' : 'a');
		$d = ($c -= ( !! ($$b % ($a += $c))));

		// results

		# $a == 1;
		# $b == 'a';
		# $c == 0;
		# $d == 0;

		*/
	}

	function thanks($invoice_id = NULL)
	{
		echo 'thanks '.$invoice_id;
	}

	function invoice($invoice_id = NULL)
	{

		$invoice = $this->_get_invoice($invoice_id);

		if ($invoice === FALSE):

			return $this->_whoops($invoice_id, 'Invalid invoice ID');

		endif;

		$client  = $this->_get_client($invoice['clientid']);

		if ($client === FALSE):

			return $this->_whoops($invoice_id, 'Unable to get client info');

		endif;

		$request = $this->_make_payment_request($invoice, $client);

		if ($request === FALSE):

			return $this->_whoops($invoice_id, 'Unable to make payment request');

		endif;		

		$errs    = $this->_get_request_errors($request);

		if (count($errs)):

			return $this->_whoops($invoice_id, implode(" \n<br/>",$errs));

		endif;

		$code = $request->code[0]->__toString();
		$code = trim($code);
	
		redirect($this->config->item('pagseg_redir').'?code='.$code);
		return;

	}

	function notify($invoice_id = NULL)
	{

		$invoice = $this->_get_invoice($invoice_id);

		if ($invoice === FALSE):

			return $this->_whoops($invoice_id, 'Invalid invoice ID');

		endif;

		$code    = $this->input->post('notificationCode');

		if ( ! $code || $this->input->post('notificationType') != 'transaction'):

			return $this->_whoops($invoice_id);

		endif;

		$notify  = $this->_get_notification($code);

		if ($notify === FALSE):

			return $this->_whoops($invoice_id, 'Unable to get transaction info');

		endif;

		$code       = $notify->code->__toString();
		$reference  = $notify->reference->__toString();

		$is_payment = ($notify->type->__toString() === '1' ? TRUE : FALSE);
		$is_paid    = ($is_payment && $notify->status->__toString() === '3' ? TRUE : FALSE);

		if ( ! $is_paid):

			return $this->_whoops($invoice_id, 'Not paid');

		endif;


		$discarded  = $this->_disregard_invoice($invoice['clientid'], $invoice_id);

		if ( ! $discarded):

			return $this->_whoops($invoice_id, 'Unable to discard invoice');

		endif;

		
		$orders     = $this->_get_client_orders($invoice['clientid']);

		if ($orders !== FALSE):

			$pushed = $this->_push_paid_orders($orders, $invoice_id);

		endif;

		/**
		 *
		 * TODO
		 * 
		 * add debug mailer here
		 *
		 * only need to echo for the fun of it
		 * 
		 */

		echo 'notify '.$invoice_id;

	}

	private function _disregard_invoice($client_id, $invoice_id)
	{

		$iresp = $this->platform->post(
			'ubersmith/invoice/disregard/'.$client_id.'/'.$invoice_id,
			array()
		);

		if ( ! $iresp['success']):

			return FALSE;

		endif;

		return TRUE;

	}

	private function _get_client_orders($client_id)
	{

		$resp = $this->platform->post(
			'ubersmith/order/get_client_orders',
			array(
				'client_id' => $client_id
			)
		);

		if ( ! $resp['success']):

			return FALSE;

		endif;

		return $resp['data']['orders'];

	}


	private function _whoops($invoice_id, $error = 'An error occurred. Please try again.')
	{

		/**
		 *
		 * TODO
		 * 
		 * Add mailer here and redirect to decline
		 * 
		 */

		show_error($error);
	}

	private function _push_paid_orders($orders, $invoice_id)
	{

		foreach ($orders as $order):

			if ( ! $this->_order_has_invoice($order, $invoice_id)):

				continue;

			endif;

			$queue_type = $this->_get_order_queue_type($order['order_queue_id']);

			if ( ! $queue_type):

				return FALSE;

			endif;

			if ( ! $this->_order_is_unpaid($order, $queue_type)):

				return FALSE;

			endif;

			$resp = $this->platform->post(
				'ubersmith/order/process/verify_payment/'.$order['order_id'].'/1/'.$queue_type,
				array()
			);

			return ($resp['success'] != FALSE);

		endforeach;

		return FALSE;
	}

	private function _get_request_errors($request)
	{

		$errs = array();

		if (property_exists($request, 'error')):

			foreach ($request->error as $err):

				$errs[] = trim($err->message) . ' (' . trim($err->code) . ') ';

			endforeach;

		endif;

		return $errs;
	}

	private function _get_order($order_id)
	{

		$order = $this->platform->post(
			'ubersmith/order/get',
			array(
				'order_id' => $order_id
			)
		);

		if ( ! $order['success']):

			return FALSE;

		endif;

		return $order['data'];
	}

	private function _make_payment_request($invoice, $client)
	{

		$invoice_id = $invoice['invid'];
		
		$packs      = $invoice['current_packs'];
		$name       = $client['first'] . ' ' . $client['last'];
		$email      = $client['email'];
		
		$counter    = 0;

		$disc_perc  = 0.15;
		$disc_amt   = 0;
		
		$url        = $this->config->item('pagseg_api');
		
		$params     = array(
			// store credentials
			'email'           => $this->config->item('pagseg_account'),
			'token'           => $this->config->item('pagseg_token'),

			'redirectUrl'     => site_url('pagseguro/thanks/'.$invoice_id),
			'notificationUrl' => site_url('pagseguro/notify/'.$invoice_id),
			
			'currency'        => 'BRL',
			'reference'       => $invoice_id, // uber invoice ID
			'shippingType'    => 3,           // 3 = unspecified
			'maxUses'         => 1,           // can only check out once
			'maxAge'          => 10800,       // 3 hours in seconds
			
			// buyer details
			'senderName'      => $name,
			'senderEmail'     => $email
		);

		foreach ($packs as $pack):

			$counter++;

			$pack['cost'] = number_format($pack['cost'] * $this->config->item('pagseg_conv'), 2);

			if ($pack['cost'] < 0.01):

				continue;

			endif;

			$params['itemId' . $counter]          = $pack['packid'];
			$params['itemDescription' . $counter] = $pack['desserv'];
			$params['itemAmount' . $counter]      = $pack['cost'];
			$params['itemQuantity' . $counter]    = 1;

			$disc_amt += ($pack['cost'] * $disc_perc); 

		endforeach;

		if ($disc_amt > 0):

			$disc_amt = number_format(0 - $disc_amt, 2);
			$params['extraAmount'] = $disc_amt;

		endif;

		$resp = $this->_curly_post($url, $params);
		$resp = trim($resp);

		if ( ! $resp || $resp=='Unauthorized'):

			return FALSE;

		endif;

		$resp = new SimpleXMLElement($resp);

		if ( ! is_object($resp)):

			return FALSE;

		endif;

		return $resp;

	}

	private function _curly_post($url = FALSE, $post = array())
	{
		// error handling
		if ( ! $url)
			throw new Exception('Valid URL not supplied.');
		
		// generate query string from post_data
		$query_string = http_build_query($post, '', '&');
		
		// initialize curl
		$ch = curl_init();

		$headers = array(
			"Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1",
			"Content-length: ".strlen($query_string),
			'lib-description: php-v.2.1.5'
		);
		
		// set parameters
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		// run cUrl
		$response	= curl_exec($ch);

		curl_close($ch);

		// return the response
		return $response;
	}	

	private function _get_client($client_id)
	{

		$client = $this->platform->post(
			'ubersmith/client/get',
			array(
				'name'      => 'id',
				'client_id' => $client_id
			)
		);

		if ( ! $client['success']):

			return FALSE;

		endif;

		return $client['data'];
	}

	private function _get_invoice($invoice_id)
	{

		$invoice = $this->platform->post(
			'ubersmith/invoice/get/invoice_id/'.$invoice_id,
			array()
		);

		if ( ! $invoice['success']):

			return FALSE;

		endif;

		return $invoice['data'];

	}

	private function _get_notification($code)
	{

		$url    = $this->config->item('pagseg_notify').$code;
		
		$params = array(
			'email' => $this->config->item('pagseg_account'),
			'token' => $this->config->item('pageseg_token')
		);

		$resp = $this->curl->get($url, $params);
		$resp = trim($resp);

		if ( ! $resp || $resp == 'Unauthorized'):

			return FALSE;

		endif;

		$resp = new SimpleXMLElement($resp);

		if ( ! is_object($resp)):

			return FALSE;

		endif;

		return $resp;

	}

	private function _order_has_invoice($order_info, $invoice_id)
	{

		if ( ! isset($order_info['info']['invid'])):

			return FALSE;

		endif;

		if ($order_info['info']['invid'] != $invoice_id):

			return FALSE;

		endif;

		return TRUE;

	}

	private function _get_order_queue_type($queue_id)
	{

		$qresp = $this->platform->post(
			'ubersmith/order_queue/get_order_queue_type',
			array(
				'queue_id' => $queue_id
			)
		);

		if ( ! $qresp['success']):

			return FALSE;

		endif;

		return $qresp['data']['type'];

	}

	private function _order_is_unpaid($order_info, $queue_type)
	{

		$sresp = $this->platform->post(
			'ubersmith/order/get_order_status/'.$queue_type,
			array(
				'order_id' => $order_info['order_id']
			)
		);

		if ( ! $sresp['success']):

			return FALSE;

		endif;

		$status = $sresp['data']['orders'][$order_info['order_id']];

		if ($status != 'verify_payment' && $status != 'stuck_payment'):

			return FALSE;

		endif;

		return TRUE;

	}

}