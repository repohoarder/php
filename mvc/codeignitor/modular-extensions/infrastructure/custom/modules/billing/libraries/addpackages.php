<?php

class Addpackages {
	
	protected $CI;
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
		$_funnel_id		  = 0,
		$_id;
	
	public $errors = array();

	function __construct()
	{

		$this->CI = &get_instance();
		$this->_funnel_id = $this->CI->session->userdata('funnel_id');
		$this->_partner_id     = $this->CI->session->userdata('partner_id');
		$this->_partner_info   = $this->CI->session->userdata('partner_info');
		$this->_affiliate_id   = $this->CI->session->userdata('affiliate_id');
		$this->_offer_id       = $this->CI->session->userdata('offer_id');
		$this->_session_key    = $this->CI->session->userdata('session_id'); # change this to NOT be CI sess key
		$this->_id       = $this->CI->session->userdata('_id');
		// load libraries
		$this->CI->load->library('bonus/prices');
		$this->CI->load->library('bonus/orders');
	}
	
	/**
	 * This is the addon domain package post submit functionality
	 * @param  array  $post
	 * @return array
	 */
	
	public function init($serviceArr,$serviceMethod)
	{
			if( ! method_exists($this,$serviceMethod)) :
				return false;
			endif;
			
			if( empty($serviceArr) ) :
				return false;
			endif;
			
			// runn service addon array
			$post = $this->$serviceMethod($serviceArr);
			
			return $this->_add_additional_packages($post);
			
	}
	public function addon_domain($post=array(), $service='addon_domain')
	{
		// if action_id = 13, then user is attempting to add domain, we need to add that domain to meta
		
			// iterate through each plan
			if( ! empty($post) ) :
				
			foreach ($post AS $dom => $price):
				
				$parts = explode("_", $dom);
				$domain = $parts[0].".".$parts[1];
				
				// set plan to addon_domain & add meta & variant
				$post['plans'][]	= array(
					'service'       => $service,
					'variant'       => 'default',
					'name'          => $domain,
					'enable_cpanel' => 0,
					'server'        => $domain,
					'meta'          => array(
						'domain' => $domain,
					),
				);

			endforeach;	// end iterating through domains to add

		endif;

		return $post;
	}

	private function _add_additional_packages($post) {
			
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

					

				endif;
				
				// grab page variables for this service
				$variables	= 	$this->CI->platform->post('sales_funnel/page/get',array('slug' => $service));

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

				// get plan id
				$services['plan_id']	= $this->CI->orders->get_plan_id($services['plan_slug']); 

				// if unable to get plan id, then we can't add service
				if ( ! $services['plan_id'] OR ! is_numeric($services['plan_id']))
					continue;

				// get price
				$services['price']		= $this->_get_price($services['plan_id'], $services['term'], $services['variant']);

				// if no price was grabbed, then we can not add anything
				if ($services['price'] === FALSE)
					continue;				

				// create array to add pack to order/client
				$arr	= $this->_update_order($services);

				// add service to order/client
				if ($arr !== FALSE AND ! empty($arr)):

					// add/update the cart
					$cart 	= $this->CI->platform->post('crm/cart/add',$arr);

				endif;
				
			endforeach;	// End looping through services to add
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
		$pack_count	= $this->CI->orders->get_order_pack_count($this->_id); 
		
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

		// return formatted array
		return $add;
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
		$price		= $this->CI->prices->get($this->_funnel_id,$this->_partner_id,$this->_affiliate_id,$this->_offer_id,$plan_id,$variant,$term);

		// if we were unable to grab the price, then we can not add this service
		if ($price === FALSE OR ! is_float($price))	return FALSE;

		return number_format($price,2);
	}
}
