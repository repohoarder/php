<?php

## TODO: Create system more modular (similiar to bonus module) so that we can have pre and post page loading methods
## TODO: Add ability to have custom layouts per _partner_id
#
class Domain extends MX_Controller {

	/**
	 * Funnel Version
	 * 
	 * @var  _funnel_version The version of the funnel the suer is currently in
	 */
	var $_funnel_version;

	/**
	 * Partner ID
	 * 
	 * @var  _partner_id
	 */
	var $_partner_id;


	protected $_session_key;

	protected $_errors = array();

	protected $_form_fields = array(
		'full_name'      => '',
		'first_name'     => '',
		'last_name'      => '',
		'email'          => '',
		'core_domain'    => '',
		'core_sld'       => '',
		'core_tld'       => '',
		'core_type'      => 'register',
		'core_num_years' => 1,
		'core_privacy'   => 1
	);

	var $_affiliate_id;
	var $_offer_id;

	public function __construct()
	{
		parent::__construct();

		// set funnel version
		$this->_funnel_version = $this->session->userdata('funnel_id');
		
		// set partner_id variable
		$this->_partner_id     = $this->session->userdata('partner_id');
		
		$this->_session_key    = $this->session->userdata('session_id'); # change this to NOT be CI sess key

		$this->_affiliate_id   = $this->session->userdata('affiliate_id');

		$this->_offer_id       = $this->session->userdata('offer_id');

	}


	/**
	 * Index
	 * 
	 * This is the default signup page
	 */ 
	function index($page=FALSE, $error=FALSE)
	{
		// if no page was passed, set it to the default page
		if ( ! $page) $page = $this->_default_page();

		// verify this is a valid page for this funnel
		$valid	= $this->platform->post('sales_funnel/version/valid_page',array('funnel_id' => $this->_funnel_version, 'slug' => $page));
		
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


		$post_fields = ($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();

		$fields = array_merge($this->_form_fields, array_intersect_key($post_fields, $this->_form_fields));

		if ( ! empty($post_fields)):
			
			$fields = $this->_form_validation($fields);
			
			if (empty($this->_errors)):

				return $this->_process_form($fields);

			endif;

		endif;

		$this->tracking->hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => $page
			)
		);

		/*
		// track page hit
		$this->tracking->hit(
			$this->_session_key,
			$this->_partner_id,
			$this->_funnel_version,
			$page,
			$this->_affiliate_id,
			$this->_offer_id
		);
		*/

		// create method to run
		$method	= '_'.$page;

