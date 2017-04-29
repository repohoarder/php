<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Bonus
 * 
 * This class handles the functionality for each sales funnel (bonuses)
 * 
 * @method	view		index(string $page)						This method loads the requested page
 * @method	array		_initialize(string $page, array $data)	This method runs functions for services before they are loaded into a view
 * @method	redirect	_submit(string $page)					This method handles submission of services to the platform and redirecting to the next page
 * @method	string		_subdomain()							This method returns which subdomain we are currently using
 * @method	string		_get_funnel_type()						This method determines whether this is a order or client funnel
 * 
 * @todo	Make this->lang->line load within the view (bonus is autoloaded but doesnt work in view)
 */
class Bonus extends MX_Controller
{
	/**
	 * Order/Client ID
	 */
	var $_id;
	
	/**
	 * This variable holds which version of the funnel we are in (funnel id)
	 * 
	 * @var boolean|string
	 */
	var $_funnel_id	= FALSE;
	
	/**
	 * This variable determines the type of funnel we are using (order or client)
	 * 
	 * @var string
	 */
	var $_funnel_type		= FALSE;

	/**
	 * Partner ID
	 * 
	 * @var  _partner_id description
	 */
	var $_partner_id;

	/**
	 * Affiliate ID
	 * 
	 * @var  _partner_id description
	 */
	var $_affiliate_id;

	/**
	 * Affiliate Offer ID
	 * 
	 * @var  _partner_id description
	 */
	var $_offer_id;
	
	function __construct()
	{
		parent::__construct();

		## TESTING
		//$this->session->set_userdata('_id', '349612');
 
		// load the bonus language file
		$this->lang->load('bonus',$this->session->userdata('_language'));
		$this->lang->load('footer',$this->session->userdata('_language'));
		
		// load the bonus config file
		$this->load->config('bonus');
		
		// cache this page (1 day)
		//$this->template->set_cache(86400);
		
		// set order/client id
		$this->_id					= $this->session->userdata('_id');
		
		// set the funnel type (order or client)
		$this->_funnel_type			= $this->_get_funnel_type();
		
		// determine funnel "version" to show
		$this->_funnel_id			= $this->version->get();

		// set partner id
		$this->_partner_id			= $this->session->userdata('partner_id');

		// set affiliate id
		$this->_affiliate_id		= $this->session->userdata('affiliate_id');

		// set offer id
		$this->_offer_id			= $this->session->userdata('offer_id');

		// make sure user has order id in session
		if ( ! $this->_id OR ! is_numeric($this->_id)):
			redirect('initialize/'.$this->_partner_id.'/'.$this->_funnel_id);	// Redirect to initialize (beginning of process)
			return;
			//$this->_id 	= '136006';
		endif;

		// make sure user has a partner id
		if ( ! $this->_partner_id OR ! is_numeric($this->_partner_id)):
			redirect('initialize/'.$this->_partner_id.'/'.$this->_funnel_id);	// Redirect to initialize (beginning of process)		
			return;
			//$this->_partner_id = 1;
		endif;

		// load libraries
		$this->load->library('prices');
		$this->load->library('orders');
	}
	
