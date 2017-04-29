<?php

class Organization extends MX_Controller {


	protected $_params = array();

	function index()
	{

		// get post or populate with todays date
		$start  = $this->input->post('start_date') ? $this->input->post('start_date') : date('Y-m-d 00:00:00',strtotime('-7 days'));

		$end    = $this->input->post('end_date')   ? $this->input->post('end_date')   : date('Y-m-d 00:00:00');
		
		$dates  = array(
			strtotime($start),
			strtotime($end)
		);

		// make sure start date is always before end date
		sort($dates);

		// get from beginning of first date to end of second date
		$this->_params = array(
			'start_date' => date('Y-m-d 00:00:00', $dates[0]),
			'end_date'   => date('Y-m-d 23:59:59', $dates[1])
		);

		// specify all of the apis needed to call
		$apis   = array(
			'revenue' => array(
				'bh' => array(
					'function' => '_plat', // can be platform or curl
					'location' => 'crm/reports/revenue/gross/brainhost',
					'params'   => array(),
					'callback' => '_sum_plat_results' // needed for refunds
				),
				'br' => array(
					'function' => '_plat',
					'location' => 'akatus/revenue',
					'params'   => array(),
					'callback' => '_sum_plat_results'
				), // brazil_orders
				'ph' => array(
					'function' => '_plat',
					'location' => 'crm/reports/revenue/gross/purelyhosting',
					'params'   => array(),
					'callback' => '_sum_plat_results'
				),
				'fw' => array(
					'function' => '_curl_json',
					'location' => 'http://api:apishmoo@analytics.freewebsite.com/reports/index.php/data/revenue',
					'params'   => array(),
					'callback' => '_parse_fws'
				),
			),
			'refunds' => array(
				'bh' => array(
					'function' => '_plat',
					'location' => 'crm/reports/refund/rev_refund_refunds',
					'params'   => array(
						'brand' => 'brainhost'
					),
					'callback' => '_sum_plat_results'
				),
				'br' => NULL, 
				'ph' => array(
					'function' => '_plat',
					'location' => 'crm/reports/refund/rev_refund_refunds',
					'params'   => array(
						'brand' => 'purelyhosting'
					),
					'callback' => '_sum_plat_results'
				),
				'fw' => array(
					'function' => '_curl_json',
					'location' => 'http://api:apishmoo@analytics.freewebsite.com/reports/index.php/data/refunds',
					'params'   => array(),
					'callback' => '_parse_fws'
				),
			),
			'rebill_revenue' => array(
				'bh' => array(
					'function' => '_plat',
					'location' => 'crm/reports/renewals/revenue/brainhost',
					'params'   => array(),
					'callback' => '_sum_plat_results'
				),
				'br' => array(
					'function' => '_plat',
					'location' => 'akatus/recurringrevenue',
					'params'   => array(),
					'callback' => '_total_brazil_recurring'
				), 
				'ph' => array(
					'function' => '_plat',
					'location' => 'crm/reports/renewals/revenue/purelyhosting',
					'params'   => array(),
					'callback' => '_sum_plat_results'
				),
				'fw' => array(
					'function' => '_curl_json',
					'location' => 'http://api:apishmoo@analytics.freewebsite.com/reports/index.php/data/rebill_revenue',
					'params'   => array(),
					'callback' => '_parse_fws'
				),
			),
			'sales_count' => array(
				'bh' => array(
					'function' => '_plat',
					'location' => 'crm/reports/sales_count/get/brainhost',
					'params'   => array(),
					'callback' => '_sum_plat_results'
				),
				'br' => array(
					'function' => '_plat',
					'location' => 'crm/reports/sales_count/get/brazil_orders',
					'params'   => array(),
					'callback' => '_sum_plat_results'
				), 
				'ph' => array(
					'function' => '_plat',
					'location' => 'crm/reports/sales_count/get/purelyhosting',
					'params'   => array(),
					'callback' => '_sum_plat_results'
				),
				'fw' => array(
					'function' => '_curl_json',
					'location' => 'http://api:apishmoo@analytics.freewebsite.com/reports/index.php/data/initial_sales',
					'params'   => array(),
					'callback' => '_parse_fws'
				),
			),
			'visitor_count' => array(
				'bh' => array(
					'function' => '_plat',
					'location' => 'google_api/visitors/get/brain_host',
					'params'   => array(),
					'callback' => '_sum_plat_results'
				),
				'br' => array(
					'function' => '_plat',
					'location' => 'google_api/visitors/get/brain_host_brazil',
					'params'   => array(),
					'callback' => '_sum_plat_results'
				), 
				'ph' => array(
					'function' => '_plat',
					'location' => 'google_api/visitors/get/purely_hosting',
					'params'   => array(),
					'callback' => '_sum_plat_results'
				),
				'fw' => array(
					'function' => '_curl_json',
					'location' => 'http://api:apishmoo@analytics.freewebsite.com/reports/index.php/data/visitors',
					'params'   => array(),
					'callback' => '_parse_fws'
				),
			)
		);

		$stats  = array();

		// loop each type of api
		foreach ($apis as $type => $brands):

			// loop each brand within the api
			foreach ($brands as $key => $details):

				$stats[$type][$key] = NULL;

				if ( ! is_array($details)):
					continue;
				endif;

				// merge provided api parameters with date parameters
				$post = array_merge($this->_params, $details['params']);

				// get data differently depending on type
				$func = $details['function'];
				if ( ! is_callable(array($this,$func))):
					$func = '_curl_raw';
				endif;

				$resp = $this->$func($details['location'], $post);

				// if response is formatted correctly, we want data only
				$data = array();

				if (is_array($resp) && isset($resp['data'])):

					$data = $resp['data'];

				endif;

				$stats[$type][$key] = $data;

				// format response with callback function if specified
				if (isset($details['callback']) && $details['callback'] && is_callable(array($this, $details['callback']))):

					$stats[$type][$key] = $this->$details['callback']($data);

				endif;

			endforeach;

		endforeach;

		$bkeys = array();
		foreach ($stats as $type => $brands):
			$bkeys = array_keys($brands);
		endforeach;


		foreach ($bkeys as $bkey):

			$stats['conversion_(%)'][$bkey] = NULL;
			$stats['epc'][$bkey] = NULL;

			if ( ! $stats['visitor_count'][$bkey]):

				continue;

			endif;

			$stats['conversion_(%)'][$bkey] = $stats['sales_count'][$bkey]/$stats['visitor_count'][$bkey] * 100;

			$stats['epc'][$bkey] = ($stats['revenue'][$bkey] - $stats['refunds'][$bkey] - $stats['rebill_revenue'][$bkey])/$stats['visitor_count'][$bkey];

		endforeach;

		$data['stats']      = $stats;
		$data['start_date'] = date('Y-m-d',$dates[0]);
		$data['end_date']   = date('Y-m-d',$dates[1]);

		$this->load->view('organization/org_table', $data);

	}

	function _curl_json($url, $params)
	{
		$resp = $this->_curl_raw($url, $params);
		return json_decode($resp, TRUE);
	}

	function _curl_raw($url, $params)
	{

		return $this->curl->post($url, $params);

	}

	function _total_brazil_recurring($results)
	{

		$amount = $this->_sum_plat_results($results);


		$old_billing = $this->_plat('crm/reports/renewals/revenue/brazil_orders', $this->_params);
		$old_amt     = $this->_sum_plat_results($old_billing);

		return $amount + $old_amt;

	}

	function _plat($url, $params)
	{	
		return $this->platform->post($url, $params);
	}


	// recursively total amounts in platform response
	function _sum_plat_results($results)
	{

		$total = 0;

		if ( ! is_array($results)):

			return $total;

		endif;

		foreach ($results as $row):

			if (isset($row['amount'])):

				$total += $row['amount'];
				continue;

			endif;

			if (is_array($row)):

				$total += $this->_sum_plat_results($row);
				continue;

			endif;

		endforeach;

		return $total;
	}

	function _parse_fws($results)
	{

		$total = 0;

		if ( ! is_array($results)):

			return $total;

		endif;

		return array_shift($results);

	}

}