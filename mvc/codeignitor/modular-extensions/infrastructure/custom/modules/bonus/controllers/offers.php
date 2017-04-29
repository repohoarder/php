<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Offers
 * 
 * This class handles the functionality for one-click upsells
 */
class Offers extends MX_Controller
{

	/**
	 * The partner ID to attribute the sale to
	 * @var [type]
	 */
	var $_partner_id;

	/**
	 * The funnel id to grab prices from
	 * @var [type]
	 */
	var $_funnel_id;

	/**
	 * The language to display
	 * @var [type]
	 */
	var $_language;

	/**
	 * Information for this funnel id
	 * @var [type]
	 */
	var $_funnel_info;
	var $_id;
	var $_funnel_type;
	/**
	 * The aprtner funnel information
	 * @var [type]
	 */
	var $_partner_funnel_info;

	/**
	 * The client id to attribute the sale to
	 * @var [type]
	 */
	var $_client_id;

	var $_partner_info;

	var $_affiliate_id;
	var $_offer_id;
	var $_client_packs;
	
	public function __construct()
	{
		parent::__construct();

		// load the bonus config file
		$this->load->config('bonus');

		// load libraries
		$this->load->library('prices');
		$this->load->library('orders');

		// default affiliate and offer to 0
		$this->_affiliate_id 	= 0;
		$this->_offer_id 		= 0;
	}

	public function init($page='',$partner_id=FALSE,$funnel_id=FALSE,$client_id=FALSE)
	{
		// set client id
		$this->_client_id 	= $client_id;
		
		// if no client info, redirect to sales funnel
		if ( ! $this->_client_id)	redirect('initialize/'.$partner_id.'/'.$funnel_id);

		// if no partner id, default to 1
		$this->_partner_id 	= ( ! $partner_id)? 1: $partner_id;

		// if no funnel id, find partner default funnel
		$this->_funnel_id 	= ( ! $funnel_id)? $this->_get_default_funnel(): $funnel_id;

		$clicksrc = ($this->input->get('clicksrc') ? $this->input->get('clicksrc') : 'unknown');
		$this->platform->post(
			'sales_funnel/oneclicks/track_click',
			array(
				'client_id' => $client_id,
				'source'    => $clicksrc,
				'slug'      => $page
			)
		);
		$this->session->set_userdata('oneclick_source', $clicksrc);


		// verify this is a valid partner
		if ( ! $this->_valid_partner()):

			if ($this->_partner_id == 1):

				show_error('Unable to retrieve partner info');
				return;

			endif;

			# instead of redirecting, preserve $_POST by calling directly
			return $this->index(1, $funnel_id, 0, 0, $page, $client_id);

		endif;


		// verify this is a valid funnel for this partner/affiliate
		if ( ! $this->_valid_funnel()):

			$default_funnel = $this->_get_default_funnel();

			if ($this->_funnel_id == $default_funnel):

				show_error('Unable to retrieve funnel info');
				return;

			endif;

			# instead of redirecting, preserve $_POST by calling directly
			return $this->index($this->_partner_id, $default_funnel, 0, 0, $page, $client_id);

		endif;

		// make sure this is a valid page for this funnel
		$valid	= $this->platform->post('sales_funnel/version/valid_page',array('funnel_id' => $this->_funnel_id, 'slug' => $page));
		

		if ( ! in_array($_SERVER['REMOTE_ADDR'],array('127.0.0.1','98.100.69.22'))):

			// if no page was passed, then show error (or grab default page for this funnel)
			if	( ! $page OR ! $valid['success'] OR ! $valid['data']) 
				show_error('You are attempting to load an invalid page.');
		
		endif;
		
		$this->_client_packs = $this->_get_client_packs($client_id);
		// set partner info
		$this->_set_partner_info();

		// set funnel info
		$this->_set_funnel_info();

		// set partner funnel info
		$this->_set_partner_funnel_info();

		// grab default language for this partner/funnel
		$this->_set_default_language();

		// load the bonus language file
		$this->lang->load('bonus',$this->_language);
		$this->lang->load('footer',$this->_language);

		// set sessions
		$this->_set_sessions();
		
		
		// if we made it here, then everything was successful - do redirect
		redirect('bonus/offers/'.$page);
	}

