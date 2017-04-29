<?php

set_time_limit(1800);

class Renewals extends MX_Controller {

	function __construct()
	{

		parent::__construct();

		$this->load->helper('url');
		$this->load->helper('email');

	}

	function main_cron()
	{

		$errors = array();

		$response = $this->platform->post('ubersmith/renewals/dom_only_store_renewals');
		
		if ( ! $response['success']):

			$errors[] = $response['error'];

		endif;

		$response = $this->platform->post('ubersmith/renewals/dom_only_update_inactive');

		if ( ! $response['success']):

			$errors[] = $response['error'];

		endif;
		
		$response = $this->platform->post('ubersmith/renewals/dom_only_mark_if_paid');

		if ( ! $response['success']):

			$errors[] = $response['error'];

		endif;


		$email_attempt = 1;

		$response = $this->platform->post('ubersmith/renewals/dom_only_get_rows_to_email/'.$email_attempt);

		if ( ! $response['success']):

			$errors[] = $response['error'];

		endif;
		
		$response2 = $this->platform->post('ubersmith/renewals/report_opt_in');
		
		if ( ! $response2['success']):

			$errors[] = $response2['error'];

		endif;

		if (count($errors)):

			@mail('travis.loudin@brainhost.com','Renewals cron errors',json_encode($errors));
			var_dump($errors);
			return;

		endif;

		
		foreach ($response['data']['rows'] as $row):
			
			$pack_key = $this->_generate_pack_key($row['packid']);

			if ( ! $pack_key):

				$errors[] = 'Could not generate renewal secret key: '.$row['id'];
				continue;

			endif;

			if ( ! valid_email($row['email'])):

				//$errors[] = 'Invalid client email: '.$row['id'];
				continue;

			endif;


			$response3 = $this->platform->post(
				'esp/add',
				array(
					'list'  => 'renewals',
					'name'  => $row['first'],
					'email' => $row['email'], 
					'meta'  => array(
						'renewal_secret' => $pack_key,
						'client_id'      => $row['clientid'],
						'renewal_row_id' => $row['id']
					)
				)
			);

			$return = $this->platform->post('ubersmith/renewals/dom_only_update_emailed_row/'.$row['id']);

			if ( ! $return['success']):

				$errors[] = $return['error'].': '.$row['id'];
				continue;

			endif;

		endforeach;

		if (count($errors)):

			@mail('travis.loudin@brainhost.com','Renewals cron errors',json_encode($errors));
			var_dump($errors);
			return;

		endif;

		$status = 'all good!';

		@mail('travis.loudin@brainhost.com','Renewal cron complete',$status);
		echo $status;
		return;

	}

