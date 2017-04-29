<?php

class Signup extends MX_Controller {


	public 
		$errors           = array(),
		$domain           = '',
		$type             = 'register',
		$params           = array(),
		$suggestions      = array(),
		$show_suggestions = FALSE;

	protected 
		$_valid_types  = array(
			'register', 
			'transfer', 
			'dns'
		),
		$_funnel_version,
		$_partner_id,
		$_session_key,
		$_affiliate_id,
		$_offer_id,
		$_form_fields = array(
			# 'full_name'      => '',
			# 'first_name'     => '',
			# 'last_name'      => '',
			# 'email'          => '',
			'core_domain'    => '',
			'core_sld'       => '',
			'core_tld'       => '',
			'core_type'      => 'register',
			'core_num_years' => 1,
			'core_privacy'   => 1
		);

	function __construct()
	{

		// set funnel version
		$this->_funnel_version = $this->session->userdata('funnel_id');
		$this->_partner_id     = $this->session->userdata('partner_id');		
		$this->_session_key    = $this->session->userdata('session_id');
		$this->_affiliate_id   = $this->session->userdata('affiliate_id');
		$this->_offer_id       = $this->session->userdata('offer_id');

		// load language
		$this->lang->load('signup',$this->session->userdata('_language'));
		$this->lang->load('footer',$this->session->userdata('_language'));
	}

	function _set_domain_fields($fields)
	{

		if ('.com' == substr($fields['core_sld'],-4)):

			$fields['core_sld'] = substr($fields['core_sld'],0,-4);

		endif;

		if ($fields['core_tld'] && $fields['core_sld'] && ! $fields['core_domain']):

			$fields['core_domain'] = $fields['core_sld'] . '.' . $fields['core_tld'];

			if ($fields['core_domain']=='.'):

				$fields['core_domain'] = '';

			endif;

		endif;


		# Making the form idiot-proof against double tlds
		$popular_tlds = array(
			'.com',
			'.net',
			'.org',
			'.info',
			'.biz'
		);

		foreach ($popular_tlds as $tld):

			$fsked_tld = $tld.'.com';
			$tld_len   = strlen($tld);

			if ($fsked_tld == substr($fields['core_domain'],(0 - strlen($fsked_tld)))):

				$fields['core_domain'] = substr($fields['core_domain'],0,-4);
				$fields['core_tld']    = substr($fields['core_domain'],(0 - ($tld_len - 1)));
				$fields['core_sld']    = substr($fields['core_domain'], 0, (0 - $tld_len));

			endif;

		endforeach;
		# End double-tld idiot-proofing


		$fields['core_domain'] = $this->domain_validation->clean_domain_name($fields['core_domain']);
		
		if ($fields['core_domain'] && ! $fields['core_sld'] && ! $fields['core_tld']):

			$core = explode('.',$fields['core_domain']);

			$fields['core_sld'] = array_shift($core);
			$fields['core_tld'] = implode('.',$core);

		endif;

		if (substr($fields['core_tld'],0,1)=='.'):

			$fields['core_tld'] = substr($fields['core_tld'], 1);

		endif;

		return $fields;
	}

	function _clear_domain_fields($fields)
	{

		$fields['core_domain'] = '';
		$fields['core_sld']    = '';
		$fields['core_tld']    = '';

		return $fields;

	}


	private function _default_page()
	{	

		return 'domain_lander';

		/*
		// grab default page for this funnel id
		$default 	= $this->platform->post('sales_funnel/page/default_signup',array('funnel_id' => $this->_funnel_version));

		// if no default page for this funnel found, then reset funnel id to 1 (Brain Host) & try again
		if (( ! $default['success'] OR ! $default['data']) && ! $this->_funnel_version == 1):

			// reset funnel to 1 (Brain Host)
			$this->_funnel_version	= 1;

			// run method again
			return $this->_default_page();

		endif;

		return $default['data'];
		*/
	}