		// build this page
		return (method_exists($this, $method))
			? $this->$method()
			: $this->_select_v1();		// $page method doesn't exist - show default page instead
	}

	function _process_form($fields)
	{

		$action_id = $this->input->post('action_id');


		$this->tracking->page_action(
			array(
				'visitor_id'        => $this->session->userdata('visitor_id'),
				'action_id'         => $action_id,
				'conversion'        => TRUE,
				'conversion_amount' => '0.00'		
			)
		);

		/*
		$this->tracking->action(
			$this->_partner_id,
			$this->_funnel_version,
			$action_id,
			TRUE,
			'0.00',
			$this->_affiliate_id,
			$this->_offer_id
		);
		*/

		
		foreach ($fields as $key => $val):

			$this->session->set_userdata($key, $val);

		endforeach;

		return $this->funnel->redirect_form_action($this->_partner_id, $this->_funnel_version, $action_id);

	}


	function _form_validation($fields)
	{

		$this->load->library('domain_validation');
		$this->load->helper('email');

		if (empty($fields)):

			$this->_errors[] = 'Form submission error.';
			return $fields;

		endif;

		$fields = $this->_set_domain_fields($fields);

		if ($fields['core_type'] != 'register' && ! $this->domain_validation->is_valid_transfer_type($fields['core_type'])):

			unset($fields['core_type']);
			$this->_errors[] = 'Invalid domain transfer type.';

		endif;


		if (isset($fields['core_domain']) && $fields['core_sld'].'.'.$fields['core_tld'] != $fields['core_domain']):

			$fields          = $this->_clear_domain_fields($fields);
			$this->_errors[] = 'Core domain and domain pieces mismatch.';

		endif;


		if (isset($fields['core_domain']) && $this->domain_validation->is_domain_forbidden($fields['core_domain'])):

			$fields          = $this->_clear_domain_fields($fields);
			$this->_errors[] = 'Sorry, this domain is currently unavailable.';

		endif;

		if (isset($fields['core_tld']) && ! $this->domain_validation->is_valid_domain_tld($fields['core_tld'])):

			$fields          = $this->_clear_domain_fields($fields);
			$this->_errors[] = 'Invalid domain extension';

		endif;


		if (isset($fields['core_sld']) && ! $this->domain_validation->is_valid_domain_sld($fields['core_sld'])):
			
			$fields          = $this->_clear_domain_fields($fields);
			$this->_errors[] = 'Domains may only contain alphanumeric characters and hyphens.';

		endif;


		if (isset($fields['core_domain'])):

			$dom_function = (isset($fields['core_type']) && $fields['core_type']=='register') ? 'is_valid_register_domain' : 'is_valid_transfer_domain';

			if ( ! $this->domain_validation->$dom_function($fields['core_sld'], $fields['core_tld'])):

				$fields          = $this->_clear_domain_fields($fields);
				$this->_errors[] = 'Please choose a valid domain.';

			endif;

		endif;


		if (isset($fields['core_sld']) && isset($fields['core_tld']) && isset($fields['core_type'])):

			$availability = $this->platform->post('registrars/domain/is_available/'.$fields['core_sld'].'/'.$fields['core_tld']);

			if ( ! $availability['success']):

				if ($_SERVER['SERVER_ADDR'] != "127.0.0.1"):

					$this->_errors[] = 'Unable to check domain availability at this time. '.implode(' :: ',$availability['error']);
				endif;

			else:

				$is_available = $availability['data']['availability'];

				if ($is_available && $fields['core_type'] != 'register'):

					$this->_errors[] = 'Sorry, this domain is not registered and can\'t be transferred.';

				endif;

				if ( ! $is_available && $fields['core_type'] == 'register'):

					$this->_errors[] = 'Sorry, this domain is unavailable and cannot be registered.';

				endif;

			endif;

		endif;

		if (isset($fields['full_name']) && $fields['full_name']):

			$name = explode(' ',$fields['full_name']);

			$fields['first_name'] = array_shift($name);
			$fields['last_name'] = implode(' ',$name);

		endif;


		if ( ! valid_email($fields['email'])):

			unset($fields['email']);

		endif;

		if ( ! is_numeric($fields['core_num_years']) || $fields['core_num_years'] < 1):

			unset($fields['core_num_years']);
			$this->_errors[] = 'Invalid domain registration length.'; 

		endif;

		if (isset($fields['core_num_years'])):

			$fields['core_num_years'] = intval($fields['core_num_years']);

		endif;


		$fields['core_privacy'] = (bool) $fields['core_privacy'];

		return $fields;

	}

	function _set_domain_fields($fields)
	{

		if ($fields['core_tld'] && $fields['core_sld'] && ! $fields['core_domain']):

			$fields['core_domain'] = $fields['core_sld'] . '.' . $fields['core_tld'];

			if ($fields['core_domain']=='.'):

				$fields['core_domain'] = '';

			endif;

		endif;

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

		unset($fields['core_domain']);
		unset($fields['core_sld']);
		unset($fields['core_tld']);

		return $fields;

	}

	

	/**
	 * Default Page
	 * 
	 * This method gets the default signup page for this funnel
	 */
	private function _default_page()
	{	
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
	}


	/**
	 * Hosting_V1
	 * 
	 * This is the private method that will handle setting template for hoting_v1 page
	 */
	private function _select_v1()
	{
		// load custom language file
		$this->lang->load('select_v1');

		// set default template layout
		$this->template->set_layout('sales_funnel');

		// set default title
		$this->template->title($this->lang->line('hosting_title'));

		// prepend javascript and video
		$this->template->prepend_footermeta('

			<script type="text/javascript" src="/resources/modules/domain/assets/js/domain_spin.js"></script>

			<script type="text/javascript" src="/resources/brainhost/js/flowplayer/flowplayer-3.2.10.min.js"></script>
			<!-- this will install flowplayer inside previous A- tag. -->
			<script>
				flowplayer("player", "/resources/brainhost/js/flowplayer/flowplayer-3.2.5.swf", {
					plugins:  {
						controls: null,
					}
				});
			</script>
		');

		$data['errors'] = $this->_errors;
		$this->template->build('select_v1', $data);
	}

	/**
	 * Select V2
	 * 
	 * This method will handle showing the select_v2 signup page
	 */
	private function _select_v2()
	{
		// load custom language file
		$this->lang->load('select_v2');

		// set the page layout
		$this->template->set_layout('sales_funnel2');

		// set the page title
		$this->template->title($this->lang->line('hosting_title'));

		// append javascript and video
		$this->template->prepend_footermeta('

			<script type="text/javascript" src="/resources/modules/domain/assets/new/js/domain_spin.js"></script>

			<script type="text/javascript" src="/resources/brainhost/js/flowplayer/flowplayer-3.2.10.min.js"></script>
			<!-- this will install flowplayer inside previous A- tag. -->
			<script>
				flowplayer("player", "/resources/brainhost/js/flowplayer/flowplayer-3.2.5.swf", {
					plugins:  {
						controls: null,
					}
				});
			</script>
		');
		
		// build the page
		$data['errors'] = $this->_errors;
		$this->template->build('select_v2', $data);
	}

	/**
	 * Select V3
	 * 
	 * This method will handle displaying the select_v3 signup page
	 */
	private function _select_v3()
	{
		// load custom language file
		$this->lang->load('select_v3');

		// set layout to use
		$this->template->set_layout('sales_funnel2');

		// set the title
		$this->template->title($this->lang->line('hosting_title'));

		// prepend the javascript and video
		$this->template->prepend_footermeta('

			<script type="text/javascript" src="/resources/modules/domain/assets/new2/js/domain_spin.js"></script>

			<script type="text/javascript" src="/resources/brainhost/js/flowplayer/flowplayer-3.2.10.min.js"></script>
			<!-- this will install flowplayer inside previous A- tag. -->
			<script>
				flowplayer("player", "/resources/brainhost/js/flowplayer/flowplayer-3.2.5.swf", {
					plugins:  {
						controls: null,
					}
				});
			</script>
		');
		
		// build the page
		$data['errors'] = $this->_errors;
		$this->template->build('select_v3', $data);
	}


}