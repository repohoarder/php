<?php

class Account extends MX_Controller
{
	/**
	 * The array of partner information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}

	public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();

		// if data was submitted, then update account information
		if ($this->input->post())	return $this->_submit();
		
		// grab partner account details
		$details 	= $this->platform->post('partner/account/details',array('partner_id' => $this->_partner['id']));
		$website 	= $this->platform->post('partner/website/details',array('partner_id' => $this->_partner['id']));
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Manage Account');

		// set data variables
		$data['error']		= urldecode($error);
		$data['details']	= $details['data'][0];
		$data['website']	= $website['data'];
		
		// load view
		$this->template->build('manage/account', $data);
	}

	private function _submit()
	{
		// initialize variables
		$info['partner_id']		= $this->_partner['id'];
		$info['company']		= $this->input->post('company');
		//$info['website']		= $this->input->post('website');
		$info['first_name'] 	= $this->input->post('first_name');
		$info['last_name']		= $this->input->post('last_name');
		$info['email']			= filter_var($this->input->post('email'), FILTER_SANITIZE_EMAIL);	// sanitize email
		$info['address']		= $this->input->post('address');
		$info['city']			= $this->input->post('city');
		$info['state'] 			= $this->input->post('state');
		$info['zip'] 			= $this->input->post('zip');
		$info['phone'] 			= preg_replace("/\D/","",$this->input->post('phone'));				// remove non numeric characters
		$info['split_test']		= $this->input->post('split_tests');
		$pass['old']			= $this->input->post('old_password');
		$pass['new']			= $this->input->post('new_password');
		$pass['confirm']		= $this->input->post('confirm_password');
		$payment['type'] 		= $this->input->post('payment_type');

		#######################################################################
		## Update Account Information
		$update 	= $this->_update_account_info($info);

		// make sure update was successful
		if ($update !== TRUE):
			// redirect user with error message
			redirect('manage/account/'.$update);
			return;
		endif;

		// we need to update partner session with new partner details
		$this->_update_partner_session();

		#######################################################################


		#######################################################################
		## Change Password
		// see if user is attempting to update password
		if ($pass['old'] AND $pass['new'] AND $pass['confirm']):

			// update password
			$password 	= $this->_update_password($pass);

			if ($password !== TRUE):
				// redirect user with error message
				redirect('manage/account/'.$password);
			endif;

		endif;	// end seeing if user is attempting to update password
		#######################################################################


		#######################################################################
		## Update Payment Settings
		$settings 	= $this->_update_payment_method($payment['type'],$this->input->post());

		// make sure update was successful
		if ($settings !== TRUE):
			// redirect user with error message
			redirect('manage/account/'.$settings);
		endif;
		#######################################################################
		

		// redirect back to page with success message
		redirect('manage/account/Account details have been updated successfully.');
	}

	private function _update_partner_session()
	{
		// get partner details
		$partner 	= $this->platform->post('partner/account/details',array('partner_id' => $this->_partner['id']));

		if ( ! $partner['success'] OR ! $partner['data'])	return FALSE;

		// set needed sessions
		$this->session->set_userdata('partner',$partner['data'][0]);

		return TRUE;
	}

	/**
	 * This method updates a aprtner's account details
	 * @param  [type] $info [description]
	 * @return [type]       [description]
	 */
	private function _update_account_info($info)
	{

		// update account information
		$account 	= $this->platform->post('partner/account/update_details',$info);

		// if update was unsuccessful, then return error message
		if ( ! $account['success'] OR ! $account['data'])	return 'There was an error updating account details.';

		return TRUE;
	}

	/**
	 * This method attempts to update a user's password
	 * @param  array  $pass [description]
	 * @return [type]       [description]
	 */
	private function _update_password($pass=array())
	{
		// make sure old password is valid
		$valid 		= $this->platform->post('partner/account/valid_password',array('partner_id' => $this->_partner['id'], 'password' => $pass['old']));

		// make sure was valid password for this partner
		if ( ! $valid['success'] OR ! $valid['data'])	return 'Invalid OLD Password.';

		// make sure new password is == to confirm password
		if ($pass['new'] != $pass['confirm'])			return 'New & Confirm Password fields did not match.';

		// generate password
		$generate 				= $this->password->generate($pass['new']);

		// add partner id to generate array
		$generate['partner_id']	= $this->_partner['id'];

		// update password
		$update 				= $this->platform->post('partner/account/update_password',$generate);

		// make sure there was no error updating password
		if ( ! $update['success'] OR ! $update['data'])	return $update['error'];	// error returned from API

		// return
		return TRUE;
	}

	/**
	 * This method attempts to update a partner's payment method
	 * @param  boolean $type [description]
	 * @param  array   $post [description]
	 * @return [type]        [description]
	 */
	private function _update_payment_method($type=FALSE,$post=array())
	{
		// see which payment method type we are attempting to update
		switch ($type):
			case "paypal":

				// initialize variables
				$update 	= array(
					'partner_id'	=> $this->_partner['id'],
					'type'			=> 'paypal',
					'paypal_email'	=> $post['paypal_email']
				);

				// update payment method details
				$pay_method 	= $this->platform->post('partner/account/update_payment_settings',$update);
				
				// if there was an error, return it
				if ( ! $pay_method['success'] OR ! $pay_method['data'])	return 'There was an error updating PayPal payment method details.';

				break;
			case "check":

				// initialize variables
				$update 	= array(
					'partner_id'	=> $this->_partner['id'],
					'type'			=> 'check',
					'name_on_check'	=> $post['name_on_check']
				);

				// update payment method details
				$pay_method 	= $this->platform->post('partner/account/update_payment_settings',$update);
				
				// if there was an error, return it
				if ( ! $pay_method['success'] OR ! $pay_method['data'])	return 'There was an error updating Check payment method details.';
				
				break;
			case "bank_wire":

				// initialize variables
				$update 	= array(
					'partner_id'		=> $this->_partner['id'],
					'type'				=> 'bank_wire',
					'bank_name'			=> $post['bank_name'],
					'branch_name'		=> $post['branch_name'],
					'beneficiary_name'	=> $post['beneficiary_name'],
					'account_number'	=> $post['account_number'],
					'routing_number'	=> $post['routing_number'],
					'swift_code'		=> $post['swift_code']
				);

				// update payment method details
				$pay_method 	= $this->platform->post('partner/account/update_payment_settings',$update);
				
				// if there was an error, return it
				if ( ! $pay_method['success'] OR ! $pay_method['data'])	return 'There was an error updating Bank Wire payment method details.';
				
				break;
			case "direct_deposit":

				// initialize variables
				$update 	= array(
					'partner_id'		=> $this->_partner['id'],
					'type'				=> 'direct_deposit',
					'bank_name'			=> $post['bank_name'],
					'branch_name'		=> $post['branch_name'],
					'beneficiary_name'	=> $post['beneficiary_name'],
					'account_number'	=> $post['account_number'],
					'routing_number'	=> $post['routing_number'],
					'swift_code'		=> $post['swift_code']
				);

				// update payment method details
				$pay_method 	= $this->platform->post('partner/account/update_payment_settings',$update);
				
				// if there was an error, return it
				if ( ! $pay_method['success'] OR ! $pay_method['data'])	return 'There was an error updating Direct Deposit payment method details.';

				break;
			default:
				// return error
				return 'Invalid Payment Method Type.';
				break;
		endswitch;

		return TRUE;
	}
}