	public function index($page='')
	{
		// make sure needed sessions are set
		//$this->debug->show($this->session->all_userdata(),true);

		// set variables
		$this->_client_id	= $this->session->userdata('client_id');
		$this->_partner_id	= $this->session->userdata('partner_id');
		$this->_funnel_id	= $this->session->userdata('funnel_id');
		$this->_id			= $this->session->userdata('client_id');
		
		// get packages from session;
		$packages = $this->session->userdata('packages');
		
		// if user is posting data, then we need to run the submit function
		if ($this->input->post()) :
			return $this->_submit($page);
		endif;
		// track page hit

		// grab page variables
		$variables				= $this->platform->post('sales_funnel/page/get',array('slug' => $page));
		$variables				= $variables['data'];

		// grab plan id
		$plan_id 				= $this->_get_plan_id($variables['plan_slug']);

		// grab service price
		$price 					= $this->_get_price($plan_id,$variables['term'],$variables['variant']);

		// initialize variables
		$data['page']               = $page;
		$data['_id']                = $this->_client_id;		// the order/client id
		$data['partner_id']         = $this->_partner_id;
		$data['funnel_id']          = $this->_funnel_id;
		$data['domain']				='';
		$data['affiliate_id']		= 0;
		$data['offer_id']			= 0;
		$data['funnel_type']		= 'one_click';
		$data['price']              = $price;
		$data['term']               = $variables['term'];
		$data['variant']            = $variables['variant'];
		$data['slug']               = $variables['plan_slug'];
		$data['plan_id']            = $plan_id;
		$data['funnel_type']		= 'client';
		$data['partner_data']       = $this->session->userdata('partner_info');
		
		// set this variable to suppress the no thanks link
		$data['shownothanks']		= true;
		$data['noexitpop']			= true;
		// initialize the page and data
		$initialize	= $this->_initialize($page,$data);
		
		// set page and data variables
		$page			= $initialize['page'];
		$data			= $initialize['data'];
		
		if (isset($variables['theme']) && !empty($variables['theme'])):

			$this->template->set_theme($variables['theme']);

		endif;

		// set the layout to use (grabbed from bonus config for this page)
		$this->template->set_layout('upsell_no_warn');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/oclick.css" />');
		// load this page's title from the language library
		$this->template->title($this->lang->line('bonus_'.$page.'_title'));
		

		if ( ! isset($variables['theme']) || $variables['theme'] == 'allphase_funnel'):

			// Load custom CSS
			$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/style2.css" />');

		endif;
	
		// see if this page has a custom style sheet 
		if (file_exists(CUSTOM_PATH.'/modules/bonus/assets/css/'.$page.'.css'))
			//$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/bonus/assets/css/'.$page.'.css">');
		
		// bypass initialize for these variables. the library is overwriting this from the upsell queue
		
		$data['domain']				= $packages['core_domain'];
		$data['domain_pack_id']		= $packages['domain_pack_id'];
		// build the page
		
		$this->template->build("oneclick/$page", $data);
	}

	private function _get_client_packs($client_id) {
		
		$client = $this->platform->post('ubersmith/package/get/client_id/'.$client_id);
		
		if($client['success']) :
			
			$packs =  $client['data'];
			
			// loop thru the packages and sort out domain
			
			foreach($packs as $index=>$package) :
				if($package['servtype'] == 'domain' && preg_match('/Core/',$package['desserv'])) :
					
					$packages['domain_pack_id'] = $package['packid'];
					$packages['core_domain']	= $package['servername'];
					
					else:
						
					$packages['upsells'][] = $package;
					
				endif;
				
			endforeach;
			
			return $packages;
			
		endif;
	}

