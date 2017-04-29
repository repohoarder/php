<?php

set_time_limit(0);

class Alerts extends MX_Controller {


	function cron()
	{

		$this->index(TRUE);
	}

	function index($is_cron = FALSE)
	{

		exit();

		$recent_sale     = FALSE;
		
		$minute_interval = 120;
		
		$subject         = 'Brain Host Lack of Sales';
		$from            = 'api@brainhost.com';
		
		$notify_emails   = array(
			//'vincentedwardfisher@gmail.com', 
			//'corey@brainhost.com', 
			'dustin@brainhost.com', 
			'nena.abdullah@brainhost.com', 
			'ryan.niddel@brainhost.com',
			'travis.loudin@brainhost.com',
			'matt.thompson@brainhost.com',
			'brainhost@aressindia.net',
			'jerry.garrett@brainhost.com',
			'john.ensell@brainhost.com'
		);

		$notify_emails = implode(',', $notify_emails);

		$headers = array(
			'From: '.$from,
			'cc:',
			'bcc:',
			'Content-type:text/plain',
		);

		$headers     = implode("\r\n",$headers);
		
		$errors      = array();
		$msg         = '';


		$last_complete = $this->platform->post('ubersmith/order/get_last_complete');

		if ($last_complete['success']):

			$order_time = $last_complete['data']['order']['order_time'];
			$order_time = date('g:i A',strtotime('+1 hour', strtotime($order_time)));

			$msg .= 'Last Complete Order: '.$order_time."\n\n";

			$seconds_interval = $last_complete['data']['order']['now_unix'] - $last_complete['data']['order']['order_unix'];

			if ($seconds_interval / 60 <= $minute_interval):

				$recent_sale = TRUE;

			endif;
			
		endif;

		$urls['200'] = array(
			'http://www.brainhost.com',
			'http://affiliate.brainhost.com',
			'http://www.mycreativesitedesigns.com/special/offer/2855/',
			'http://my.brainhost.com',
			'http://clusterweb1.brainhost.com',
			'http://clusterweb2.brainhost.com',
			'https://infrastructure.brainhost.com/monitor.php',
		);		

		foreach ($urls as $code => $sites):

			foreach ($sites as $site):

				$response = $this->_curl_url($site);

				$err = ($code !== $response['http_code']);

				$msg .= 'Try #1: '.str_replace('/monitor.php','',$site).' '.($err ? 'ERR: '.$response['http_code'] : 'OK')."\n";

				if ($err):

					$response2 = $this->_curl_url($site, 20);

					$err2 = ($code !== $response2['http_code']);

					$msg .= 'Try #2: '.str_replace('/monitor.php','',$site).' '.($err2 ? 'ERR: '.$response2['http_code'] : 'OK')."\n";

					if ($err2):

						$errors[] = str_replace('/monitor.php','',$site) .' did not resolve correctly after 2 attempts';

					endif;

				endif;

			endforeach;

		endforeach;

		$response = $this->platform->post(
			'ubersmith/client/login', 
			array(
				'user' => 'apirobot', 
				'pass' => 'd.EsastAp.uNasw7'
			)
		);

		$login_error = FALSE;

		if ( ! $response['success']):

			$login_error = (is_array($response['error']) ? implode(',',$response['error']) : $response['error']);

			$errors[] = 'Uber Login Error: '.$login_error;

		endif;


		$msg .= "\n".'API Login '.(($login_error) ? 'ERR: '.$login_error : 'OK');

		$subject .= ' Errors: '.count($errors);

		$recent_orders = $this->platform->post('ubersmith/order/get_recent');

		if ($recent_orders['success']):

			$msg .= "\n\n".'Order Activity in Last 60 Minutes'."\n";

			foreach ($recent_orders['data']['orders'] as $order):

				$last_activity = $order['last_activity'];
				$last_activity = date('g:i A',strtotime('+1 hour',strtotime($last_activity)));

				$msg .= '[q'.$order['order_queue_id'].'] '.$order['name'].': '.$order['num'].' (Last Activity: '.$last_activity.')'."\n";

			endforeach;

		endif;		

		if (count($errors)):

			$msg .= "\nErrors:\n".implode("\n",$errors)."\n";

		endif; 

		if (count($errors) || (($seconds_interval / 60) > (2 * $minute_interval))):

			$msg = "------------------------------\n" .
					"ATTN: TEST ORDER NEEDED \n" .
					"To the next available person: \n" .
					"Please run a full affiliate test order \n " .
					"and REPLY TO ALL with the results."."\n" .
					"------------------------------\n\n"
				 . $msg;

		endif;


		if ($is_cron && ( ! $recent_sale || ! empty($errors))):

			@mail($notify_emails, $subject, $msg, $headers);

			$msg = 'Mail sent.'."\n\n".$msg;

		endif;

		echo str_replace("\n",'<br/>',$msg);

	}

	function _curl_url($url, $timeout = 10)
	{

		$response = array(
			'content'   => '',
			'http_code' => ''
		);

		$ch       = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		$response['content']   = curl_exec($ch);		
		$response['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

		curl_close($ch);

		return $response;

	}


}