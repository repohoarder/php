<?php

class Paypal_cron extends MX_Controller {

	function index()
	{

		$response = $this->platform->post('ubersmith/paypal/get_paid_dropoffs');

		if ( ! $response['success']):

			echo 'No PayPal dropoffs';
			return;

		endif;

		echo 'Num rows: '.count($response['data']['rows'])."\n\n";

		foreach ($response['data']['rows'] as $row):

			var_dump($row);

			echo "\n";

			$cron_response = $this->platform->post(
				'ubersmith/paypal/mark_cron_submission',
				array(
					'paypal_id' => $row['id']
				)
			);

			$completed = $this->_complete_order($row['id'], $row['order_id']);

			var_dump($completed);

			echo "\n\n";

		endforeach;

		return;

	}


	function _complete_order($paypal_id, $order_id)
	{

		$response = $this->platform->post(
			'ubersmith/paypal/mark_paid',
			array(
				'paypal_id' => $paypal_id
			)
		); 

		$response = $this->platform->post(
			'ubersmith/order/process/verify_payment/'.$order_id.'/true'
		);
		

		$completed = FALSE;

		if ($response['success']):

			$response = $this->platform->post(
				'ubersmith/paypal/mark_complete',
				array(
					'paypal_id' => $paypal_id
				)
			);

			$completed = $response['success'];

		else:

			$response = $this->platform->post(
				'ubersmith/paypal/add_error',
				array(
					'paypal_id' => $paypal_id,
					'error'     => 'Error: '.json_encode($response['error'])
				)
			);

		endif;

		return $completed;

	}


}