	private function _submit($page)
	{
		//
		$client_id = $this->session->userdata('client_id');
		// load the services library
		$this->load->library('services');


		if ($this->session->userdata('oneclick_source')):

			$this->platform->post(
				'sales_funnel/oneclicks/track_purchase',
				array(
					'client_id' => $client_id,
					'source'    => $this->session->userdata('oneclick_source'),
					'slug'      => $page
				)
			);

			// @mail('travis.loudin@brainhost.com','BH One-click attempt',$client_id.' '.$page.' '.$this->session->userdata('oneclick_source'));

		endif;

		// initialize variables
		$post	= $this->_set_submit_vars();
				
		// run any custom functions this service needs to run
		if(method_exists($this->services,$page)):
			
			$post = $this->services->$page($post);
			
		endif;
		
		// see if we need to add services to the order/client
		if ($post['add_service'] === TRUE AND isset($post['plans'])):
	
			//echo"<pre>";
			//print_r($post);
			foreach($post['plans'] AS $index=>$service) :
		
				if(is_array($service)) :
						
						$servicename = $post['plans'][$index]['service'];
						$meta = array();
						// set method to run (depending on order or client funnel type)
						$method	= '_build_'.$servicename;

						// create array to add pack to order/client
						if(method_exists($this,$method)) :
							$meta	= $this->$method($servicename,$post['plans'][$index]);
						endif;
						
						$this->_add_packs($servicename,$meta);
					
					
				else:
					
						$method	= '_build_'.$service;
						$meta = array();
						// create array to add pack to order/client
						if(method_exists($this,$method)) :
							$meta	= $this->$method($service,$post[$service]);
						endif;
			
						$this->_add_packs($service,$meta);
				endif;
					
			endforeach;	// End looping through services to add
		endif;	// end seeing if we need to add services
		
		
		// process (gen & charge invoice for client)
		$inv = $this->platform->post('ubersmith/invoice/generate/'.$client_id);
		
		if($inv['success']) :
			
			// set invoice id
			$invoice_id = isset($inv['data']['invid']) ? $inv['data']['invid'] : false;
		
		endif;
		
		// check for valid invoice_id
		if( ! is_numeric($invoice_id) ||  ! $invoice_id) :
			// redirect to decline
			redirect('/billing/declined');
		
		endif;
		
		// charge the invoice that was just created
		$charge = $this->platform->post('ubersmith/invoice/charge/'.$invoice_id);
		
		//if not redirect to declined
		if(!$charge['success']) :
			redirect('/billing/declined');
		endif;

		if ($this->session->userdata('oneclick_source')):

			$this->platform->post(
				'sales_funnel/oneclicks/track_success',
				array(
					'client_id' => $client_id,
					'source'    => $this->session->userdata('oneclick_source'),
					'slug'      => $page
				)
			);

			@mail('travis.loudin@brainhost.com','BH One-click SUCCESS',$client_id.' '.$page.' '.$this->session->userdata('oneclick_source'));

		endif;
		
		// redirect to completed page
		redirect('/completed/sale/invoice/'.$invoice_id.'/completed');
		
	}

	private function _get_default_funnel()
	{
		$default_funnel = 1;

		$def		= array(
			'partner_id'	=> $this->_partner_id,
			'affiliate_id'	=> 0
		);
		
		// gets the default version for this partner/affiliate
		$default	= $this->platform->post('sales_funnel/version/get_default', $def);

		if ($default['success'] && isset($default['data'][0]['funnel_id'])):

			$default_funnel = $default['data'][0]['funnel_id'];

		endif;

		if (isset($this->_partner_info['website']['default_funnels']['hosting'])):

			$default_funnel = $this->_partner_info['website']['default_funnels']['hosting'];

		endif;

		return $default_funnel;
	}
	
	/**
	 * Valid Partner
	 * 
	 * This method determines if user is a valid partner
	 */
	private function _valid_partner()
	{
		// set post array
		$partner	= array(
			'partner_id'	=> $this->_partner_id
		);
		
		// determine if partner is valid
		$valid	= $this->platform->post('sales_funnel/partner/valid',$partner);		

		// if not valid, return false
		if ( ! $valid['success'] OR ! $valid['data']) return FALSE;
		
		// if we made it here, then we have a valid partner
		return TRUE;
	}
	
	/**
	 * Valid Funnel
	 * 
	 * This method detemrines if this funnel id is valid for this partner/affiliate
	 */
	private function _valid_funnel()
	{
		// create post array to determine valid funnel
		$valid		= array(
			'funnel_id'		=> $this->_funnel_id,
			'partner_id'	=> $this->_partner_id,
			'affiliate_id'	=> $this->_affiliate_id
		);
		
		// gets the default version for this partner/affiliate
		$default	= $this->platform->post('sales_funnel/version/valid_funnel',$valid);

		// if not valid, return false
		if ( ! $default['success'] OR ! $default['data']) return FALSE;
		
		// if we made it here, then we have a valid funnel
		return TRUE;
	}

	private function _set_default_language()
	{

		// grab language from platform
		$lang 		= $this->platform->post('partner/language/get',array('partner_id' => $this->_partner_id, 'funnel_id' => $this->_funnel_id));

		// if grabbing language was unsuccessful, set session and return
		if ( ! $lang['success'] OR ! is_array($lang['data']) OR empty($lang['data'])):

			return;

		endif;

		// set global language variable
		$this->_language 	= (isset($lang['data']['slug']) AND ! empty($lang['data']['slug']))? $lang['data']['slug']: $this->_language;
	}