	function _form_validation($fields)
	{

		$this->load->library('domain_validation');
		$this->load->helper('email');

		if (empty($fields)):

			$this->errors[] = $this->lang->line('form_submission');
			return $fields;

		endif;

		if (empty($fields['core_domain']) && empty($fields['core_sld'])):

			$this->errors[] = $this->lang->line('choose_domain');
			return $fields;

		endif;

		$fields = $this->_set_domain_fields($fields);

		if ($fields['core_type'] != 'register' && ! $this->domain_validation->is_valid_transfer_type($fields['core_type'])):

			unset($fields['core_type']);
			$this->errors[] = $this->lang->line('invalid_domain');

		endif;

		/*
		$assembled = $fields['core_sld'].'.'.$fields['core_tld'];

		if (isset($fields['core_domain']) && $assembled != '.' && $assembled != $fields['core_domain']):

			$fields['core_domain'] = $assembled;

		endif;
		*/
	
		// if posted suggestion doesn't match what's in the session, we need to overwrite it
		if ($fields['core_domain'] != $fields['core_sld'].'.'.$fields['core_tld'] AND $fields['core_domain'] != ''):

			// grab position of 1st .
			$symbol 	= strrpos($fields['core_domain'], '.');

			// split sld and tld
			$fields['core_tld'] 	= substr($fields['core_domain'],($symbol+1));
			$fields['core_sld']		= substr($fields['core_domain'],0,$symbol);

		endif;


		if (isset($fields['core_domain']) && $this->domain_validation->is_domain_forbidden($fields['core_domain'])):

			$fields          = $this->_clear_domain_fields($fields);
			$this->errors[]  = $this->lang->line('domain_unavailable');

		endif;

		if (isset($fields['core_tld']) && ! $this->domain_validation->is_valid_domain_tld($fields['core_tld'])):

			$fields          = $this->_clear_domain_fields($fields);
			$this->errors[]  = $this->lang->line('invalid_extension');

		endif;


		if (isset($fields['core_sld']) && ! $this->domain_validation->is_valid_domain_sld($fields['core_sld'])):
			
			
			$fields          = $this->_clear_domain_fields($fields);
			$this->errors[]  = $this->lang->line('valid_domain');

		endif;


		if (isset($fields['core_domain'])):

			$dom_function = (isset($fields['core_type']) && $fields['core_type']=='register') ? 'is_valid_register_domain' : 'is_valid_transfer_domain';

			if ( ! $this->domain_validation->$dom_function($fields['core_sld'], $fields['core_tld'])):

				$fields       = $this->_clear_domain_fields($fields);
				$this->errors = array_merge($this->domain_validation->errors,$this->errors);

				if (empty($this->errors)):

					$this->errors[] = $this->lang->line('error_checking');

				endif;

			endif;

		endif;


		if ($fields['core_sld'] && $fields['core_tld'] && $fields['core_type']):

			$availability = $this->platform->post('registrars/domain/is_available/'.$fields['core_sld'].'/'.$fields['core_tld']);

			if ( ! $availability['success']):

				$this->errors[] = $this->lang->line('unable_check').implode(' :: ',$availability['error']);

			else:

				$is_available = $availability['data']['availability'];

				if ($is_available && $fields['core_type'] != 'register'):

					$this->errors[] = $this->lang->line('not_registered');

				endif;

				if ( ! $is_available && $fields['core_type'] == 'register'):

					$tld_response = $this->platform->post(
						'registrars/domain/get_all_tlds/'.trim($fields['core_sld'])
					);

					$suggs = array();

					if ($tld_response['success']):

						foreach ($tld_response['data']['domains'] as $tld=>$avail):

							if ($avail):

								$suggs[] = $tld;

							endif;

						endforeach;

					endif;

					$get_num  = 12 - count($suggs);
					
					$response = $this->platform->post(
						'registrars/domain/get_suggestions/'.$fields['core_sld'].'/'.$fields['core_tld'].'/'.$get_num
					);

					if ($response['success'] && count($response['data']['suggestions'])):

						$suggs = array_merge($suggs, $response['data']['suggestions']);

					endif; 

					$fields['core_sld']     = '';

					if (empty($suggs)):

						$this->errors[] = $this->lang->line('domain_unavailable');
						return FALSE;

					endif;					
					
					$this->suggestions      = $suggs;					
					$this->show_suggestions = TRUE;

				endif;

			endif;

		endif;



		if ( ! is_numeric($fields['core_num_years']) || $fields['core_num_years'] < 1):

			unset($fields['core_num_years']);
			$this->errors[] = $this->lang->line('registration_length'); 

		endif;

		if (isset($fields['core_num_years'])):

			$fields['core_num_years'] = intval($fields['core_num_years']);

		endif;


		$fields['core_privacy'] = (bool) $fields['core_privacy'];

		return $fields;

	}

