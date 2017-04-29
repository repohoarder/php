<?php

class Test extends MX_Controller {

	# Move to a config
	protected $_paypal_defaults = array(
		
		'payment_url'   => 'https://www.sandbox.paypal.com/us/cgi-bin/webscr',
		'business_acct' => 'matt.t_1349464808_biz@brainhost.com',

		'logo'          => 'http://setup.brainhost.com/resources/brainhost/img/brainhost_big_wreck_tangle.png',
		'ipn_url'       => 'https://my.brainhost.com/ipn/paypal.php',
		'return_url'    => 'http://domains.brainhost.com/paypal/payment',
		'cancel_url'    => 'http://domains.brainhost.com/paypal/cancel',
	);

	protected 
			$_dropoff_minutes = 30,
			$_max_uber_errors = 3;

	function test_cron()
	{

		$sql = '
			SELECT 
				pp.id, pp.order_id 
			FROM
				brainhos_accounts.paypal_orders AS pp
			INNER JOIN 
				ubersmith.INVOICES AS inv ON
					pp.date_sent_to_paypal != "0000-00-00 00:00:00" AND
					(
						(pp.date_sent_to_paypal < DATE_SUB(NOW(), INTERVAL '.intval($this->_dropoff_minutes).' MINUTE) AND pp.uber_error_count = 0) OR
						(pp.last_checked < DATE_SUB(NOW(), INTERVAL '.intval($this->_dropoff_minutes).' MINUTE) AND pp.uber_error_count < '.intval($this->_max_uber_errors).')
					)
					AND
					pp.cancelled = 0 AND
					pp.completed = 0 AND
					pp.invoice_id = inv.invid AND
					inv.paid = 1
			';

		$query = $this->db->query($sql);

		$cron_ids = array();

		foreach ($query->result() as $row):

			$cron_ids[] = $row->id;

			$this->_process_paypal_order($row->id);

		endforeach;

		$this->_mark_paypal_cron($cron_ids);

	}

	function _update_paypal_returned($paypal_id, $cancelled = FALSE)
	{

		$returned = ($returned) ? 1 : 0;

		$sql = '
			UPDATE
				brainhos_accounts.paypal_orders AS pp
			SET
				pp.returned = 1,
				pp.cancelled = ?
			WHERE
				pp.id = ?
			LIMIT 1
		';

		$query = $this->db->query($sql, array($cancelled, $paypal_id));

	}

	function _update_paypal_error($paypal_id, $error_text)
	{

		$sql = '
			UPDATE
				brainhos_accounts.paypal_orders AS pp
			SET 
				pp.error_count = error_count + 1,
				pp.error_text = CONCAT(error_text, "\n\n", ?)
			WHERE
				pp.id = ?
			LIMIT 1
		';

		$query = $this->db->query($sql,array($error_text, $paypal_id));


	}

	function _mark_paypal_cron($paypal_ids)
	{

		if ( ! $paypal_ids):

			return FALSE;

		endif;

		if ( ! is_array($paypal_ids)):

			$paypal_ids = array($paypal_ids);

		endif;
		
		$paypal_ids    = array_filter(array_map('intval',$paypal_ids));
		
		$paypal_id_cnt = count($paypal_ids);
		$paypal_id_sql = implode(', ',$paypal_ids);

		$sql = '
			UPDATE
				brainhos_accounts.paypal_orders AS pp
			SET
				pp.cron_submitted = 1,
				pp.last_checked = NOW()
			WHERE
				pp.id IN ('.$paypal_ids.')
			LIMIT '.$paypal_id_cnt.'
		';

		$query = $this->db->query($sql);
	}

	function _process_paypal_order($paypal_id)
	{

		$orders = $this->_get_paypal_orders($paypal_id);

		foreach ($orders as $order):

			$response = $this->platform->post('ubersmith/order/process/verify_payment/'.$order->order_id.'/true');

			if ($response['success']):
			
				$this->_mark_paypal_as_completed($paypal_id);

			endif;

		endforeach;

	}

	function _get_paypal_orders($paypal_ids)
	{

		if ( ! $paypal_ids):

			return FALSE;

		endif;

		if ( ! is_array($paypal_ids)):

			$paypal_ids = array($paypal_ids);

		endif;


		$paypal_ids    = array_filter(array_map('intval',$paypal_ids));
		
		$paypal_id_cnt = count($paypal_ids);
		$paypal_id_sql = implode(', ',$paypal_ids);

		$sql = '
			SELECT 
				pp.order_id
			FROM
				brainhos_accounts.paypal_orders AS pp
			WHERE 
				pp.id IN ('.$paypal_id_sql.')
			LIMIT '.$paypal_id_cnt.'
		';

		$query = $this->db->query($sql, array($paypal_id));

		return $query->result();

	}

	function _mark_paypal_as_paid($paypal_id)
	{

		$sql = '
			UPDATE
				brainhos_accounts.paypal_orders AS pp
			SET
				pp.paid = 1
			WHERE 
				pp.id = ?
			LIMIT 1
		';

		$query = $this->db->query($sql, array($paypal_id));

	}

	function _mark_paypal_as_completed($paypal_id)
	{

		$sql = '
			UPDATE
				brainhos_accounts.paypal_orders AS pp
			SET
				pp.completed = 1
			WHERE 
				pp.id = ?
			LIMIT 1
		';

		$query = $this->db->query($sql, array($paypal_id));

	}


	function index($order_id, $invoice_id)
	{

		if ( ! $order_id):

			// redirect to cc decline?

			return;

		endif;

		$response = $this->platform->post('ubersmith/order/get',
			array(
				'order_id' => $order_id
			)
		);

		if ( ! $response['success']):

			// redirect to cc decline

			return;

		endif;

		$order = $response['data'];

		if ( ! $order || ! isset($order['info']) || ! isset($order['info']['pack1'])):

			// redirect to cc decline

			return;

		endif;

		if ( ! $invoice_id):

			// redirect to cc decline
			
			return;

		endif;
		


		$info    = $order['info'];
		$hosting = $order['info']['pack1'];



		$data['paypal']                   = $this->_paypal_defaults;

		$data['paypal']['item_name']      = 'Award-winning, reliable Web Hosting services (15% off!) from Brain Host.';

		$data['paypal']['invoice_total']  = $order['total'];
		$data['paypal']['hosting_total']  = $hosting['price'];
		$data['paypal']['hosting_months'] = $hosting['period'];

		$data['paypal']['custom_data']    = json_encode(
			array(
				'client_id'  => $order['client_id'],
				'order_id'   => $order['order_id'],
				'invoice_id' => $invoice_id
			)
		);

		$data['paypal']['first_name']     = $info['first'];
		$data['paypal']['last_name']      = $info['last'];

		$data['paypal']['address']        = $info['address'];
		$data['paypal']['city']           = $info['city'];
		$data['paypal']['state']          = $info['state'];
		$data['paypal']['country']        = $info['country'];
		$data['paypal']['zip']            = $info['zip'];

		$data['paypal']['email']          = $info['email'];


		$this->load->view('test', $data);
	}

}