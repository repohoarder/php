<?php

/*
NEEDED PLATFORM STUFF

	need to check if client, email exists, etc

	need to build in credits and discounts to cart/add
		(eventually)	

	customer blacklisting

	add order to dropoff table
		and cron running on platform to push them through

	need piwik modules to record order and upsells being added

*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billing extends MX_Controller {

	protected 
		$_errors          = array(),
		$_hosting_options = array(),
		$_hosting_prices,
		$_core_domain_prices,
		$_partner_id      = 1,
		$_partner_info    = array(),
		$_funnel_version  = 0,
		$_affiliate_id    = 0,
		$_offer_id        = 0,
		$_conversion_amt  = 0,
		$_session_key,
		$_billing_upsells = array(
			'domain_privacy' => 'Domain Privacy',
			'daily_backup'   => 'Automated Daily Backup',
			'weblock'        => 'Weblock Domain Security'
		),
		$_form_fields      = array(
			'first_name'    => '', 
			'last_name'     => '', 
			'address'       => '', 
			'city'          => '', 
			'state'         => '',
			'country'       => 'US',
			'zipcode'       => '',
			'phone'         => '',
			'cell'          => 'N/A',
			'email'         => '',
			'cc_num'        => '',
			'cc_exp_mo'     => '01',
			'cc_exp_yr'     => '14',
			'cc_security'   => '',
			'tos_agreement' => '1',
			'affiliate_id'  => 0,
			'offer_id'      => 0,
			'hosting'       => 'annual',
			'charity'       => 1,
			'cpf'			=> '',
			'street_number'	=> '',
			'district'		=> '',
			'bill_upsells'  => array()
		), 
		$_hosting_readable = array( # Might be able to nix this
			'monthly'     => 'Monthly',
			'biannual'    => 'Semiannual',
			'annual'      => 'Annual',
			'biennial'    => 'Biennial',
			'triennial'   => 'Triennial',
			'quadrennial' => 'Quadrennial',
		),
		$_terms    = array(
			'1'  => 'monthly',
			'6'  => 'biannual',
			'12' => 'annual',
			'24' => 'biennial',
			'36' => 'triennial',
			'48' => 'quadrennial',
		),
		$_percents = array(
			'monthly'     => 0,
			'biannual'    => 6,
			'annual'      => 13,
			'biennial'    => 20,
			#'triennial'   => 25,
			#'quadrennial' => 30,
		),
		$_upsell_prices;


	function __construct()
	{

		parent::__construct();

		$this->load->helper('url');
		$this->load->config('address_validation');

		$this->_funnel_version = $this->session->userdata('funnel_id');
		$this->_partner_id     = $this->session->userdata('partner_id');
		$this->_partner_info   = $this->session->userdata('partner_info');
		
		$this->_affiliate_id   = $this->session->userdata('affiliate_id');
		$this->_offer_id       = $this->session->userdata('offer_id');

		$this->_session_key    = $this->session->userdata('session_id'); # change this to NOT be CI sess key
		
		$core_type             = $this->session->userdata('core_type');
		
		$this->_form_fields['cc_exp_mo']    = date('m');
		$this->_form_fields['cc_exp_yr']    = date('Y');
		
		$this->_form_fields['bill_upsells'] = array_combine(
			array_keys($this->_billing_upsells),
			array_fill(0, count($this->_billing_upsells), 1)
		);

		$this->_set_hosting_options();

		$variant = ($core_type == 'dns' ? $core_type : 'default');
		$this->_set_core_domain_options($variant);

		$this->_set_upsell_prices();
		
	}


	function _add_billing_upsells($billing_upsells, $order_id, $core_dom_pack = FALSE)
	{

		if ( ! $order_id):

			return FALSE;

		endif;

		$count_response = $this->platform->post(
			'ubersmith/order/pack_count',
			array(
				'order_id' => $order_id
			)
		);

		if ( ! $count_response['success']):

			return FALSE;

		endif;

		$pack_count = $count_response['data'];

		foreach ($billing_upsells as $key => $checked):

			if ( ! $checked):

				continue;

			endif;

			$pack_count++;


			$params = array(
				'order_id'		=> $order_id,
				'info'			=> array(
					'pack'.$pack_count	=> array(
						'plan_id'	=> $this->_upsell_prices[$key]['annual']['plan_id'],
						'price'		=> $this->_upsell_prices[$key]['annual']['price'],
						'desserv'	=> $this->_billing_upsells[$key],
						'comment'	=> 'Added By HMVC Billing Funnel',
						'period'    => 12,
					)
				),
			);

			$this->_conversion_amt = $this->_conversion_amt + $this->_upsell_prices[$key]['annual']['price'];


			if ($core_dom_pack !== FALSE):

				$params['info']['pack'.$pack_count]['parentpack'] = 'pack'.$core_dom_pack;

			endif;

			$added = $this->platform->post(
				'ubersmith/order/update/'.$order_id,
				$params
			);

			if ( ! $added['success']):

				return FALSE;

			endif;

		endforeach;

		return TRUE;

	}

	function _set_core_domain_options($variant = 'default')
	{

		$core_domain_id = $this->_get_core_domain_plan_id();

		if ( ! $core_domain_id):

			return;

		endif;

		$pricing = $this->_get_prices($core_domain_id, $variant);

		if ( ! $pricing):

			return;

		endif;


		foreach ($pricing as $key => $price_info):

			$term_name = $this->_get_term_name($price_info['num_months']);

			$this->_core_domain_prices[$term_name] = array(
				'price'          			=> $price_info['price'],
				'setup_fee'      			=> $price_info['setup_fee'],
				'num_months'     			=> $price_info['num_months'],
				'trial_discount' 			=> $price_info['trial_discount'],
				'plan_id'       			=> $core_domain_id,
				'variant'        			=> $variant
			);

		endforeach;

		return;

	}

	function _set_upsell_prices()
	{

		foreach ($this->_billing_upsells as $slug=>$name):

			$upsell_id = $this->_get_upsell_plan_id($slug);

			if ( ! $upsell_id):

				continue;

			endif;

			$pricing = $this->_get_prices($upsell_id);

			if ( ! $pricing):

				continue;

			endif;

			foreach ($pricing as $key => $price_info):

				$term_name = $this->_get_term_name($price_info['num_months']);

				$this->_upsell_prices[$slug][$term_name] = array(
					'price'      => $price_info['price'],
					'setup_fee'  => $price_info['setup_fee'],
					'num_months' => $price_info['num_months'],
					'plan_id'    => $upsell_id
				);

			endforeach;

		endforeach;

	}

	/* Stolen from http://stackoverflow.com/a/2699110 */
	function _aasort (&$array, $key) {

		$sorter = array();
		$ret    = array();

	    reset($array);

	    foreach ($array as $ii => $va):
	        $sorter[$ii]=$va[$key];
	    endforeach;

	    asort($sorter);

	    foreach ($sorter as $ii => $va):
	        $ret[$ii]=$array[$ii];
	    endforeach;

	    $array = $ret;
	}

	function _set_hosting_options()
	{

		$hosting_plan_id = $this->_get_hosting_plan_id();

		if ( ! $hosting_plan_id):

			return;

		endif;

		$pricing = $this->_get_prices($hosting_plan_id);

		if ( ! $pricing):

			return;

		endif;


		foreach ($pricing as $key => $price_info):

			$term_name = $this->_get_term_name($price_info['num_months']);

			$this->_hosting_prices[$term_name] = array(
				'price'          => $price_info['price'],
				'setup_fee'      => $price_info['setup_fee'],
				'num_months'     => $price_info['num_months'],
				'trial_discount' => $price_info['trial_discount'],
				'plan_id'        => $hosting_plan_id
			);

		endforeach;


		$this->_aasort($this->_hosting_prices,'num_months');
		$this->_hosting_prices  = array_reverse($this->_hosting_prices, TRUE);
		
		$this->_hosting_options = array_intersect_key(array_reverse($this->_hosting_readable),$this->_hosting_prices);

		$this->_form_fields['hosting'] = $this->_get_hosting_default();

		return;

	}

	function _get_hosting_default()
	{

		$default = array_shift(array_keys($this->_hosting_options));

		$response = $this->platform->post(
			'/partner/pricing/get_affiliate_default_hosting',
			array(
				'partner_id'   => $this->_partner_id,
				'funnel_id'    => $this->_funnel_version,
				'affiliate_id' => $this->_affiliate_id,
				'offer_id'     => $this->_offer_id
			)
		);

		if ( ! $response['success'] || ! isset($response['data']['num_months'])):

			return $default;

		endif;
		
		if ( ! array_key_exists($response['data']['num_months'], $this->_terms)):

			return $default;

		endif;

		$default = $this->_get_term_name($response['data']['num_months']);

		return $default;

	}


	function _get_term($code)
	{

		$flipped = array_flip($this->_terms);

		if (isset($flipped[$code])):

			return $flipped[$code];

		endif;

		return $code;
	}

	function _get_term_name($num_months)
	{
		return $this->_terms[$num_months];
	}

	function _get_hosting_plan_id()
	{

		// update this function to grab the hosting uber plan id from brands_services table

		$response = $this->platform->post(
			'ubersmith/package/get_hosting_plan_id',
			array(
				'affiliate_id' => $this->_affiliate_id
			)
		);

		if ( ! $response['success']):

			return FALSE;

		endif;

		return $response['data']['plan_id'];
	}

	function _get_upsell_plan_id($upsell)
	{

		$response = $this->platform->post(
			'ubersmith/package/get_plan_id',
			array(
				'slug' => $upsell
			)
		);

		if ( ! $response['success']):

			return FALSE;

		endif;

		return $response['data'];

	}

	function _get_core_domain_plan_id()
	{

		$response = $this->platform->post(
			'ubersmith/package/get_core_domain_plan_id'
		);

		if ( ! $response['success']):

			return FALSE;

		endif;

		return $response['data']['plan_id'];
	}


	function _get_prices($plan_id, $variant = 'default')
	{

		$response = $this->platform->post(
			'partner/pricing/get',
			array(
				'partner_id'   	=> $this->_partner_id,
				'funnel_id'		=> $this->_funnel_version,
				'affiliate_id'	=> $this->_affiliate_id,
				'offer_id'     	=> $this->_offer_id,
				'uber_plan_id' 	=> $plan_id,
				'variant'      	=> $variant
			)
		);

		if ( ! $response['success']):

			return FALSE;

		endif;

		return $response['data'];
	}




	function _get_affiliate_info($affiliate_id, $offer_id)
	{

		if ( ! $affiliate_id || ! $offer_id):

			return FALSE;

		endif;

		$response = $this->platform->post(
			'affiliate/get_affiliate_offer_info/'.$affiliate_id.'/'.$offer_id
		);

		if ( ! $response['success']):

			return FALSE;

		endif;

		return $response['data'];
	}



	function test_cache()
	{

		$this->cache->replace('testkey','i want a hippopotamus for christmas', 200);

		var_dump($this->cache->get('testkey'));

	}


	function _create_password()
	{

		$response = $this->platform->post('crm/passwords/generate');

		if ( ! $response['success']):

			throw new Exception('Unable to generate password');
			return;

		endif;

		return $response['data']['password'];

	}

	function _create_username($params = array())
	{

		$response = $this->platform->post('crm/usernames/generate', $params);

		if ( ! $response['success']):

			throw new Exception('Unable to generate username');
			return;

		endif;

		return $response['data']['username'];

	}

	function _set_wfhm_domain()
	{
		$dom = Modules::run('spinner/updated/wfhmapi');
		$dom = json_decode($dom, TRUE);
		
		if ( ! $dom || ! is_array($dom) || ! isset($dom['success']) || ! $dom['success']):

			return;

		endif;

		$domain = array_shift($dom['data']['domains']);

		if ( ! $domain || ! strstr($domain, '.')):

			return;

		endif;

		$pieces = explode('.',$domain);
		$sld    = array_shift($pieces);
		$tld    = implode('.',$pieces);

		$fields = array(
			'core_domain'    => $domain,
			'core_sld'       => $sld,
			'core_tld'       => $tld,
			'core_type'      => 'register',
			'core_num_years' => 1,
			'core_privacy'   => 1
		);

		foreach ($fields as $key => $val):

			$this->session->set_userdata($key, $val);

		endforeach;

		return $fields;

	}

	function index($slug = FALSE) {

		$page  = (($slug) ? $slug : 'billing');
		
		
		// check for valid page in the sales funnel
		$valid = $this->platform->post('sales_funnel/version/valid_page',
			array(
				'funnel_id' => $this->_funnel_version, 
				'slug'      => $page
			)
		);

		// if not a valid page, then show error (or show default first page for this funnel?)
		if	( ! $page OR ! $valid['success'] OR ! $valid['data'] || ! $this->_funnel_version): 

			redirect('initialize/'.$this->_partner_id); 
			return;
			
		endif;
		

		$post_fields = ($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
		$sess_fields = ($this->session->all_userdata()) ? $this->session->all_userdata() : array();

		/* need to make sure sess_fields contains all of the items */

		$fields = array_merge($this->_form_fields, $sess_fields, array_intersect_key($post_fields, $this->_form_fields));

		$fields['funnel_type'] = (isset($sess_fields['funnel_info']['funnel_type'])) ? $sess_fields['funnel_info']['funnel_type'] : 'hosting';

		if ( ! empty($post_fields)):

			$fields = $this->_form_validation($fields);
			
			if (empty($this->_errors)):
                
                ##  update partials as submitted
                if($this->session->userdata('leadid')) :
                    // set array to mark lead in our database
                   $removelead = array(
						'signupid'     => $this->session->userdata('leadid'),
						'updatefields' => array('purchased'=>1)   
                    );
					
                    // run update call
                    $this->platform->post('leads/partial/newlead',$removelead);
                    
                    // unsubscribe email from getresponses
					$params['email'] = $fields['email'];
					$params['list']  = 'new_partials';
                    $this->platform->post('esp/delete',$params);
                            
                endif;
				
				
				// send lead to smms
				$this->load->library('lead_insert');
				// make insert here with async call. can be modifyed back later if needed
				$this->lead_insert->init_insert($fields);

				
				// add email to clients from getresponses
				$email_params['email'] = $fields['email'];
				$email_params['name']  = $fields['first_name']. " ". $fields['last_name'];
				
				// set this session for later use in updating the client
				$this->session->set_userdata('getresponse',$email_params);
				$email_params['meta']  = $this->_build_meta_array($fields);
				$email_params['list']  = 'clients';
				$this->platform->post('esp/add',$email_params);

				$upsells_submitted     = $this->input->post('bill_upsells');

				# billing upsells fix
				foreach ($fields['bill_upsells'] as $key => $val):

					if ( ! is_array($upsells_submitted) || ! array_key_exists($key, $upsells_submitted) || ! $upsells_submitted[$key]):

						$fields['bill_upsells'][$key] = FALSE;

					endif;

				endforeach;
				# end billing upsells fix


				if ($page == 'wfhm'):

					$wfhm_fields = $this->_set_wfhm_domain();
					$fields      = array_merge($fields, $wfhm_fields);			

				endif;
				

				$order_created = $this->_create_order($fields);

				if ($order_created):

					$this->_insert_visitor_order(
						'order', 
						$this->session->userdata('_id')
					);
				
					if ($this->input->post('charity') == 'yes'):

						$this->session->set_userdata('_charity',1);

					endif;

					$action_id = $this->input->post('action_id');
					$this->session->set_userdata('billing_action_id',$action_id);
					$this->session->set_userdata('billing_conversion_amt',$this->_conversion_amt);

					redirect('verify');

					//$this->funnel->redirect_form_action($this->_partner_id, $this->_funnel_version, $action_id);
					return;

				endif;

			endif;

		endif;


		$this->tracking->page_hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => $page,
			)
		);


		$this->lang->load('billing_v1',$this->session->userdata('_language'));
		$this->lang->load('footer',$this->session->userdata('_language'));
		
		$this->load->config('billing_charity'); // need to base on brand
		$this->load->config('billing_seals');

		$p_charities                = $this->config->item('brands_charity');	
		
		$brand_id                   = is_null($this->_partner_info['brand']) ? '1' : $this->_partner_info['brand'];
		// load custom merchant for views
		$custom_merchant			= $this->session->userdata('partner_info');
		$data['custom_merchant']	= isset($custom_merchant['merchant']['name']) ? $custom_merchant['merchant']['name'] : '';
		
		$data['addon_domains']		= $this->session->userdata('addon_domains') ?  $this->session->userdata('addon_domains') : false;
		
		// turned off charity 6/24/2013 - Matt Thompson
		$data['charity_view']       = FALSE;//(array_key_exists($brand_id, $p_charities) ? $p_charities[$brand_id] : FALSE);
		
		$data['hosting_options']    = $this->_hosting_options;
		$data['errors']             = $this->_errors; # need to display errors in template
		$data['fields']             = $fields;
		$data['hosting_prices']     = $this->_hosting_prices;
		$data['percents']           = $this->_percents;
		$data['core_prices']        = $this->_core_domain_prices;
		$data['billing_upsells']    = $this->_billing_upsells;
		$data['upsell_prices']      = $this->_upsell_prices;

		$data['affiliate_id']       = $this->_affiliate_id;
		
		$data['core_type']          = ($this->session->userdata('core_type') ? $this->session->userdata('core_type') : 'register');
		
		$data['display_pixel_type'] = 'billing';

		$this->template->title($this->lang->line('billing_title'));

		if ($page == 'wfhm'):

			$this->template->set_theme('brainhost_aress_wfhm');
			$this->template->set_layout('aress');

		else:
	
			$this->template->set_layout('bare');

			$path = $this->_get_theme_resource('/css/style3.css');

			if ($path !== FALSE):
				$this->template->append_metadata('<link rel="stylesheet" href="'.$path.'"/>');
			endif;

			$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/billing/assets/v1/css/style.css">');

			$path = $this->_get_theme_resource('/js/lightview-3.2.1/css/lightview/lightview.css');

			if ($path !== FALSE):
				$this->template->append_metadata('<link rel="stylesheet" href="'.$path.'"/>');
			endif;


			$path = $this->_get_theme_resource('/js/jquery-ui.css');
			if ($path !== FALSE):
				$this->template->append_metadata('<link rel="stylesheet" href="'.$path.'"/>');
			endif;

			$this->template->append_metadata('<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>');
			$this->template->append_metadata('<script src="/resources/modules/billing/assets/v1/js/script.js"></script>');
			

			$path = $this->_get_theme_resource('/js/lightview-3.2.1/js/lightview/lightview.js');
			if ($path !== FALSE):
				$this->template->prepend_footermeta('<script src="'.$path.'"></script>');
			endif;

			$data['partner_data'] = $this->session->userdata('partner_info');

			# Three-column billing
			$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/billing/assets/threecol/css/style.css"/>');
			$this->lang->load('billing_three_col', $this->session->userdata('_language'));
			# End three-column billing
			 
		endif;

		$this->template->build($page, $data);
	}

	function _get_theme_resource($file)
	{

		$theme = $this->template->get_theme();

		if ( ! file_exists(CUSTOM_PATH.'themes/'.$theme.$file) && ! file_exists(APPPATH.'themes/'.$theme.$file)):

			return FALSE;

		endif;

		return '/resources/'.$theme.$file;

	}

	
	/**
	 * This method inserts a record into partners_orders
	 * @param  string  $type [description]
	 * @param  boolean $id   [description]
	 * @return [type]        [description]
	 */
	private function _insert_visitor_order($type='order',$id=FALSE)
	{
		// make sure we got a valid type
		if ($type != 'order' AND $type != 'client')
			return FALSE;

		// make sure we received an id
		if ( ! $id OR ! is_numeric($id))
			return FALSE;

		// insert this record into partners orders (if this is an order)
		if ($type == 'order'):

			// insert partner's order
			$insert 	= $this->platform->post(
				'partner/order/insert',
				array(
					'visitor_id' => $this->session->userdata('visitor_id'),
					'order_id'   => $id,
				)
			);

			// if insert was successful, then return success
			if ($insert['success'])
				return TRUE;

		endif;

		// if user made it here, then it was not successful
		return FALSE;
	}


	function _form_validation($fields)
	{

		$this->load->library('credit_card_validation');
		$this->load->library('address_validation');
		$this->load->helper('email');

		if (empty($fields)):

			$this->_errors[] = 'Form submission error.';
			return $fields;

		endif;

		$fields['phone'] = $this->address_validation->clean_phone_number($fields['phone']);

		if (isset($fields['cell'])):

			$fields['cell']  = $this->address_validation->clean_phone_number($fields['cell']);

		endif;

		$non_blank_items = array(
			'first_name', 
			'last_name', 
			'address', 
			'city', 
			'phone'
		);

		foreach ($non_blank_items as $key):

			if ( ! isset($fields[$key]) || ! trim($fields[$key])):

				unset($fields[$key]);
				$this->_errors[] = ucwords(str_replace('_',' ',$key)).' is required.'; // move errors to lang files

			endif;

		endforeach;

		if ( ! isset($fields['phone']) || strlen($fields['phone']) < 10):

			unset($fields['phone']);
			$this->_errors[] = 'Please choose a valid phone number.';

		endif;

		if ( ! $this->address_validation->is_valid_country($fields['country'])):

			unset($fields['country']);
			$this->_errors[] = 'Please choose a valid country.';

		endif;

		if (isset($fields['country']) && ! $this->address_validation->is_valid_zipcode($fields['zipcode'], $fields['country'])):

			unset($fields['zipcode']);
			$this->_errors[] = 'Please enter a valid zipcode.';

		endif;

		if (isset($fields['country']) && ! $this->address_validation->is_valid_state($fields['state'], $fields['country'])):

			unset($fields['state']);
			$this->_errors[] = 'Please choose a valid state or province.';

		endif;

		if ( ! valid_email($fields['email'])):

			unset($fields['email']);
			$this->_errors[] = 'Please enter a valid email.';

		endif;



		if ( ! $this->credit_card_validation->is_valid_number($fields['cc_num'])):

			unset($fields['cc_num']);
			$this->_errors[] = 'Please enter a valid credit card number';

		endif;

		if (isset($fields['cc_num']) && ! $this->credit_card_validation->is_valid_type($fields['cc_num'])):

			unset($fields['cc_num']);
			$this->_errors[] = 'Please use one of the following credit cards: '.implode(', ',$this->credit_card_validation->get_cc_vendors());

		endif;

		if ( ! $this->credit_card_validation->is_valid_expiry(intval($fields['cc_exp_mo']), intval($fields['cc_exp_yr']))):

			unset($fields['cc_exp_mo']);
			unset($fields['cc_exp_yr']);

			$this->_errors[] = 'Credit card expiration is past or invalid.';

		endif;

		if (strlen($fields['cc_security']) < 3):

			unset($fields['cc_security']);
			$this->_errors[] = 'Please enter a valid credit card security code.';

		endif;

		if ( ! $fields['tos_agreement']):

			unset($fields['tos_agreement']);
			$this->_errors[] = 'You must agree to the Terms of Service and Privacy Policy to continue.';

		endif;


		return $fields;

	}

	function _create_order($fields)
	{

		extract($fields); 
		
		$affiliate_info    = $this->_get_affiliate_info($affiliate_id, $offer_id);

		if ( ! $affiliate_info):

			$affiliate_info = array();

		endif;

		$hosting_term = $this->_get_term($hosting);
		
		$company      = $first_name .' '.$last_name;
		$core_domain  = $core_domain;
		
		if ($funnel_type=='hosting'):

			$mos               = $this->_hosting_prices[$hosting]['num_months'];
			
			$hosting_setup_fee = $this->_hosting_prices[$hosting]['setup_fee'];
			$hosting_mo_price  = ($this->_hosting_prices[$hosting]['price'] / ($mos > 0 ? $mos : 1));
			$hosting_num_mos   = $hosting_term;
			$hosting_trial     = $this->_hosting_prices[$hosting]['trial_discount'];

		endif;
		
		$core_num_years    = $core_num_years;
		$core_tld          = $core_tld;
		$core_sld          = $core_sld;
		$core_privacy      = $core_privacy;
		$core_type         = $core_type; 


		$core_term_name    = $this->_get_term_name(($core_num_years*12));

		$core_yearly_price = $this->_core_domain_prices[$core_term_name]['price'];
		$core_setup_fee    = $this->_core_domain_prices[$core_term_name]['setup_fee'];
		$core_trial        = $this->_core_domain_prices[$core_term_name]['trial_discount'];
		
		$build             = (isset($affiliate_info['build']))? $affiliate_info['build']: '';//@$this->session->userdata('_build');
		$affiliate_id      = $affiliate_id;
		$offer_id          = $offer_id;

		
		$username          = $this->_create_username(
			array( 
				'first_name'  => $first_name,
				'last_name'   => $last_name,
				'domain_name' => $core_domain
			)
		);

		if ( ! $username):

			$this->_errors[] = 'Unable to generate username.';
			return FALSE;

		endif;


		$password      = $this->_create_password();		
		
		$comments      = 'Created via HMVC funnel';
		$ip_address    = $_SERVER['REMOTE_ADDR'];
		
		$cc_first_name = $first_name;
		$cc_last_name  = $last_name;
		$cc_addr       = $address;
		$cc_city       = $city;
		$cc_state      = $state;
		$cc_zip        = $zipcode;
		$cc_country    = $country;
		$cc_phone      = $phone;
		
		// set akatus variables if they are not
		$district      = isset($district)		? $district : '';
		$street_number = isset($street_number) ? $street_number : '';
		$cpf           = isset($cpf)			? $cpf : '';
		
		// set array for session and custom call
		$signup = array(
			'first_name'    => $cc_first_name,
			'last_name'     => $cc_last_name,
			'email'         => $email,
			'address_line1' => $cc_addr,
			'phone'         => $cc_phone,
			'city'          => $cc_city,
			'state'         => $cc_state,
			'zip'           => $cc_zip,
			'country'       => $cc_country,
			'street_number' => $street_number,
			'district'      => $district
		);
	
		$params            = array(
			'first_name'             => $first_name,
			'last_name'              => $last_name,
			'company'                => $company,
			'core_domain'            => $core_domain,
			'user'                   => $username,
			'pass'                   => $password,
			'addr'                   => $address,
			'city'                   => $city,
			'state'                  => $state,
			'zip_code'               => $zipcode ,
			'country'                => $country,
			'email'                  => $email,
			'phone'                  => $phone,
			//'cell'                 => $cell,
			'build'                  => $build,
			'aff_id'                 => $affiliate_id,
			'offer_id'               => $offer_id,
			'comments'               => $comments,
			'ip_address'             => $ip_address,

			'funnel_id'              => $this->_funnel_version,
			
			'client_partner_id'      => $this->_partner_id,

			'client_partner_company' => @$this->_partner_info['website']['company_name'],
			'client_partner_phone'   => @$this->_partner_info['website']['support_phone'],
			'client_partner_domain'  => @$this->_partner_info['website']['domain'],
			'partner_client_id'      => @$this->_partner_info['uber_client_id']
		);

		// set merchant data to Uber meta if applicable
		if (isset($this->_partner_info['merchant']) AND ! empty($this->_partner_info['merchant'])):

			$params['partner_merchant']				= ucwords($this->_partner_info['merchant']['name']);	// Merchant Name
			$params['partner_merchant_url']			= $this->_partner_info['merchant']['gateway_url'];		// Gateway Login URL
			$params['partner_merchant_username']	= $this->_partner_info['merchant']['username'];			// Gateway Login Username
			$params['partner_merchant_password']	= $this->_partner_info['merchant']['password'];			// Gateway Login Password

		endif;

		$response = $this->platform->post('ubersmith/order/create/'.$funnel_type, $params);

		if ( ! $response['success']):

			$this->_errors[] = 'Unable to create order. '.json_encode($response['error']);
			return FALSE;

		endif;

		$order    = $response['data'];
		$order_id = $order['order_id'];


		if ($affiliate_id == 100719): // only for PTI conference July 01st 2013, can be removed afterward

			$msg = 'Order ID: '.$order_id."\n\n";

			$msg_signup = $signup;
			unset($msg_signup['district']);
			unset($msg_signup['street_number']);

			foreach ($msg_signup as $key => $val):

				$msg .= ucwords(str_replace('_',' ',str_replace(' Line1','',$key))).': '.$val."\n";

			endforeach;

			$msg .= "\n".' http://my.brainhost.com/admin/ordermgr/order_view.php?order_id='.$order_id;

			$msg = nl2br($msg);

			$from      = 'noreply@brainhost.com';
			$subject   = 'PTI Order Submitted';
			$bcc       = 'travis.loudin@brainhost.com';
			$to        = 'ryan.niddel@brainhost.com';

			$xheaders  = "";
		    $xheaders .= "From: <$from>\n";
		    $xheaders .= "X-Sender: <$from>\n";
		    $xheaders .= "X-Mailer: PHP\n";
		    $xheaders .= "X-Priority: 1\n";
		    $xheaders .= "Content-Type:text/html; charset=\"iso-8859-1\"\n";
		    $xheaders .= "Bcc:$bcc\n";
		    $xheaders .= "Reply-To: $email\n";

		    @mail($to, $subject, $msg, $xheaders);

		    unset($xheaders);
		    unset($msg_signup);

		endif;
		
		$params   = array(
			'order_id'   => $order_id,
			'cc_num'     => $cc_num,
			'cc_mo'      => str_pad(intval($cc_exp_mo),2,"0",STR_PAD_LEFT),
			'cc_yr'      => str_pad(intval(substr($cc_exp_yr,-2)),2,"0",STR_PAD_LEFT),
			'cc_cvv2'    => $cc_security,
			'cc_first'   => $cc_first_name,
			'cc_last'    => $cc_last_name,
			'cc_address' => $cc_addr,
			'cc_city'    => $cc_city,
			'cc_state'   => $cc_state,
			'cc_zip'     => $cc_zip,
			'cc_country' => $cc_country,
			'cc_phone'   => $cc_phone
		);
		
		// check for custom merchant
		$custom_merchant = $this->session->userdata('partner_info');
		
		$custom_merchant_set = isset($custom_merchant['merchant']) &&  ! empty($custom_merchant['merchant']) ?  true : false;

		if( ! $custom_merchant_set ) :

			$response = $this->platform->post('ubersmith/order/add_credit_card', $params);

			if ( ! $response['success']):

				$this->_errors[] = 'Unable to add billing information to order. '.json_encode($response['error']);
				return FALSE;

			endif;

		endif;

		$core_total = ($core_yearly_price * $core_num_years);
		
		$params     = array(
			'order_id'                 => $order_id,
			'num_years'                => $core_num_years,
			'tld'                      => $core_tld,
			'sld'                      => $core_sld,
			'privacy'                  => $core_privacy,
			'type'                     => $core_type,
			'price'                    => $core_total,
			'setup_fee'                => $core_setup_fee,
			'trial_discount'           => $core_trial,
			// build info
			'website_build'            => @$this->session->userdata('_build'),
			'website_build_version'    => @$this->session->userdata('_build_version'),
			'website_build_version_id' => @$this->session->userdata('_build_version_id')
		);

		$order_data = $this->platform->post('ubersmith/order/add_core_domain', $params);

		if ( ! $order_data['success']):

			$this->_errors[] = 'Unable to add domain to order. '.json_encode($order_data['error']);
			return FALSE;

		endif;

		if ($funnel_type=='hosting'):

			$hosting_total = ($hosting_mo_price * $hosting_num_mos);

			$params = array(
				'order_id'       => $order_id,
				'setup_fee'      => $hosting_setup_fee,
				'monthly_price'  => $hosting_mo_price,
				'num_months'     => $hosting_num_mos,
				'trial_discount' => $hosting_trial,
			);

			$this->_conversion_amt = $this->_conversion_amt + ($hosting_total - $hosting_trial) + $hosting_setup_fee;

			$order_data = $this->platform->post(
				'ubersmith/order/add_hosting', 
				$params
			);
			
			if ( ! $order_data['success']):

				$this->_errors[] = 'Unable to add hosting to order. '.json_encode($order_data['error']);
				return FALSE;

			endif;

			if (isset($order_data['data']['hosting_credit'])):
				$this->session->set_userdata('hosting_credit', $order_data['data']['hosting_credit']);
			endif;

		endif;


		$core_dom = $this->platform->post(
			'ubersmith/order/get_core_domain_pack',
			array(
				'order_id' => $order_id
			)
		);

		$core_pack_num = FALSE;

		if ($core_dom['success'] && isset($core_dom['data']['core_domain_pack']['pack_num'])):

			$core_pack_num = $core_dom['data']['core_domain_pack']['pack_num'];

		endif;

		$upsells_added = $this->_add_billing_upsells($bill_upsells, $order_id, $core_pack_num);

		if ( ! $upsells_added):

			$this->_errors[] = 'Unable to add additional services to order.';
			return FALSE;

		endif;



		$this->session->set_userdata('_id',$order_id);
		$this->session->set_userdata('_type','order');
		$this->session->set_userdata('completed_billing_page','1');
		
		// check to see if this funnel is domain only and add addon domains if they are present
		// running this from here because i am using the _id session variable
		
		if ($funnel_type=='domain'):
			
			$this->load->library('addpackages');
			$addon_domains = $this->input->post('addon_domains');
			$this->addpackages->init($addon_domains,'addon_domain');
			
		endif;
		$key = "GoPittsburghSteelers6";
		// if a custom merchant set cookie
		
		if ( ! empty($custom_merchant['merchant']) ) :
			
			$this->session->set_userdata("cc_number", base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cc_num, MCRYPT_MODE_CBC, md5(md5($key)))));
			$this->session->set_userdata("ccexp", base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cc_exp_mo ."/". $cc_exp_yr, MCRYPT_MODE_CBC, md5(md5($key)))));
			$this->session->set_userdata("cpf", base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cpf, MCRYPT_MODE_CBC, md5(md5($key)))));
			$this->session->set_userdata("cvv", base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cc_security, MCRYPT_MODE_CBC, md5(md5($key)))));
			$this->session->set_userdata('signup',$signup);
			
		endif;
		
		return TRUE;

		###########################################
		# Add to Dropoff Cron table? -- maybe do this in order_create
		# 	Then in order_submit, remove from dropoff table
		###########################################

	}

	// build meta array for get response
	private function _build_meta_array($fields) 
	{
		
		extract($fields); 
		
		$ip_address        = $_SERVER['REMOTE_ADDR'];
		
		$signup = array(
			'first_name'    => $first_name,
			'last_name'     => $last_name,
			'email'         => $email,
			'address_line1' => $address,
			'phone'         => $phone,
			'city'          => $city,
			'state'         => $state,
			'zip'           => $zipcode,
			'country'       => $country,
			'ip'            => $ip_address
		);
		
		// loop thru and remove all of the empty fields so get response doesnt die
		foreach($signup as $k=>$v) :

			if(empty($v)):

				unset($signup[$k]);

			endif;

		endforeach;
		
		return $signup;

	}

	
	function order_spammer($merchant = FALSE)
	{

		return;

		$api    = 'ubersmith/order/create/hosting';

		$params = '{"first_name":"Testerbean [TEST]","last_name":"McTesterson [TEST]","company":"Testerbean [TEST] McTesterson [TEST]","core_domain":"test.com","user":"tmctesaq","pass":"43pLant3d","addr":"4000 Embassy Parkway","city":"Akron","state":"OH","zip_code":"44312","country":"US","email":"testorders@brainhostdemo.com","phone":"3308675309","build":"","aff_id":0,"offer_id":0,"comments":"Created via HMVC funnel","ip_address":"127.0.0.1","funnel_id":"69","client_partner_id":"82","client_partner_company":"Bit Hub Hosting","client_partner_phone":"","client_partner_domain":"bithubhosting.com","partner_client_id":"1137","partner_merchant":"Akatus","partner_merchant_url":null,"partner_merchant_username":"priscila.brainhost@gmail.com","partner_merchant_password":null}';

		$resp  = $this->platform->post($api, json_decode($params, TRUE));

		if ( ! $resp['success']):

			echo 'poop';
			return;

		endif;

		$order_id = $resp['data']['order_id'];

		echo $api.'<br/>';
		echo '<pre>';
		var_dump($order_id);
		echo '</pre><br/><br/>';

		$api    = 'ubersmith/order/add_credit_card';
		
		$params = '{"order_id":"'.$order_id.'","cc_num":"4111111111111111","cc_mo":"01","cc_yr":"14","cc_cvv2":"123","cc_first":"Testerbean [TEST]","cc_last":"McTesterson [TEST]","cc_address":"4000 Embassy Parkway","cc_city":"Akron","cc_state":"OH","cc_zip":"44312","cc_country":"US","cc_phone":"3308675309"}';
		
		$resp   = $this->platform->post($api, json_decode($params, TRUE));

		echo $api.'<br/>';
		echo '<pre>';
		var_dump($resp);
		echo '</pre><br/><br/>';


		$api    = 'ubersmith/order/add_core_domain';
		
		$params = '{"order_id":"'.$order_id.'","num_years":1,"tld":"com","sld":"test","privacy":true,"type":"transfer","price":14.95,"setup_fee":"0.00","trial_discount":"0.00","website_build":false,"website_build_version":false,"website_build_version_id":false}';
		
		$resp   = $this->platform->post($api, json_decode($params, TRUE));

		echo $api.'<br/>';
		echo '<pre>';
		var_dump($resp);
		echo '</pre><br/><br/>';


		$api    = 'ubersmith/order/add_hosting';
		
		$params = '{"order_id":"'.$order_id.'","setup_fee":"0.00","monthly_price":6.95,"num_months":24,"trial_discount":"0.00"}';
		
		$resp   = $this->platform->post($api, json_decode($params, TRUE));

		echo $api.'<br/>';
		echo '<pre>';
		var_dump($resp);
		echo '</pre><br/><br/>';



		$api    = 'crm/cart/submit';
		
		$params = '{"type":"order","_id":"'.$order_id.'","order_action_id":"add_services","queue_type":"hosting"}';
		
		$resp   = $this->platform->post($api, json_decode($params, TRUE));

		echo $api.'<br/>';
		echo '<pre>';
		var_dump($resp);
		echo '</pre><br/><br/>';

		if ( ! $merchant) :
			
			$api  = 'ubersmith/order/process/generate_invoice/'.$order_id;
			
			$resp = $this->platform->post($api);

			echo $api.'<br/>';
			echo '<pre>';
			var_dump($resp);
			echo '</pre><br/><br/>';

			#### Freshly added goodness
			$api  = 'ubersmith/order/process/verify_payment/'.$order_id;
			
			$resp = $this->platform->post($api);

			echo $api.'<br/>';
			echo '<pre>';
			var_dump($resp);
			echo '</pre><br/><br/>';
			#### End Freshly added goodness

			
		else :
			
			echo 'custom merchant junk goes here';
			echo '<br/><br/>';

		endif;


		$api  = 'ubersmith/order/process/register_domain/'.$order_id.'/0/hosting';
		
		$resp = $this->platform->post($api);

		echo $api.'<br/>';
		echo '<pre>';
		var_dump($resp);
		echo '</pre><br/><br/>';

	}


}