	function _get_core_price($core_type)
	{

		$variant = ($core_type == 'dns' ? $core_type : 'default');

		$response = $this->platform->post(
			'ubersmith/package/get_core_domain_plan_id'
		);

		if ( ! $response['success']):

			return FALSE;

		endif;

		$plan_id = $response['data']['plan_id'];

		$response = $this->platform->post(
			'partner/pricing/get',
			array(
				'partner_id'   => $this->_partner_id,
				'affiliate_id' => $this->_affiliate_id,
				'offer_id'     => $this->_offer_id,
				'uber_plan_id' => $plan_id,
				'variant'      => $variant,
				'funnel_id'    => $this->_funnel_version,
			)
		);

		if ( ! $response['success']):

			return FALSE;

		endif;

		$price_data = $response['data'];
		$pricing    = FALSE;

		foreach ($price_data as $prices):

			if ($prices['num_months']==12):

				$pricing = $prices;
				break;

			endif;

		endforeach;

		if ( ! $pricing || ! isset($pricing['price'])):

			return FALSE;

		endif;


		$price = ($pricing['price'] - $pricing['trial_discount']);

		if ($price < 0):

			$price = 0;

		endif;

		$price = $price + $pricing['setup_fee'];

		return $price;


	}

	/**
	 * Modified on 5/8/13   added the domain_only variable
	 * @param type $fields
	 * @param type $domain_only
	 * @return type
	 */
	
	function _process_form($fields,$domain_only=false)
	{

		if(!$domain_only) :
			
			$conversion_price = floatval($this->_get_core_price($fields['core_type']));
		
		else:
			// this is added in function at bottom of the page build_something_or_other dont feel like scrolling down to the bottom
			$conversion_price = $fields['conversion'];
		
		endif;
		
		$action_id        = $fields['action_id'];
		
		$this->tracking->page_action(
			array(
				'visitor_id'        => $this->session->userdata('visitor_id'),
				'action_id'         => $action_id,
				'conversion'        => TRUE,
				'conversion_amount' => $conversion_price		
			)
		);		
	
		foreach ($fields as $key => $val):

			$this->session->set_userdata($key, $val);		

		endforeach;

		
		return $this->funnel->redirect_form_action($this->_partner_id, $this->_funnel_version, $action_id);
		
	}

	function index($page = '') 
	{

		// set default layout for page
		$layout = 'bare';
		if( ! empty($page)) :
			$pagedet = $this->platform->post('sales_funnel/page/get',array('slug'=>$page));
			
			if($pagedet['success']) :
				$page_details = $pagedet['data'];
				$layout = $page_details['layout'];
			endif;
		endif;
		
		if ($this->input->cookie('ordersubmitted')):

			$val = json_decode($this->input->cookie('ordersubmitted'), TRUE);

			if (is_array($val)):

				if (isset($val['success']) && $val['success'] && isset($val['id'])):

					redirect('completed/sale/order/'.$val['id']);
					return;

				endif;

				redirect('billing/declined');
				return;

			endif;

		endif;


		// if no page was passed, set it to the default page
		if ( ! $page) $page = $this->_default_page();

		// verify this is a valid page for this funnel
		$valid	= $this->platform->post(
			'sales_funnel/version/valid_page',
			array(
				'funnel_id' => $this->_funnel_version, 
				'slug'      => $page
			)
		);
		
		
		// if not a valid page, then show error (or show default first page for this funnel?)
		if	( ! $page OR ! $valid['success'] OR ! $valid['data']): 
			redirect('initialize/'.$this->_partner_id); 
			return;
		endif;

		// verify there's a valid _funnel_version
		if ( ! $this->_funnel_version):
			redirect('initialize/'.$this->_partner_id); 
			return;
		endif;
		
		#todo fix later
		$form_action_id = 49;
		
		$posted         = ($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();

		$post_fields    = array_merge(
			$this->session->all_userdata(), 
			$posted
		); # combine session with post
		
		$fields         = array_merge(
			$this->_form_fields, 
			array_intersect_key(
				$post_fields, 
				$this->_form_fields
			)
		);
		
		// process domain only
		if($this->input->post('domains_only')) :
			
			$form_action_id = 7;
			$domains_only = $this->input->post('domains_only');
		
			$fields['action_id'] = $form_action_id;
			
			$fields = $this->_build_domains_only($domains_only,$fields);
			
			/*$cleaned_fields = $this->_form_validation($fields);

			if ( ! $cleaned_fields):

				$cleaned_fields = array();

			endif;

			$fields = array_merge($fields, $cleaned_fields);
			
			if (empty($this->errors) && ! $this->show_suggestions):
			*/	
				$this->_process_form($fields,true);
			
			//endif;
			
		else:
			if ( ! empty($posted) || ($this->session->userdata('external_post') && ($this->session->userdata('core_domain') || $this->session->userdata('core_sld')))):

				#todo fix later
				$fields['action_id'] = $form_action_id;

				$cleaned_fields = $this->_form_validation($fields);

				if ( ! $cleaned_fields):

					$cleaned_fields = array();

				endif;

				$fields = array_merge($fields, $cleaned_fields);

				## LOCAL HACK
				if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1'):

					$this->errors 				= array();
					$this->show_suggestions 	= FALSE;

				endif;

				if (empty($this->errors) && ! $this->show_suggestions):

					return $this->_process_form($fields);

				endif;

			endif;
		endif;

		$this->tracking->page_hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => $page
			)
		);
		