	function index($page=false,$error=false)
	{

		// make sure this is a valid page for this funnel
		$valid	= $this->platform->post('sales_funnel/version/valid_page',array('funnel_id' => $this->_funnel_id, 'slug' => $page));
		

		if ( ! in_array($_SERVER['REMOTE_ADDR'],array('127.0.0.1','98.100.69.22'))):

			// if no page was passed, then show error (or grab default page for this funnel)
			if	( ! $page OR ! $valid['success'] OR ! $valid['data']) 
				show_error('You are attempting to load an invalid page.  Please <a href="/">click here</a> to continue.');
		
		endif;

		// if user is posting data, then we need to run the submit function
		if ($this->input->post())
			return $this->_submit($page);
		
		// track page hit
		$this->tracking->page_hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => $page
			)
		);
		
		// grab page variables
		$variables				= $this->platform->post('sales_funnel/page/get',array('slug' => $page));
		$variables				= $variables['data'];
		
		
		// grab plan id
		$plan_id 				= $this->_get_plan_id($variables['plan_slug']);

		// grab service price
		$price 					= $this->_get_price($plan_id,$variables['term'],$variables['variant']);

		// initialize variables
		$data['page']               = $page;
		$data['error']              = $error;
		$data['funnel_type']        = $this->_funnel_type;	// set funnel type so other functions may use it (eg: initialize)
		$data['_id']                = $this->_id;			// the order/client id
		$data['partner_id']         = $this->_partner_id;
		$data['funnel_id']          = $this->_funnel_id;
		$data['affiliate_id']       = $this->_affiliate_id;
		$data['offer_id']           = $this->_offer_id;
		$data['price']              = $price;
		$data['term']               = $variables['term'];
		$data['variant']            = $variables['variant'];
		$data['slug']               = $variables['plan_slug'];
		$data['plan_id']            = $plan_id;
		
		$data['partner_data']       = $this->session->userdata('partner_info');
		
		$data['display_pixel_type'] = 'post_billing';
		
		// initialize the page and data
		$initialize	= $this->_initialize($page,$data);

		if (isset($initialize['skip_action']) && $initialize['skip_action']):

			return $this->_skip_action($initialize['skip_action']);

		endif;
		
		// set page and data variables
		$page			= $initialize['page'];
		$data			= $initialize['data'];
		
		if (isset($variables['theme']) AND $variables['theme'] != ''):

			$this->template->set_theme($variables['theme']);

		endif;

		// set the layout to use (grabbed from bonus config for this page)
		$this->template->set_layout($variables['layout']);

		// load this page's title from the language library
		$this->template->title($this->lang->line('bonus_'.$page.'_title'));
		

		if ( empty($variables['theme']) || $variables['theme'] == 'allphase_funnel'):

			// Load custom CSS
			$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/style2.css" />');

		endif;
		
		// this is so that BH can show pixel on first upsell page
		$this->_affiliate_callback();
	
		// see if this page has a custom style sheet 
		if (file_exists(CUSTOM_PATH.'/modules/bonus/assets/css/'.$page.'.css'))
			$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/bonus/assets/css/'.$page.'.css">');
		
		// build the page
		$this->template->build($page, $data);
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
	 * Bonus Form Submission
	 * 
	 * This method handles submission of all "bonus" forms
	 * 
	 * @example submit('test_page');
	 * 
	 * @param	string $page	This variable is the page name that is submitting the data
	 */
	private function _submit($page='')
	{
		// load the services library
		$this->load->library('services');

		// initialize variables
		$post					= $this->_set_submit_vars();
		$tracking_amount		= 0;							// This variable resets the $ amount to be tracked
		$tracking_conversion	= FALSE;						// This variable says whether or not to track a conversion
				
		// run any custom functions this service needs to run
		if(method_exists($this->services,$page)) $post = $this->services->$page($post);


		if (isset($post['custom_conversion_amount'])):
			$tracking_amount		= $post['custom_conversion_amount']; // This variable resets the $ amount to be tracked
			$tracking_conversion	= TRUE;
		endif;


		// see if we need to add services to the order/client
		if ($post['add_service'] === TRUE AND isset($post['plans'])):

			// iterate plans and add to order/client
			foreach($post['plans'] AS $service):

				// if service is an array, then we need to break it out (for example: addon domains - where we need to add multiple of the same service)
				if (is_array($service)):

					// set service array to a separate variable
					$service_array 				= $service;

					// set service variable to the service slug
					$service 					= $service_array['service'];

					$post[$service] = array(
						'name'    => FALSE,
						'meta'    => array(),
						'variant' => 'default'
					);

					$post[$service] = array_merge($post[$service], array_filter($service_array));

					/*
					// set name (if set)
					$post[$service]['name']		= (isset($service_array['name']) AND ! empty($service_array['name']))
						? $service_array['name']
						: FALSE;						// set to FALSE so as not to overwrite

					// set meta (if set)
					$post[$service]['meta']		= (isset($service_array['meta']) AND ! empty($service_array['meta']))
						? $service_array['meta']		// the meta passed from 'services' library
						: array();						// empty array

					// set variant (if set)
					$post[$service]['variant']	= (isset($service_array['variant']))
						? $service_array['variant']		// variant passed from 'services' library
						: 'default';
					*/

				endif;
				
				// grab page variables for this service
				$variables	= 	$this->platform->post('sales_funnel/page/get',array('slug' => $service));

				// if we were unable to grab service information, then go to next upsell
				if ( ! $variables['success']) continue;
				
				// set service's variables
				$services = $variables['data'];

				######################
				$services = array_merge($services, array_filter($post[$service]));

				if ( ! isset($services['meta'])):

					$services['meta'] = array();

				endif;

				$services['meta']['funnel_id']		= $this->_funnel_id;
				$services['meta']['partner_id']		= $this->_partner_id;
				$services['meta']['affiliate_id']	= $this->_affiliate_id;
				$services['meta']['offer_id']		= $this->_offer_id;
				$services['meta']['variant']		= $services['variant'];

				/*
				// see if variant was passed from 'services library' - if so, override default
				if (isset($post[$service]['variant']) AND ! empty($post[$service]['variant']))	$services['variant']	= $post[$service]['variant'];

				// see if custom name was passed
				$services['name']		= (isset($post[$service]['name']) AND ! empty($post[$service]['name']))? $post[$service]['name']: $services['name'];

				// see if parent_id is set, and if so, add it to service array
				if (isset($post[$service]['parent_id']))
					$services['parent_id']	= $post[$service]['parent_id'];

				// see if custom pack count was set (in case we are updating a pack on an order instead of adding one)
				if (isset($post[$service]['pack_count']) AND is_numeric($post[$service]['pack_count']))
					$services['pack_count']	= $post[$service]['pack_count'];

				// see if custom meta was passed for this service - if so, set it
				$services['meta']		= (isset($post[$service]['meta']) AND ! empty($post[$service]['meta']))? $post[$service]['meta']: array();

				// set meta variables needing added
				$services['meta']['funnel_id']		= $this->_funnel_id;
				$services['meta']['partner_id']		= $this->_partner_id;
				$services['meta']['affiliate_id']	= $this->_affiliate_id;
				$services['meta']['offer_id']		= $this->_offer_id;
				$services['meta']['variant']		= $services['variant'];
				*/
				######################


				// get plan id
				$services['plan_id']	= $this->_get_plan_id($services['plan_slug']);

				// if unable to get plan id, then we can't add service
				if ( ! $services['plan_id'] OR ! is_numeric($services['plan_id']))
					continue;

				// get price
				$services['price']		= $this->_get_price($services['plan_id'], $services['term'], $services['variant']);

				// if no price was grabbed, then we can not add anything
				if ($services['price'] === FALSE)
					continue;				

				// set method to run (depending on order or client funnel type)
				$method	= '_update_'.$this->_funnel_type;

				// create array to add pack to order/client
				$arr	= $this->$method($services);

				// add service to order/client
				if ($arr !== FALSE AND ! empty($arr)):

					// add/update the cart
					$cart 	= $this->platform->post('crm/cart/add',$arr);

					// update tracking conversion boolean
					$tracking_conversion	= TRUE;
					
					// add price to tracking amount
					$tracking_amount+=$services['price'];

				endif;
				
			endforeach;	// End looping through services to add
		
		endif;	// End seeing if we need to add services

		// track page action
		$this->tracking->page_action(
			array(
				'visitor_id'        => $this->session->userdata('visitor_id'),
				'action_id'         => $this->input->post('action_id'),
				'conversion'        => $tracking_conversion,
				'conversion_amount' => $tracking_amount		
			)
		);
		
		// determine next page slug
		$next_page	= $this->platform->post('sales_funnel/page/get_by_id',array('id' => $post['next_page_id']));
		
		/*
		// if we are unable to grab the type, redirect to processing - else send to next page
		( ! $next_page['success'] OR $next_page['data']['type'] === 'completed')
			? redirect('billing/processing/sale/completed/'.$this->_funnel_type.'/'.$this->_id)	// the thank you page ## TODO: Make it so that partner/funnel/affiliate/offer can have dynamic TY Page
			: redirect('bonus/'.$next_page['data']['slug']);
		*/

		redirect($next_page['data']['uri']);
		return;
	}

	/**
	 * This method skips a page and tracks an action
	 * @param  [type] $action_id [description]
	 * @return [type]            [description]
	 */
	public function _skip_action($action_id)
	{
		// track page action
		$this->tracking->page_action(
			array(
				'visitor_id'        => $this->session->userdata('visitor_id'),
				'action_id'         => $action_id,
				'conversion'        => FALSE,
				'conversion_amount' => 0.00		
			)
		);

		// get the action data
		$action	= $this->platform->post(
			'sales_funnel/action/get_funnel_action',
			array(
				'action_id' => $action_id, 
				'funnel_id' => $this->_funnel_id
			)
		);

		// grab next page for this action
		$next_page_id = $action['data']['next_page_id'];

		// grab page by id
		$next_page	= $this->platform->post(
			'sales_funnel/page/get_by_id',
			array(
				'id' => $next_page_id
			)
		);

		// default next page to show as the completed page
		$page = 'billing/processing/sale/completed/'.$this->_funnel_type.'/'.$this->_id;

		// determine if next page to show is completed page or not
		if ($next_page['success'] && $next_page['data']['type'] !== 'completed'):

			// if not completed page, then set page to the page slug to redirect to
			$page = $next_page['data']['uri'];//'bonus/'.$next_page['data']['slug'];

		endif;

		// perform redirect
		redirect($page);
		return;		
	}
	
	/**
	 * Update Order
	 * 
	 * This method creates the update array for an order
	 */
	private function _update_order($service=array())
	{		
		// make sure we have service info
		if (empty($service)) return FALSE;
		
		// get the current pack count on the ubersmith order
		$pack_count	= $this->_get_order_pack_count($this->_id);
		
		// if we were unable to get the pack count, then we won't be able to add this service
		if ($pack_count === FALSE) return FALSE;
		
		// increment pack count
		$pack_count++;

		// if custom pack_count was passed, then use it instead (for example: double_traffic)
		if (isset($service['pack_count']) AND is_numeric($service['pack_count']))
			$pack_count 	= $service['pack_count'];

		$add		= array(
			'type'			=> 'order',
			'order_id'		=> $this->_id,				// This is the order/client _id
			'info'			=> array(
				'pack'.$pack_count	=> array(
					'plan_id'	=> $service['plan_id'],
					'price'		=> $service['price'],
					'desserv'	=> $service['name'],
					//'period'	=> '',
					'comment'	=> 'Added By: '.getcwd()
				)
			),
			'funnel_id'		=> $this->_funnel_id,	// The funnel id
			'partner_id'	=> $this->_partner_id		// The partner id
		);
		
		if( $this->input->post('period')) :
		
			$add['info']['pack'.$pack_count]['period'] = $this->input->post('period');
		
		endif;
		// if parent id was passed, then we need to set it
		if (isset($service['parent_id']))
			$add['info']['pack'.$pack_count]['parentpack']	= $service['parent_id'];

		// see if meta variables are set
		if (isset($service['meta'])):
			// iterate through meta values
			foreach($service['meta'] AS $name => $value):
				// add to meta array
				$add['info']['pack'.$pack_count][$name]	= $value;
			endforeach;	// End iterating meta values
		endif;	// End if meta is set
		
		
		##### 
		# Begin code to allow Addon Domains to pass needed info to order update
		# Need to pass enable_cpanel, userid, pass, etc for addon domain packs
		# Will add any keys not added above to the top level of $pack[$count]
		$custom_keys = array_filter( // remove empty values from resulting array
			array_diff_key( // get only the keys in $service that arent listed
				$service,
				array_flip( // turn the below list into a set of array keys
					array(
						'service',
						'variant',
						'name',
						'meta',
						'parent_id',
						'id',
						'type',
						'slug',
						'uri',
						'plan_slug',
						'term',
						'layout',
						'date_added',
						'plan_id',
						'price'
					)
				)
			)
		);

		if (is_array($custom_keys) && count($custom_keys)):

			foreach ($custom_keys as $key => $val):

				$add['info']['pack'.$pack_count][$key] = $val;

			endforeach;

		endif;

		# End Addon Domain code
		######
		

		// return formatted array
		return $add;
	}
	
	/**
	 * Update Client
	 * 
	 * This method creates the update array for a client
	 */
	private function _update_client($service)
	{
		// make sure we have service info
		if (empty($service)) return FALSE;
		
		$add	= array(
			'type'			=> 'client',
			'client_id'		=> $this->_id,
			'plan_id'		=> $service['plan_id'],
			//'parent_id',
			//'period',
			'price'			=> $service['price'],
			'desserv'		=> $service['name'],
			'funnel_id'		=> $this->_funnel_id,	// The funnel_id
			'partner_id'	=> $this->_partner_id		// The partner_id
		);

		// if parent id was passed, then we need to set it
		if (isset($service['parent_id']) AND is_numeric($service['parent_id']))
			$add['parent_id']	= $service['parent_id'];
		
		// see if meta variables are set
		if (isset($service['meta'])):
			// iterate through meta values
			foreach($service['meta'] AS $name => $value):
				// add to meta array
				$add['meta_'.$name]	= $value;
			endforeach;	// End iterating meta values
		endif;	// End if meta is set
		
		// return formatted array
		return $add;
	}

	/**
	 * Subdomain
	 * 
	 * This method grabs the current subdomain we are working in
	 * 
	 * @return	string	The value returned will be the subdomain we are working in
	 */
	private function _subdomain()
	{
		// grab the parsed url
		$url		= parse_url($_SERVER['HTTP_HOST']);
		
		// explode the URL into segments
		$host		= explode('.',$url['path']);
		
		// grab subdomain (this accounts for sub.domain.example.com)
		$subdomain	= array_slice($host, 0, count($host) - 2 );

		// return subdomain
		return $subdomain[0];
	}
	
	/**
	 * Bonus
	 * 
	 * This method grabs the array of pages to show for this funnel
	 */
	private function _bonus($version)
	{
		// grab all pages in this funnel
		$funnel	= $this->platform->post('sales_funnel/version/get',array('version' => $version));
	}
	
	/**
	 * Get Funnel Type
	 * 
	 * This method determines whether the current funnel is order or client
	 */
	private function _get_funnel_type()
	{	
		// load funnel config (from subdomain, default back to infrastructure)
		# $type	= $this->config->item('_type');
		
		$type = ($this->session->userdata('_type'))
			? $this->session->userdata('_type')
			: 'order';

		// set global variable
		return $type;
	}
	
	/**
	 * Set POST Vars
	 * 
	 * This method sets all needed submit variables
	 */
	private function _set_submit_vars()
	{
		$post	= $this->input->post();
		
		// grab funnel action info
		$action	= $this->platform->post('sales_funnel/action/get_funnel_action',array('action_id' => $post['action_id'], 'funnel_id' => $this->_funnel_id));

		// if not successful, then this page doesn't have access to be submitted in this funnel
		if ( ! $action['success'])
			show_error('You are attempting to load an invalid page.  Please <a href="/initialize/'.$this->_partner_id.'/'.$this->_funnel_id.'">click here</a> to continue.');
			
		// set the next page id
		$post['next_page_id']	= $action['data']['next_page_id'];	// This variable determins the next page to show
		
		// set the add service boolean
		$post['add_service']	= ($action['data']['add_service'])? TRUE : FALSE;	// This boolean tells us whether to add a service or not

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
	 * This method gets the current pack count for an order
	 * @param  boolean $order_id [description]
	 * @return [type]            [description]
	 */
	private function _get_order_pack_count($order_id=FALSE)
	{
		// grab pack count for order
		return $this->orders->get_order_pack_count($order_id);
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

	function _affiliate_callback()
	{

		if ( ! $this->session->userdata('completed_billing_page')):

			return;

		endif;

		$this->session->unset_userdata('completed_billing_page');

		if ( ! $this->_affiliate_id || ! $this->_offer_id || $this->session->userdata('_type') != 'order'):

			return;

		endif;

		$order_id = $this->session->userdata('_id');

		if ( ! $order_id):

			return;

		endif;


		$order_resp = $this->platform->post(
			'ubersmith/order/get/',
			array(
				'order_id' => $order_id
			)
		);

		if ( ! $order_resp['success']):

			return;

		endif;

		$order = $order_resp['data'];

		$response = $this->platform->post(
			'affiliate/get_affiliate_offer_info/'.$this->_affiliate_id.'/'.$this->_offer_id
		);

		$host_resp = $this->platform->post(
			'ubersmith/order/get_hosting_pack',
			array(
				'order_id' => $order_id
			)
		);



		if ( ! $response['success'] || ! isset($response['data']['callback'])):

			return;

		endif;

		if ( ! $host_resp['success']):

			return;

		endif;


		$hosting_pack = $host_resp['data']['hosting_pack'];

		$callback     = $response['data']['callback'];		
		$price        = $hosting_pack['price'] + $hosting_pack['setup'];
		
		$find_replace = array(
			'[first_name]'           => $order['info']['first'],
			'[last_name]'            => $order['info']['last'],
			'[phone]'                => $order['info']['phone'],
			'[email]'                => $order['info']['email'],
			'[order_id]'             => $order_id,
			'[passback]'             => $this->input->cookie('passback'),
			'[total]'                => $price,
			'[commissionable_total]' => $price * .75
		);
		
		$callback = str_replace(array_keys($find_replace),$find_replace,trim($callback));

		$this->template->append_footermeta(
			'<div id="affiliate_callback_markup" style="visibility:hidden;height:1px;width:1px;">
				'.$callback.'
			</div>'
		);

	}

	function test_upsell ($viewname) {
		$data['domain'] = "test.com";
		$this->template->set_theme('allphase_full_funnel');
		$this->template->set_layout('upsell');
		$this->template->build($viewname, $data);

	}
}