	function process($email_attempt, $renewal_id, $client_id, $pack_key)
	{

		$domain_price = 14.95;
		$bundle_price = 1997;
		$price        = $bundle_price - $domain_price;

		$discount     = '1900.00';

		if ($email_attempt == 2):

			$discount  = '1924.25';

		endif;
		
		
		if ( ! $client_id || ! $renewal_id):

			redirect(site_url('billing/declined'));
			return;

		endif; 

		
		$response = $this->platform->post('ubersmith/renewals/dom_only_get_row/'.$renewal_id);

		if ( ! $response['success']):

			$this->platform->post(
				'ubersmith/renewals/dom_only_store_error/'.$renewal_id,
				array(
					'error' => 'Unable to get renewal row info'
				)
			);

			redirect(site_url('billing/declined'));
			return;

		endif;

		if ($response['data']['row']['opted_in'] != '0000-00-00 00:00:00'):

			$this->platform->post(
				'ubersmith/renewals/dom_only_store_error/'.$renewal_id,
				array(
					'error' => 'Already opted in'
				)
			);

			redirect(site_url('billing/declined'));
			return;

		endif;


	
		$domain_pack_id = $response['data']['row']['packid'];

		$control_key = $this->_generate_pack_key($domain_pack_id);

		if ($control_key != $pack_key):

			$this->platform->post(
				'ubersmith/renewals/dom_only_store_error/'.$renewal_id,
				array(
					'error' => 'Control key does not match provided key'
				)
			);

			redirect(site_url('billing/declined'));
			return;

		endif;

		$plat_response = $this->platform->post('ubersmith/package/get/pack_id/'.$domain_pack_id);

		if ( ! $plat_response['success']):

			$this->platform->post(
				'ubersmith/renewals/dom_only_store_error/'.$renewal_id,
				array(
					'error' => 'Unable to get pack info'
				)
			);

			redirect(site_url('billing/declined'));
			return;

		endif;

		$domain_pack   = $plat_response['data'][0];
		
		$update_params = array(
			'discount' => 0
		);

		if ($domain_pack['price'] != $domain_price):

			$update_params['price'] = $domain_price;

		endif;

		if ($domain_pack['billed'] == '1'):

			$update_params['billed'] = 0;

		endif;

		$plat_response = $this->platform->post('ubersmith/package/update/'.$domain_pack_id, $update_params);

		if ( ! $plat_response['success']):

			$this->platform->post(
				'ubersmith/renewals/dom_only_store_error/'.$renewal_id,
				array(
					'error' => 'Unable to update core domain pack info'
				)
			);

			redirect(site_url('billing/declined'));
			return;

		endif;


		$this->platform->post('ubersmith/renewals/dom_only_opt_in/'.$renewal_id);	

		$esp_response = $this->platform->post(
			'esp/move',
			array(
				'list'  => 'clients',
				'email' => $response['data']['row']['email'],
			)
		);

		$response = $this->platform->post(
			'ubersmith/package/add_renewal_bundle/'.$client_id,
			array(
				'price'         => $price,
				'discount'      => $discount,
				'discount_type' => 1,
				'period'        => 0,
				'desserv'       => 'Premium Renewal Bundle'
			)
		);

		if ( ! $response['success']):

			$this->platform->post(
				'ubersmith/renewals/dom_only_store_error/'.$renewal_id,
				array(
					'error' => 'Unable to add renewal bundle'
				)
			);

			redirect(site_url('billing/declined'));
			return;

		endif;


		$renew_pack_id = $response['data'];

		$response = $this->platform->post(
			'ubersmith/invoice/generate/'.$client_id,
			array(
				'packs' => array(
					$renew_pack_id,
					//$domain_pack_id
				)
			)
		);


		if ( ! $response['success']):

			$this->platform->post(
				'ubersmith/renewals/dom_only_store_error/'.$renewal_id,
				array(
					'error' => 'Unable to generate invoice'
				)
			);

			redirect(site_url('billing/declined'));
			return;

		endif;


		$invoice_id = $response['data']['invid'];

		$charge_response = $this->platform->post(
			'ubersmith/invoice/charge/'.$invoice_id
		);

		$paid = $charge_response['success'];

		$response = $this->platform->post(
			'ubersmith/renewals/dom_only_update_invoice_id/'.$renewal_id.'/'. $invoice_id.'/'. (($paid) ? '1' : '0')
		);

		if ( ! $paid):

			$esp_response = $this->platform->post(
				'esp/move',
				array(
					'list'  => 'declines',
					'email' => $response['data']['row']['email'],
				)
			);

			redirect(site_url('billing/declined'));
			return;

		endif;

		redirect(site_url('completed/sale/completed/invoice/'.$invoice_id));
		return;


	}

	function _generate_link($renewal_id, $client_id, $pack_id, $email_attempt)
	{

		$pack_key = $this->_generate_pack_key($pack_id);

		if ( ! $pack_key):

			return FALSE;

		endif;

		$link = site_url('renewals/process/'.$email_attempt.'/'.$renewal_id.'/'.$client_id.'/'.$pack_key);

		return $link;

	}



	function _generate_pack_key($pack_id)
	{

		$pack_key = substr(hash('sha256',$pack_id),0,8);

		return $pack_key;

	}


}