		$method = "_init_$page";
		
		if(method_exists($this,$method)) :
			$data = $this->$method();
		endif;
		
		$data['errors']             = $this->errors; # need to display errors in template
		$data['fields']             = $fields;
		$data['suggestions']        = ($this->suggestions) ? $this->suggestions : NULL;
		
		$data['form_action_id']     = $form_action_id;
		
		$data['display_pixel_type'] = 'lander';

		// domain only variables
		
		$data['searched_domain']['full'] = 'exampledomain.com';
		$data['searched_domain']['sld'] = 'exampledomain';
		$data['searched_domain']['tld'] = 'com';
		

		$this->template->set_layout($layout);

		$this->template->title($this->lang->line('signup_title'));
		


		$path = $this->_get_theme_resource('/css/style3.css');
		if ($path !== FALSE):
			$this->template->append_metadata('<link rel="stylesheet" href="'.$path.'"/>');
		endif;

		$path = $this->_get_theme_resource('/js/lightview-3.2.1/css/lightview/lightview.css');
		if ($path !== FALSE):
			$this->template->append_metadata('<link rel="stylesheet" href="'.$path.'"/>');
		endif;

		$path = $this->_get_theme_resource('/js/lightview-3.2.1/js/lightview/lightview.js');
		if ($path !== FALSE):
			$this->template->prepend_footermeta('<script src="'.$path.'"></script>');	
		endif;

		$this->template->build($page, $data);
	}


	function _get_theme_resource($file)
	{

		$theme = $this->template->get_theme();

		if ( ! file_exists(CUSTOM_PATH.'themes/'.$theme.$file)):

			return FALSE;

		endif;

		return '/resources/'.$theme.$file;

	}
	
	/**
	 * Function to initialize the domain only funnel styles
	 * Added 5/7/13
	 * Jamie Rohr
	 * @return type
	 */
	function _init_search(){
		
		$price = $this->_get_core_price('default');
		$this->lang->load('domain_form');
		$prices = "var prices = {
					'com' 	: ['$price','$price'],
					'net' 	: ['$price','$price'],
					'org' 	: ['$price','$price'],
					'info' 	: ['$price','$price'],
					'biz' 	: ['$price','$price']
					}";
		$this->template->prepend_footermeta('<script type="text/javascript">'.$prices.'</script>');
		$this->template->prepend_footermeta('<script src="/resources/brainhost/js/search3.js"></script>');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/allphase_funnel/css/domains.css"/>');
		return array();
	}
	
	/**
	 * Function to build an addon domain array for the billing page
	 * Added 5/7/13
	 * Jamie Rohr
	 * @return type
	 */
	private function _build_domains_only($domains,$fields) {
		
		$return = array();
		// process core domain 
		$conversion_price = floatval($this->_get_core_price($fields['core_type']));
		// loop thru post
		$total = 0 ;
		foreach($domains as $dom => $price) :
			
			// durp it up into a durpable exploded array to get sld and tld
			
			$durp = explode("_", $dom);
			$domain = $durp[1].".".$durp[0];
			
			$price = $price == $conversion_price ? $price : $conversion_price;
			
			$total += $price;
			// set core domain if it is not set
			if( empty($fields['core_domain'])) :
				
				$fields['core_domain'] = $domain;
				$fields['core_sld'] = $durp[1];
				$fields['core_tld'] = $durp[0];
			
			//else create add on domains
			else: 
				$d = array();
				$d['sld'] = $durp[1];
				$d['tld'] = $durp[0];
				$d['price'] = $price;
				$d['domain'] = $domain;
				$return[$domain] = $d;
			endif;
			
		endforeach;
		
		$fields['conversion'] = $total;
		// set addon array 
		$fields['addon_domains'] = $return;
		return $fields;
	}

}