	function _set_partner_funnel_info()
	{

		$resp = $this->platform->post(
			'partner/funnels/get',
			array(
				'partner_id' => $this->_partner_id
			)
		);

		if ( ! $resp['success']):

			return;

		endif;
		
		foreach ($resp['data'] as $funnel)
		{
			if ($funnel['funnel_id']==$this->_funnel_id)
			{
				$resp['data']	= $funnel;
			}
		}

		$keys = array(
			'id',
			'white_label',
			'exit_pop'
		);

		$template = array_combine(
			$keys,
			array_fill(0,count($keys),FALSE)
		);

		$this->_partner_funnel_info = array_filter(array_intersect_key($resp['data'],$template));

	}

	function _set_funnel_info()
	{

		$resp = $this->platform->post(
			'sales_funnel/funnel/get',
			array(
				'funnel_id' => $this->_funnel_id
			)
		);

		if ( ! $resp['success']):

			return;

		endif;

		$keys = array(
			'name',
			'slug',
			'funnel_type',
			'default_page_id'
		);

		$template = array_combine(
			$keys,
			array_fill(0,count($keys),FALSE)
		);

		$this->_funnel_info = array_filter(array_intersect_key($resp['data'],$template));

	}

	private function _set_sessions()
	{
		// set allowed sessions
		$allowed_sessions = array(
			'session_id',
			'last_activity',
			'ip_address',
			'user_agent',
			'_language',
			'packages',
			'oneclick_source'
		);

		// grab current sessions
		$current_sessions = $this->session->all_userdata();

		// kill current sessions
		$current_sessions = array_diff_key(array_filter($current_sessions), array_flip($allowed_sessions));
		foreach ($current_sessions as $key => $value):

			$this->session->unset_userdata($key);

		endforeach;
		

		// set language session
		$this->session->set_userdata('_language', $this->_language);

		// set partner id session
		$this->session->set_userdata('partner_id', $this->_partner_id);
		
		// set funnel id session
		$this->session->set_userdata('funnel_id', $this->_funnel_id);

		// set partner funnel information session
		$this->session->set_userdata('funnel_info', $this->_funnel_info);
		
		// set partner funnel information session
		$this->session->set_userdata('partner_funnel_info', $this->_partner_funnel_info);

		// set client id to session
		$this->session->set_userdata('client_id', $this->_client_id);

		// set partner info
		$this->session->set_userdata('partner_info', $this->_partner_info);
		
		$this->session->set_userdata('packages', $this->_client_packs);
		$this->session->set_userdata('_id', '');
		$this->session->set_userdata('_funnel_type', '');
		return;
	}

	/**
	 * Initialize
	 * 
	 * This method runs pre view functions for "bonus" (upsell) pages if one exists in library.
	 * 
	 * @example initialize('test_page',array('test' => 'data'));
	 * 
	 * @param	string	$page	This is the page name that user is accessing
	 * @param	array	$data	This is the data array that has already been set (and needs kept)
	 */
	private	function _initialize($page,$data=array()){
		
		// load the initialization library
		$this->load->library('initialize');
		
		// initialize variables
		$initialize	= array(
			'page'	=> $page,
			'data'	=> $data			
		);

		// see if this page has an initialization method
		if(method_exists($this->initialize,$page)) $initialize = $this->initialize->$page($page,$data);
		
		// return array
		return $initialize;
	}

	/**
	 * This method gets the ubersmith plan id from a service slug
	 * @param  boolean $slug [description]
	 * @return [type]        [description]
	 */
	private function _get_plan_id($slug=FALSE)
	{
		// grab slug
		return $this->orders->get_plan_id($slug);
	}

	/**
	 * This emthod gets a price for a specific plan/term/variant
	 * @return [type] [description]
	 */
	private function _get_price($plan_id=FALSE,$term='12',$variant='default')
	{
		if ( ! $plan_id OR ! is_numeric($plan_id))
			return FALSE;

		
		// grab prices data for this plan
		$price		= $this->prices->get($this->_funnel_id,$this->_partner_id,$this->_affiliate_id,$this->_offer_id,$plan_id,$variant,$term);

		// if we were unable to grab the price, then we can not add this service
		if ($price === FALSE OR ! is_float($price))	return FALSE;

		return number_format($price,2);
	}

