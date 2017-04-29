<?php

class Verify extends MX_Controller {

	protected 
		$_errors         = array(),
		$_type           = '',
		$_id             = '',
		$_bill_action_id = '',
		$_bill_conv_amt  = 0,
		$_partner_id     = 1,
		$_funnel_version = 0,
		$_affiliate_id   = 0,
		$_offer_id       = 0,
		$_session_key,
		$_max_codes      = 6;

	function __construct()
	{

		parent::__construct();
		
		$this->_type           = $this->session->userdata('_type');
		$this->_id             = $this->session->userdata('_id');
		
		$this->_funnel_version = $this->session->userdata('funnel_id');
		$this->_partner_id     = $this->session->userdata('partner_id');
		
		$this->_affiliate_id   = $this->session->userdata('affiliate_id');
		$this->_offer_id       = $this->session->userdata('offer_id');
		
		$this->_session_key    = $this->session->userdata('session_id');
		
		$this->_bill_action_id = $this->session->userdata('billing_action_id');
		$this->_bill_conv_amt  = $this->session->userdata('billing_conversion_amt');
		
		$this->_order          = FALSE;

		// load language files
		$this->lang->load('verify',$this->session->userdata('_language'));
		$this->lang->load('footer',$this->session->userdata('_language'));

		if ($this->_type == 'order' && $this->_id):

			$order_resp = $this->platform->post(
				'ubersmith/order/get/',
				array(
					'order_id' => $this->_id
				)
			);

			if ( ! $order_resp['success']):

				$this->_success();
				return;

			endif;

			$this->_order = $order_resp['data'];

		endif;

	}

	function test()
	{

		$this->session->set_userdata('_id','349184');
		$this->session->set_userdata('_type','order');

		echo 'whoop';
	}

	function _format_phone($phone, $country = 'US')
	{

		$phone = preg_replace('/[^0-9\+]/','',$phone);

		if ( ! $phone):

			return FALSE;

		endif;

		if (substr($phone,0,1) == '+'):

			return $phone;

		endif;


		$phone_code = $this->platform->post(
			'five9/phone/get_country_code',
			array(
				'country' => $country
			)
		);

		$prefix = '1';

		if ($phone_code['success']):

			$prefix = $phone_code['data']['code'];

		endif;

		$phone = '+'.$prefix.$phone;

		return $phone;

	}

	function index()
	{

		if ( ! $this->_order):

			$this->_success();
			return;

		endif;

		$score = $this->_order['info']['minfraud']['riskScore'];

		$thresh_resp = $this->platform->post(
			'maxmind/get_score_threshold',
			array(
				'risk_score' => $score
			)
		);

		if ( ! $thresh_resp['success'] || $thresh_resp['data']['threshold'] == 'none'):

			$this->_success();
			return;

		endif;

		if ($thresh_resp['data']['threshold'] == 'phone'):

			redirect('verify/phone');
			return;

		endif;

		redirect('verify/manual');
		return;

	}


	function manual()
	{

		$allowed_sessions = array(
			'session_id',
			'last_activity',
			'ip_address',
			'partner_info',
			'user_agent',
			'visitor_id',
			'partner_funnel_info',
			'partner_id'
		);

		$current_sessions = $this->session->all_userdata();

		if (is_array($current_sessions) && ! empty($current_sessions)):

			$current_sessions = array_diff_key($current_sessions, array_flip($allowed_sessions));

			foreach ($current_sessions as $key => $value):

				$this->session->unset_userdata($key);

			endforeach;

		endif;

		$dom = explode('.',$_SERVER['HTTP_HOST']);
		array_shift($dom);
		$dom = implode('.',$dom);
		
		$ticket_add = $this->platform->post(
			'ubersmith/ticket/add',
			array(
				'client_id'	=> 0,
				'subject'	=> 'Manual Verification Order# : '.$this->_order['order_id'],
				'body'		=> "The following order #".$this->_order['order_id']." requires manual review. Visit http://my.".$dom."/admin/ordermgr/order_view.php?order_id=".$this->_order['order_id']." for more details.",
				'queue'		=> 12
			)
		);

		$page = 'manual';
		
		$this->tracking->page_hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => $page,
			)
		);

		$data['errors']       = $this->_errors;
		$data['partner_info'] = $this->session->userdata('partner_info');

		$this->template->set_layout('bare');
		$this->template->title('Manual Verification');
		$this->template->build('verify_manual', $data);


	}


	function _call($phone)
	{

		if ( ! $phone):

			$this->_errors[] = 'Please enter a valid phone number.';
			return FALSE;

		endif;

		$call_resp = $this->platform->post(
			'maxmind/call_phone',
			array(
				'phone' => $phone
			)
		);
		
		if ( ! $call_resp['success']):

			$this->_errors = array_merge($call_resp['error'],$this->_errors);
			return FALSE;

		endif;

		return TRUE;


	}

	function phone()
	{

		$phone = str_replace('+1.','',$this->_order['info']['phone']);

		$phone = ($this->input->post('verify_phone')) ? $this->input->post('phone') : $phone;		

		$phone = $this->_format_phone($phone, $this->_order['info']['country']);

		if ($this->input->post('verify_phone')):

			$success = $this->_call($phone);

			if ($success):

				$this->session->set_userdata('verify_phone',$phone);

				redirect('verify/code');
				return;

			endif;

		endif;

		$page = 'phone';

		$this->tracking->page_hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => $page,
			)
		);
		

		$data['phone_number'] = $phone;
		$data['errors']       = $this->_errors;

		$this->template->set_layout('bare');

		$this->template->title('Phone Verification');

		$this->template->build('verify_phone', $data);

	}

	function _check_code($phone, $code)
	{

		$resp = $this->platform->post(
			'maxmind/entered_code',
			array(
				'phone'        => $phone,
				'entered_code' => $code
			)
		);
		

		if ( ! $resp['success']):

			$this->_errors = array_merge($resp['error'],$this->_errors);
			return FALSE;

		endif;

		if ($resp['data']['is_valid']):

			return TRUE;

		endif;		

		$this->_errors[] = 'Invalid verification code. Please try again.';
		return FALSE;
	}

	function code()
	{

		$phone = $this->session->userdata('verify_phone');

		$num_codes = intval($this->session->userdata('num_verify_codes'));

		if ($this->input->post('verify_code')):

			$num_codes++;
			$this->session->set_userdata('num_verify_codes',$num_codes);
			
			if ($num_codes > $this->_max_codes):

				redirect('verify/manual');
				return;

			endif;

			$code = $this->input->post('verify_code');

			$success = $this->_check_code($phone, $code);

			if ($success):

				$this->_success();
				return;

			endif;

		endif;

		$page = 'code';
		
		$this->tracking->page_hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => $page,
			)
		);


		$data['errors']       = $this->_errors;
		$data['phone_number'] = $phone;

		$this->template->set_layout('bare');

		$this->template->title('Enter Your Verification Code');

		$this->template->build('verify_code', $data);

	}

	function _success() 
	{

		$page = 'success';
		
		$this->tracking->page_hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => $page,
			)
		);

		$this->tracking->page_action(
			array(
				'visitor_id'        => $this->session->userdata('visitor_id'),
				'action_id'         => $this->_bill_action_id,
				'conversion'        => TRUE,
				'conversion_amount' => $this->_bill_conv_amt		
			)
		);


		$this->funnel->redirect_form_action($this->_partner_id, $this->_funnel_version, $this->_bill_action_id);
		return;
	}

}