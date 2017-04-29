<?php
 
class Signup extends MX_Controller
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

	public function index($page='register_domain_name',$error=FALSE)
	{
		// initialize variables
		$data	= array();

		// if data was submitted, then update account information
		if ($this->input->post())	return $this->_submit($page);

		// grab step information
		$step 	= $this->platform->post('partner/step/get_by_slug',array('slug' => $page));

		// if step doesn't exist, then show nothing
		if ( ! $step['success'] OR empty($step['data']))	return $this->_no_exist();

		// initialize variables
		$name 	= $step['data']['name'];

		// set template layout to use
		$this->template->set_layout('partner_setup');
		
		// set the page's title
		$this->template->title($name);

		// set data variables
		$data['error']		= urldecode($error);
		$data['name']		= $name;
		$data['slug']		= $page;
		$data['partner']	= $this->_partner;


		$js = 'modules/steps/assets/js/'.$page.'.js';

		if (file_exists(APPPATH.$js)):

			$footermeta = '<script type="text/javascript" src="/resources/'.$js.'"></script>';
			$this->template->prepend_footermeta($footermeta);
		
		endif;

		/*
		// see if this page has custom js file
		if (file_exists(APPPATH.'modules/steps/assets/js/'.$page.'.js'))
			$this->template->prepend_footermeta('<script type="text/javascript" src="/resources/modules/steps/assets/js/'.$page.'.js"></script>');
		*/
		
		// load view
		$this->template->build('steps/'.$page, $data);
	}

	/**
	 * This submit functionality that needs ran for each step
	 * @return [type] [description]
	 */
	private function _submit($type=FALSE)
	{
		// error handling
		if ( ! $type)	return $this->_go_home();

		// initialize variables
		$method 	= '_submit_'.$type;
        
		$current_order = $this->session->userdata('current_orderid');
		
		// if processing the order and orderid already exists change the method below
		$current = empty ($current_order) ?  false : true;
		$method = ($type == 'pay_for_domain_name' &&  $current  ) ? '_submit_retry_for_domain' :$method;
        // run this page's submit function
        return $this->$method();
	}

	/**
	 * This method is to show nothing in case step isn't found or has been completed
	 * @return [type] [description]
	 */
	private function _no_exist()
	{
		return;
	}

	private function _submit_register_domain_name()
	{
		// load domain validation library
		$this->load->library('domain_validation');

		// initialize variables
		$type 		= $this->input->post('type');
		$sld	 	= $this->input->post('sld');
		$tld 		= $this->input->post('tld');
		$company 	= $this->input->post('company');

		// if sld has a . then user is submitting a domain suggestion - set proper variables
		if ($type == 'suggestions' AND preg_match('/./',$sld)):

			// set variables
			$exploded 	= explode('.',$sld);
			$sld 		= $exploded[0];
			$tld 		= $exploded[1];

		endif;

		// if user is registering domain, we need to check availability
		if ($type == 'register' OR $type == 'suggestions'):

			// make sure this domain is still available
			if ( ! $this->domain_validation->available($sld,$tld)):

				// show error
				return $this->_show_error('register_domain_name','Domain is no longer available.');

			endif;	// end making sure domain is available

		endif;	// end seeing if type is register

		// add this domain as the partner's website
		$website 	= $this->_update_website($company,$sld,$tld, $type);

		// if there was an error adding website information, show error
		if ( ! $website['success']):

			// show error
			return $this->_show_error('register_domain_name', 'There was an error updating your website details.');

		endif;

		// mark step as completed
		$this->_complete('register_domain_name');
		
		// redirect to home page
		return $this->_go_home();
	}
        
    private function _submit_edit_payment_settings(){
        
    	// initialize variables
    	$post 	= $this->input->post(NULL,TRUE);

        // set the type
        $type = $post['radPayment'];
        switch ($type):
			case "paypal":

				// initialize variables
				$update 	= array(
					'partner_id'   => $this->_partner['id'],
					'type'         => 'paypal',
					'paypal_email' => $post['paypal_email']
				);

				// update payment method details
				$pay_method 	= $this->platform->post('partner/account/update_payment_settings',$update);
				
				// if there was an error, return it
				if ( ! $pay_method['success'] OR ! $pay_method['data'])	return $this->_show_error('edit_payment_settings', 'There was an error updating PayPal payment method details.');

				break;
			case "check":

				// initialize variables
				$update 	= array(
					'partner_id'    => $this->_partner['id'],
					'type'          => 'check',
					'name_on_check' => $post['name_on_check']
				);

				// update payment method details
				$pay_method 	= $this->platform->post('partner/account/update_payment_settings',$update);
				
				// if there was an error, return it
				if ( ! $pay_method['success'] OR ! $pay_method['data'])	return $this->_show_error('edit_payment_settings', 'There was an error updating Check payment method details.');
				
				break;
			case "bank_wire": 
                            // skip and use same api call
                        case 'direct_deposit' :

				// initialize variables
				$update 	= array(
					'partner_id'       => $this->_partner['id'],
					'type'             => $type,
					'bank_name'        => $post['bank_name'],
					'branch_name'      => $post['branch_name'],
					'beneficiary_name' => $post['beneficiary_name'],
					'account_number'   => $post['account_number'],
					'routing_number'   => $post['routing_number'],
					'swift_code'       => $post['swift_code']
				);

				// update payment method details
				$pay_method 	= $this->platform->post('partner/account/update_payment_settings',$update);
				
				// if there was an error, return it
				if ( ! $pay_method['success'] OR ! $pay_method['data'])	return $this->_show_error('edit_payment_settings', 'There was an error updating Bank Wire payment method details.');
				
				break;
			default:
				// return error
				return 'Invalid Payment Method Type.';
				break;
		endswitch;

		// mark step as completed
		$this->_complete('edit_payment_settings');

		// return user to home page
		return $this->_go_home();
    }
	
	private function _submit_pay_for_domain_name(){
        
    	// initialize variables
    	$post		= $this->input->post(NULL,TRUE);


    	### HACK ADDED VIA RYAN ON 02/27/2013
    	if ($this->input->post('txtCardNum') == '4111111111111111'):

			// mark step as completed
			$this->_complete('pay_for_domain_name');

			// return user to home page
			return $this->_go_home();

    	endif;
    	### END HACK


		
		$website	= $this->platform->post('partner/website/details', array('partner_id'=>$this->_partner['id']));
		
		if (!$post['txtFName'] || !$post['txtLName'] || !$post['txtEmail']
			|| !$post['txtState'] || !$post['txtAddress'] || !$post['txtCity']
			|| !$post['txtZip'] || !$post['txtCardNum'] || !$post['txtCVV'])
		{
			return $this->_show_error('pay_for_domain_name', 'Please fill out all Billing Information.');
		}
		
		$this->load->library('password');


		$cart_post  = array
		(
			'type'            => 'order',
			#'order_queue_id' => 7,
			'first_name'      => $this->_partner['first_name'],
			'last_name'       => $this->_partner['last_name'],
			'company'         => $this->_partner['company'],
			'core_domain'     => $website['data']['domain'],
			'user'            => $this->_partner['username'],
			'pass'            => $this->password->decrypt($this->_partner['password'], $this->_partner['password_salt']),
			'addr'            => $this->_partner['address'],
			'city'            => $this->_partner['city'],
			'state'           => $this->_partner['state'],
			'zip_code'        => $this->_partner['zip'],
			'country'         => $this->_partner['country'],
			'email'           => $this->_partner['email'],
			'phone'           => $this->_partner['phone'],
			//'ach_acct'      => $this->_partner['payment_method']['account_number'],
			//'ach_aba'       => $this->_partner['payment_method']['routing_number'],
			//'ach_type'      => $this->_partner['payment_method']['type'],
			//'ach_bank'      => $this->_partner['payment_method']['bank_name'],
			'ip_address'      => $_SERVER['REMOTE_ADDR'],
			'partner_id'      => $this->_partner['id'],
		);
		
		
		
		$cart        = $this->platform->post('ubersmith/order/create/partner', $cart_post);
		$cart        = $cart['data'];
		
		
		$ordsess = array('current_orderid'=>$cart['order_id']);
		$this->session->set_userdata('current_orderid',$cart['order_id']); 
		
		
		//var_dump($cart);
		
		$price			= 8.50;
		if ($website['data']['domain_type'] == 'transfer')
		{
			$price		= 0.00;
		}
		
		$domain_params = array
		(
			'order_id'           => $cart['order_id'],
			'num_years'          => '1',
			'tld'                => substr($website['data']['domain'], strpos($website['data']['domain'], '.')+1),
			'sld'                => substr($website['data']['domain'], 0, strpos($website['data']['domain'], '.')),
			'privacy'            => '1',
			'type'               => $website['data']['domain_type'],
			'price'              => $price,
			'setup_fee'          => '0.00',
			
			'build_version_id'   => 1,
			'build_type'         => 'all_phase',
			'build_type_version' => '1.0'
		);
		$domain_results = $this->platform->post('ubersmith/order/add_core_domain', $domain_params);
		
		$host_params = array
		(
			'order_id'       => $cart['order_id'],
			'base_domain'	 => $website['data']['domain'],
			'setup_fee'      => '0.00',
			'trial_discount' => '0.00',
			'monthly_price'  => '0.00',
			'num_months'     => 12
		);
		$host_results  = $this->platform->post('ubersmith/order/add_hosting/partner', $host_params);
		
		
		$credit_params  = array
		(
			'order_id'   => $cart['order_id'],
			'cc_num'     => $post['txtCardNum'],
			'cc_mo'      => str_pad(intval($post['selExpMonth']),2,"0",STR_PAD_LEFT),
			'cc_yr'      => str_pad(intval(substr($post['selExpYear'],-2)),2,"0",STR_PAD_LEFT),
			'cc_cvv2'    => $post['txtCVV'],
			'cc_first'   => $post['txtFName'],
			'cc_last'    => $post['txtLName'],
			'cc_address' => $post['txtAddress'],
			'cc_city'    => $post['txtCity'],
			'cc_state'   => $post['txtState'],
			'cc_zip'     => $post['txtZip'],
			'cc_country' => $post['selCountry'],
			'cc_phone'   => $this->_partner['phone']
		);
		$credit_results = $this->platform->post('ubersmith/order/add_credit_card', $credit_params);
		
		
		/*
		$submit_results = $this->platform->post('ubersmith/order/submit/'.$cart['order_id']);
		*/
	
		$post	= array(
			'type'				=> 'order',
			'_id'				=> $cart['order_id'],
			'order_action_id'	=> 'add_services',
			'queue_type'        => 'partner'
		);
		
		// submit sale
		$submit_results	= $this->platform->post('crm/cart/submit',$post);




		#### Verifying that the order was paid
		$api  = 'ubersmith/order/process/verify_payment/'.$cart['order_id'].'/0/partner';
		
		$resp = $this->platform->post($api);
		
		$submit_results['success']                   = FALSE;
		$submit_results['data']['submit']['success'] = FALSE;

		if ($resp['success']):

			$submit_results['success']                   = TRUE;
			$submit_results['data']['submit']['success'] = TRUE;

		endif;
		#### End payment verification
		

		
		
		$order_info		= $this->platform->post('ubersmith/order/get', array('order_id'=>$cart['order_id']));
		$order_info		= $order_info['data'];

		
		$uber_client_id	= $this->platform->post('partner/account/update_uber_client_id',
			array('partner_id'=>$this->_partner['id'], 'uber_client_id'=>$order_info['client_id'])
		);
		
		if ( ! $submit_results['success'] || ! $submit_results['data']['submit']['success'])
		{
			return $this->_show_error('pay_for_domain_name', 'There was a problem processing your payment. Please make sure you filled out correct Credit Card Information.');
		}
		
		$process_results = $this->platform->post('ubersmith/order/process/register_domain/'.$cart['order_id'].'/0/partner');

		// mark step as completed
		$this->_complete('pay_for_domain_name');

		// return user to home page
		return $this->_go_home();
    }

	private function _submit_retry_for_domain(){
		
		$post 	= $this->input->post(NULL,TRUE);
		
		$website = $this->platform->post('partner/website/details', array('partner_id'=>$this->_partner['id']));
		
		if (!$post['txtFName'] || !$post['txtLName'] || !$post['txtEmail']
			|| !$post['txtState'] || !$post['txtAddress'] || !$post['txtCity']
			|| !$post['txtZip'] || !$post['txtCardNum'] || !$post['txtCVV'])
		{
			return $this->_show_error('pay_for_domain_name', 'Please fill out all Billing Information.');
		}
		
		$current_order = $this->session->userdata('current_orderid');
		$current_order = empty($current_order) ? false :true;
		// check to see if there is a current order in session if there is not create an order below
		
		// set the current orderid and update card because the order has failed
		$cart['order_id'] = $this->session->userdata('current_orderid');
		
		// get current order info.
		$order_info		= $this->platform->post('ubersmith/order/get', array('order_id'=>$cart['order_id']));
		$order_info		= $order_info['data'];
		
		// get client invo
		$client_info = $this->platform->post('ubersmith/client/get',array('name'=>'id',"client_id"=>$order_info['client_id']));
		
		// update credit card on file
		$credit_params  = array
		(
			'client_id'  => $order_info['client_id'],
			'cc_num'     => $post['txtCardNum'],
			'cc_expire'   => str_pad(intval($post['selExpMonth']),2,"0",STR_PAD_LEFT).str_pad(intval(substr($post['selExpYear'],-2)),2,"0",STR_PAD_LEFT),
			'cc_cvv2'    => $post['txtCVV'],
			'fname'   => $post['txtFName'],
			'lname'    => $post['txtLName'],
			'address' => $post['txtAddress'],
			'city'    => $post['txtCity'],
			'state'   => $post['txtState'],
			'zip'     => $post['txtZip'],
			'country' => $post['selCountry'],
			'phone'   => $this->_partner['phone'],
			'email'	  => $this->_partner['email']
		);
		
		$current_cc = isset($order_info['info']['pack0']['billing_info_id']) ? $order_info['info']['pack0']['billing_info_id'] : false;
		
		$invid = isset($order_info['info']['invid']) ? $order_info['info']['invid'] : false;
		
		// if there is no invoice id exit
		if( ! $invid) :			
			return $this->_show_error('pay_for_domain_name', 'There was a problem processing your payment. Invoice not found.');
		endif;
		
		
		// add credit card.
		$credit_results = $this->platform->post('ubersmith/credit_cards/add', $credit_params);
		// set credit card for processing
		$billing_info_id = ! $credit_results['data'] ? FALSE : $credit_results['data'];
		
		// delete the packid credit card on file
		$deletecard = $this->platform->post('ubersmith/credit_cards/delete',array('billing_info_id'=>$current_cc));
		
		
		$post	= array(
			'billing_info_id'				=> $billing_info_id
		);
		
		// charge the invoice
		$submit_results	= $this->platform->post('ubersmith/invoice/charge/'.$invid,$post);
		/*echo"<pre>";print_r($order_info);print_r($client_info);
		var_dump($invid);
		var_dump($current_cc);
		var_dump($credit_results);
		var_dump($deletecard);
		var_dump($post);
		var_dump($submit_results);
		echo "</pre>";
		 */
		
		if ( ! $submit_results['success'] )
		{
			return $this->_show_error('pay_for_domain_name', 'There was a problem processing your payment. Please make sure you filled out correct Credit Card Information.');
		}
		if( $submit_results['success']) :
			$process_results = $this->platform->post('ubersmith/order/process/verify_payment/'.$cart['order_id'].'/0/partner');
		endif;
		$register = $this->platform->post('ubersmith/order/process/register_domain/'.$cart['order_id'].'/0/partner');
		
		
		// mark step as completed
		$this->_complete('pay_for_domain_name');

		// return user to home page
		return $this->_go_home();
	}

    /**
     * This method updates a partner's website details
     * @param  [type] $company [description]
     * @param  [type] $sld     [description]
     * @param  [type] $tld     [description]
     * @return [type]          [description]
     */
    private function _update_website($company,$sld,$tld,$type='register')
    {
		// create array of fields to update
		$update 		= array(
			'partner_id'	=> $this->_partner['id'],
			'company_name'	=> $company,
			'domain'		=> $sld.'.'.$tld,
			'logo_type'		=> 'text',
			'logo'			=> ucwords($company),
			'domain_type'   => $type
		);
		
		$this->_partner['domain_type'] = $type;
		$this->session->set_userdata('partner', $this->_partner);

		// update partner website details
		return $this->platform->post('partner/website/update',$update);
    }

    /**
     * This method shows a page error
     * @param  [type] $page  [description]
     * @param  [type] $error [description]
     * @return [type]        [description]
     */
    private function _show_error($page,$error)
    {
		// remove POST values
		unset($_POST);

		// display domain not available error
		redirect('/steps/signup/index/'.$page.'/'.$error);
		return;
    }

    /**
     * This method redirects a user to the home page
     * @return [type] [description]
     */
    private function _go_home()
    {
    	//redirect('home');
    	header('Location:https://partner.allphasehosting.com/home');
    	return;
    }

    /**
     * This method marks a step as completed
     * @param  [type] $step [description]
     * @return [type]       [description]
     */
    private function _complete($step)
    {
    	$completed 	= $this->platform->post('partner/step/complete',array('partner_id' => $this->_partner['id'], 'slug' => $step));
    	
    	return;
    }
    
}
