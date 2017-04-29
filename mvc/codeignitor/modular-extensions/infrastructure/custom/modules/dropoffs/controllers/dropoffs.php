<?php

class Dropoffs extends MX_Controller {
	
	function index()
	{

		$dropoffs = $this->platform->post(
			'sales_funnel/dropoffs/get'
		);

		if ( ! $dropoffs['success'] || empty($dropoffs['data']['orders'])):

			return $this->_output(
				array('error' => 'No dropoffs to submit')
			);

		endif;

		$orders   = $dropoffs['data']['orders'];
		$statuses = $this->platform->post(
			'ubersmith/order/get_order_status',
			array(
				'order_ids' => $orders
			)
		);

		if ( ! $statuses['success']):

			return $this->_output(
				array('error' => 'Unable to get order queue steps of dropoffs')
			);

		endif;

		$statuses = $statuses['data']['orders'];
		$output   = array();

		foreach ($statuses as $order => $status):

			if ( $status != 'leads'):

				// mark order as submitted - order was somehow submitted without dropoff cron 
				$r = $this->platform->post(
					'partner/order/submitted',
					array(
						'order_id' => $order
					)
				);

				$output[$order] = 'Order submitted within Ubersmith. Status: '.$status;
				
				// update the order and marc as cron ran
				$update = $this->platform->post(
					'partner/order/update',
					array(
						'order_id' => $order,
						'cron' => 1
					)
				);
				
				unset($statuses[$order]);
				continue;

			endif;

			// submit order
			$url  = site_url('billing/processing/order/'.$order);
			$resp = $this->curl->post($url); // async not working... oh well.

			$output[$order] = 'Dropoff submitted';

		endforeach;

		return $this->_output(
			array('dropoffs' => $output)
		);

	}

	function _output($output)
	{

		var_dump($output);
		//@mail('travis.loudin@brainhost.com','Purely dropoff cron', json_encode($output));

	}


}