	/**
	 * Set POST Vars
	 * 
	 * This method sets all needed submit variables
	 */
	private function _set_submit_vars()
	{
		$post	= $this->input->post();
		
		/*  /REMOVED THIS FOR ONECLICKSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS BIAAAAAAATCHS!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// grab funnel action info
		$action	= $this->platform->post('sales_funnel/action/get_funnel_action',array('action_id' => $post['action_id'], 'funnel_id' => $this->_funnel_id));

		// if not successful, then this page doesn't have access to be submitted in this funnel
		if ( ! $action['success'])
			show_error('You are attempting to load an invalid page.  Please <a href="/initialize/'.$this->_partner_id.'/'.$this->_funnel_id.'">click here</a> to continue.');
			
		// set the next page id
		$post['next_page_id']	= $action['data']['next_page_id'];	// This variable determins the next page to show
		*/
		
		// set the add service boolean
		$post['add_service']	= TRUE;	// This boolean tells us whether to add a service or not

		// set funnel variables
		$post['partner_id']		= $this->_partner_id;
		$post['funnel_id']		= $this->_funnel_id;
		$post['funnel_type']	= $this->_funnel_type;
		$post['affiliate_id']	= $this->_affiliate_id;
		$post['offer_id']		= $this->_offer_id;
		$post['_id']			= $this->_id;
		
		// return $post
		return $post;
	}

	function _set_partner_info()
	{

		$wanted = array(
			'uber_client_id',
			'first_name',
			'last_name',
			'address',
			'city',
			'state',
			'zip',
			'country',
			'phone',
			'brand',
			'website',
			'merchant'
		);

		$p_info = $this->platform->post(
			'partner/account/details',
			array(
				'partner_id' => $this->_partner_id
			)
		);

		if ( ! $p_info['success']):

			return;

		endif;

		$wanted_assoc = array_combine($wanted, array_fill(0,count($wanted),0));			
		$data         = array_shift($p_info['data']);			
		$info         = array_filter(array_intersect_key($data, $wanted_assoc));

		if (isset($info['website']['0']) && ! isset($info['website']['id'])):

			$info['website'] = array_shift($info['website']);

		endif;

		// format merchant info
		if (isset($info['merchant'][0])):

			$info['merchant']	= $info['merchant'][0];

		endif;

		$this->_partner_info = $info;
	}
	/**
	 * 
	 * @param type $service
	 */
	private function _add_packs($service,$meta = array()) {
		
				$client_id = $this->_client_id;
			// grab page variables for this service
				$variables	= 	$this->platform->post('sales_funnel/page/get',array('slug' => $service));
				
				// if we were unable to grab service information, then go to next upsell
				if ( ! $variables['success']) :
					return;
				endif;
				// set service's variables
				$services = $variables['data'];
				
				// get plan id
				$plan_id	= $this->_get_plan_id($services['plan_slug']);
				
				
				// if unable to get plan id, then we can't add service
				if ( ! $plan_id OR ! is_numeric($plan_id)):
					return;
				endif;
				

				$serveArr = array();
				$serveArr['description']	= $services['name'];
				$serveArr['variant']		= $services['variant'];
				$serveArr['term']			= $services['term'];
				
				$serveArr = array_merge($serveArr,$meta);
				
				// get price
				$serveArr['price']		= $this->_get_price($plan_id, $serveArr['term'], $serveArr['variant']);
				
				
				if( isset($post['domain_pack_id'])) :
					
					$serveArr['parent_id'] = $post['domain_pack_id'];
				
				endif;
				
				// add package to client
				$add = $this->platform->post("ubersmith/package/add/$client_id/$plan_id",$serveArr);
				//var_dump($add);
	}
	
	private function _build_addon_domain($service,$meta) {
		
		$arr['name']  = $meta['name'];
		$arr['enable_cpanel'] = $meta['enable_cpanel'];
		$arr['server']			= $meta['server'];
		$arr['description']		= "Addon Domain: " .$meta['server'];
		$arr['meta'] = $meta['meta'];
		
		return $arr;
	}
	
	private function _build_traffic($service,$meta) {
		
		$arr = array();

		if (array_key_exists('meta',$meta)):
			$arr = $meta['meta'];
		endif;
		
		$arr['meta']		= $meta['meta'];
		$arr['parent_id']	= $meta['parent_id'];
		$arr['variant']		= $meta['variant'];
		return $arr;
	}
	
	
}