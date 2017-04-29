<?php

error_reporting(0);

class Opt extends MX_Controller {

	protected 
		$_errors = array(),
		$_params = array(
			'rebill_id' => FALSE,
			'client_id' => FALSE
		),
		$_post   = array(),
		$_email  = FALSE,
		$_record = array();

	function __construct()
	{

		parent::__construct();

		$this->load->helper('email');
		$this->load->helper('url');
		
	}

	function cron()
	{

		
		$resp = $this->platform->post(
			'ubersmith/rebilling/cron'
		);

		echo '<pre>';
		var_dump($resp);
		echo '</pre>';

		//@mail('travis.loudin@brainhost.com','BRAIN HOST Rebilling cron',json_encode($resp));
	}

	function _validate($params)
	{

		$keys = array(
			'rebill_id', 
			'client_id'
		);

		foreach ($keys as $key):

			if ($this->session->userdata($key)):

				$this->_params[$key] = $this->session->userdata($key);

			endif;

			if (isset($params[$key]) && is_numeric($params[$key])):

				$this->_params[$key] = $params[$key];

			endif;

			if ( ! $this->_params[$key]):

				$this->_errors[] = 'Invalid '.implode(' ',explode('_',$key));

			endif;

		endforeach;

		$this->_post = $this->input->post(NULL, TRUE);

		if ($this->_post):
				
			if (empty($this->_errors)):

				$resp  = $this->platform->post(
					'ubersmith/rebilling/get_rebill_row',
					array(
						'rebill_id' => $this->_params['rebill_id'],
						'client_id' => $this->_params['client_id']
					)
				);

				if ( ! $resp['success']):

					$data['errors'] = $resp['error'];

				else:

					$this->_record = $resp['data']['rebill_record'];

				endif;

			endif;

			$this->_email = isset($this->_post['email']) ? $this->_post['email'] : FALSE;

			if ( ! $this->_email || ! valid_email($this->_email)):

				$this->_errors[] = 'Invalid email';

			endif;

			$this->_params['email'] = $this->_email;

		endif;

	}

	function _store_lead()
	{

		$record = $this->_record;
		
		$slug   = isset($record['meta']['affiliate_id']) && $record['meta']['affiliate_id'] ? 'bizops' : 'hosting';
		$key    = 'wTgb+W6djt3AJDHZev6H2pfFkUCL5d2JRPholsDc270=';
		$email  = $this->_email;
		$phone  = $record['phone'];
		$fname  = $record['first'];
		$lname  = $record['last'];
		$ip     = isset($record['meta']['client_ip_address']) ? $record['meta']['client_ip_address'] : $_SERVER['REMOTE_ADDR'];
		$addy   = $record['address'];
		$city   = $record['city'];
		$state  = $record['state'];
		$cntry  = $record['country'];
		$zip    = $record['zip'];

		$params = array(
			'vertical_slug' => $slug,
			'key'			=> $key,
			'email'			=> $email,
			'phone'			=> $phone,
			'first'			=> $fname,
			'last'			=> $lname,
			'ip'			=> $ip,
			'address'		=> array(
				'address1' => $addy,
				'city'     => $city,
				'state'    => $state,
				'zip'      => $zip,
				'country'  => $cntry,
			)
		);

		$resp = $this->curl->post(
			'http://platform.socialmediamanagementservices.com/lead/add',
			$params
		);

	}

	function in($rebill_id, $client_id) {

		$this->_validate(
			$params = array(
				'rebill_id' => $rebill_id,
				'client_id' => $client_id
			)
		);

		$data = array();

		if ($this->_post):

			$data['errors'] = $this->_errors;

			if (empty($this->_errors)):
		
				$resp = $this->platform->post(
					'ubersmith/rebilling/opt_in',
					$this->_params
				);

				if ($resp['success']):

					$this->_store_lead();

					redirect('opt/thanks');
					return;

				endif;

				$data['errors'] = $resp['error'];

			endif;

		endif;

		$data['noexitpop'] = true;
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Congratulations on keeping your domain for another year!');
		
		// append the CSS file
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/optin.css" type="text/css" />');
		
		// load view
		$this->template->build('optin', $data);

	}

	function out($rebill_id, $client_id) {

		$this->_validate(
			$params = array(
				'rebill_id' => $rebill_id,
				'client_id' => $client_id
			)
		);

		// initialize variables
		$data	= array();

		if ($this->_post):

			$data['errors'] = $this->_errors;

			if (empty($this->_errors)):
		
				$this->_params['reason'] = $this->input->post('reason');

				$resp = $this->platform->post(
					'ubersmith/rebilling/opt_out',
					$this->_params
				);

				if ($resp['success']):

					$this->_store_lead();

					redirect('opt/thanks');
					return;

				endif;

				$data['errors'] = $resp['error'];

			endif;

		endif;
		
		$data['noexitpop'] = true;
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Congratulations on keeping your domain for another year!');
		
		// append the CSS file
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/optin.css" type="text/css" />');
		
		// load view
		$this->template->build('declined', $data);

	}

	function thanks()
	{
		$data['noexitpop'] = true;
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Thanks!');
		
		// append the CSS file
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/optin.css" type="text/css" />');
		
		// load view
		$this->template->build('thanks', $data);
	}

}
