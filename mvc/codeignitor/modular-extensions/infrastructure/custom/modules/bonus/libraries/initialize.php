<?php 

/**
 * Bonus Initialize
 * 
 * This class runs initialization methods before each "bonus" (upsell) page is displayed.  These functions must ALWAYS return: array('page' => 'pagename', 'data' => array('data' => 'values'))
 * 
 * @method	array	platinum_package()	This method runs platinum package initialization functions
 */
class Initialize
{
	/**
	 * This variable holds the Codeignitor Object
	 * 
	 * @var object
	 */
	var $CI;
	
	function __construct()
	{
		// load codeignitor instance
		$this->CI = &get_instance();
	}
	
	/**
	 * Platinum Package
	 * 
	 * @param	string	$page	This is the page name of this upsell
	 * @param	array	$data	This variables holds data that needs loaded for this page
	 * 
	 * @return	array
	 */
	public function platinum_package($page,$data=array())
	{
		// initialize variables
		$init				= array(
			'page'	=> $page,
			'data'	=> $data
		);

		// grab included upsells from config
		//$platinum					= $this->CI->config->item($this->CI->version->get());
		$config						= $this->CI->config->item('default');
		
		// grab the platinum package included upsells 
		$init['data']['included']	= $config[$page]['included'];
		
		// return array
		return $init;
	}

	/**
	 * The functionality for the hosting offer
	 * @param  [type] $page [description]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function hosting_offer($page,$data=array())
	{
		// initialize variables
		$init				= array(
			'page'	=> $page,
			'data'	=> $data
		);

		// initialize domain variable
		$init['data']['domain']	= FALSE;

		// get core domain & core domain pack id
		$init 					= $this->_get_core_domain($init);

		// return array
		return $init;
	}
	
	/**
	 * SEO Package
	 * 
	 * @param	string	$page	This is the page name of this upsell
	 * @param	array	$data	This variables holds data that needs loaded for this page
	 * 
	 * @return	array
	 */
	public function seo_package($page,$data=array())
	{
		// initialize variables
		$init				= array(
			'page'	=> $page,
			'data'	=> $data
		);

		// initialize domain variable
		$init['data']['domain']	= FALSE;

		// get core domain & core domain pack id
		$init 					= $this->_get_core_domain($init);
		
		// return array
		return $init;
	}

	/**
	 * The functionality for the pre-load traffic package
	 * @param  [type] $page [description]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function traffic($page,$data=array())
	{
		// initialize variables
		$init				= array(
			'page'	=> $page,
			'data'	=> $data
		);

		// grab pricing for all 3 traffic packages
		$init['data']['traffic_10k']	= $this->CI->prices->get($init['data']['funnel_id'],$init['data']['partner_id'],$init['data']['affiliate_id'],$init['data']['offer_id'],$init['data']['plan_id'],'reg_10k',$init['data']['term']);
		$init['data']['traffic_5k']		= $this->CI->prices->get($init['data']['funnel_id'],$init['data']['partner_id'],$init['data']['affiliate_id'],$init['data']['offer_id'],$init['data']['plan_id'],'reg_5k',$init['data']['term']);
		$init['data']['traffic_1k']		= $this->CI->prices->get($init['data']['funnel_id'],$init['data']['partner_id'],$init['data']['affiliate_id'],$init['data']['offer_id'],$init['data']['plan_id'],'reg_1k',$init['data']['term']);

		// initialize domain variable
		$init['data']['domain']	= FALSE;

		// get core domain & core domain pack id
		$init 					= $this->_get_core_domain($init);

		// return array
		return $init;
	}

	
	
	public function email($page,$data=array()){
		
		// initialize variables
		$init				= array(
			'page'	=> $page,
			'data'	=> $data
		);
		
		// grab variant price points
		$init['data']['pro']['price']        = $this->CI->prices->get($init['data']['funnel_id'],$init['data']['partner_id'],$init['data']['affiliate_id'],$init['data']['offer_id'],$init['data']['plan_id'],'pro',$init['data']['term']);
		$init['data']['enterprise']['price'] = $this->CI->prices->get($init['data']['funnel_id'],$init['data']['partner_id'],$init['data']['affiliate_id'],$init['data']['offer_id'],$init['data']['plan_id'],'enterprise',$init['data']['term']);
		
		
		return $init;
	}
	
	/**
	 * The functionality for the pre-load double traffic package
	 * @param  [type] $page [description]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	
	public function double_hosting($page, $data = array(), $skip_action = '88')
	{

		$init = array(
			'page' => $page,
			'data' => $data
		);

		if ($data['funnel_type'] != 'order'):

			$init['skip_action'] = $skip_action;
			return $init;

		endif;

		$resp = $this->CI->platform->post(
			'ubersmith/order/get_hosting_pack', 
			array(
				'order_id' => $data['_id']
			)
		);

		if ( ! $resp['success']):

			$init['skip_action'] = $skip_action;
			return $init;

		endif;

		$hpack  = $resp['data']['hosting_pack'];
		$period = $hpack['period'];


		$post 	= array(
			'funnel_id'		=> $data['funnel_id'],
			'partner_id'	=> $data['partner_id'],
			'uber_plan_id'	=> $hpack['plan_id'],
			'variant'		=> 'default',
		);

		if ($data['affiliate_id']):
			$post['affiliate_id'] = $data['affiliate_id'];
		endif;

		if ($data['offer_id']):
			$post['offer_id'] = $data['offer_id'];
		endif;

		// get prices details
		$resp =  $this->CI->platform->post('partner/pricing/get',$post);

		if ( ! $resp['success']):

			$init['skip_action'] = $skip_action;
			return $init;
		
		endif;

		$all_terms = $resp['data'];
		$terms     = array();

		foreach ($all_terms as $term):

			$terms[$term['num_months']] = $term;

		endforeach;

		ksort($terms);


		$next  = NULL;
		$found = FALSE;

		foreach ($terms as $num_months => $term):

			if ($num_months == $period):

				$found = TRUE;
				continue;

			endif;

			if ($found):

				$next = $term;
				break;

			endif;

		endforeach;

		if (is_null($next) || ! $next):

			$init['skip_action'] = $skip_action;
			return $init;

		endif;

		$init['data']['current_period'] = $hpack;
		$init['data']['next_period']    = $next;
		
		$init['data']['current_mo']     = number_format($hpack['cost'] / $hpack['period'],2);
		$init['data']['next_mo']        = number_format($next['price'] / $next['num_months'],2);
		
		$init['data']['discount']       = number_format($next['price'] * .80, 2);
		$init['data']['discount_mo']    = number_format($init['data']['discount'] / $next['num_months'],2);

		return $init;

	}

	public function double_traffic($page,$data=array())
	{
		### DOUBLE TRAFFIC WILL NOT WORK IF FUNNEL TYPE IS CLIENT

		// initialize variables
		$init				= array(
			'page'	=> $page,
			'data'	=> $data
		);

		// initialize domain variable
		$init['data']['domain']		= FALSE;
		$init['data']['traffic']	= FALSE;	// this variable will hold the current traffic package details
		$init['data']['double']		= array();		

		// get core domain & core domain pack id
		$init 					= $this->_get_core_domain($init);

		// see if we were able to grab core domain pack
		if (isset($init['data']['domain_pack_id'])):

			// get previously added traffic package
			if ($init['data']['funnel_type'] == 'client'):

				// get traffic pack details from client
				$pack 	= $this->CI->platform->post('ubersmith/package');

				// make sure we were able to grab the traffic package
				if ($pack['success'] === TRUE AND ! empty($pack['data'])):

					// set traffic price
					$init['data']['double']['price']	= '67.00';
					// set traffic hits
					// set traffic name

				endif;


			elseif ($init['data']['funnel_type'] == 'order'):

				// load libraries
				$this->CI->load->library('orders');
				$this->CI->load->library('prices');

				// get traffic pack details from order
				$pack 	= $this->CI->orders->get_order_pack($init['data']['_id'],'traffic');

				// make sure we were able to grab the traffic package
				if ($pack AND ! empty($pack)):

					// initialize variables
					$name 		= (($pack['traffic_hits']*2) / 1000).'k';
					$variant 	= 'ups_'.$name;
					$hits 		= $pack['traffic_hits'] * 2;

					// set traffic price
					$init['data']['double']['price']        = $this->CI->prices->get($init['data']['funnel_id'],$init['data']['partner_id'],$init['data']['affiliate_id'],$init['data']['offer_id'],$init['data']['plan_id'],$variant,$init['data']['term']);
					
					// set traffic hits
					$init['data']['double']['traffic_hits'] = $hits;
					
					// set traffic name
					$init['data']['double']['name']         = $name;
					
					// set the old price user has already paid for traffic
					$init['data']['double']['old_price']    = $pack['price'];
					
					// set old hits count
					$init['data']['double']['old_hits']     = $pack['traffic_hits'];
					
					// set old name
					$init['data']['double']['old_name']     = $pack['name'];

				endif;

			endif;

		endif;

		// return array
		return $init;
	}
	
	/**
	 * Hosting Package
	 * 
	 * @param	string	$page	This is the page name of this upsell
	 * @param	array	$data	This variables holds data that needs loaded for this page
	 * 
	 * @return	array
	 */
	public function hosting_package($page,$data=array())
	{
		// initialize variables
		$init				= array(
			'page'	=> $page,
			'data'	=> $data
		);
		
		// return array
		return $init;
	}

	/**
	 * Addon Domains
	 * 
	 * @param	string	$page	This is the page name of this upsell
	 * @param	array	$data	This variables holds data that needs loaded for this page
	 * 
	 * @return	array
	 */
	public function addon_domain($page, $data = array(), $tlds = array(), $skip_action = 13)
	{
		// initialize variables
		$init				= array(
			'page'	=> $page,
			'data'	=> $data
		);

		// set TLDs available for lookup
		$available_tlds 	= array(
			'com',
			'net',
			'org',
			'info',
			'biz'
		);

		if (is_array($tlds) && count($tlds)):

			$available_tlds = $tlds;

		endif;


		// this function can be converted to use /registrars/domains/get_all_tlds/$sld platform api

		// initialize domain variables
		$init['data']['domain']			= FALSE;
		$init['data']['domain_sld']		= FALSE;
		$init['data']['domain_tld']		= FALSE;
		$init['data']['suggestions']	= array();
		$init['skip_action']            = FALSE;

		// get core domain & core domain pack id
		$init 					= $this->_get_core_domain($init);
		
		if(isset($init['data']['pack_id']) && $init['data']['funnel_type'] == 'client') :
			
			$init['data']['domain_pack_id'] = $init['data']['pack_id'];
		
		endif;
		
		// see if we were able to grab core domain pack
		if (isset($init['data']['domain_pack_id'])):

			// if there was a core domain found, then grab suggestions from available_tlds
			if ($init['data']['domain_sld']):

				// iterate available tld's
				foreach ($available_tlds AS $tld):

					// make sure we're not looking at the same TLD that user already purchased
					if ($tld != $init['data']['domain_tld']):

						// see if this SLD with this TLD is available
						$available 	= $this->CI->platform->post('registrars/domain/is_available/'.$init['data']['domain_sld'].'/'.$tld);
						
						// see if sld/tld is available
						if ($available['success'] AND $available['data']['availability']):

							// if this domain is available, then add it to suggestions array
							$init['data']['suggestions'][]	= $tld;

						endif;

					endif;

				endforeach;
			
			endif;	// end seeing if core domain SLD was found

		endif;

		if (empty($init['data']['suggestions'])):

			$init['skip_action'] = $skip_action;
			
		endif;
		
		// return array
		return $init;
	}

	function business_domain($page, $data = array())
	{

		$tlds        = array('co');
		$skip_action = 58;

		return $this->addon_domain($page, $data, $tlds, $skip_action);

	}


	function domain_insurance($page,$data=array())
	{
		
		// initialize variables
		$init = array(
			'page'	=> $page,
			'data'	=> $data
		);

		$init['data']['domain'] = FALSE;

		$init = $this->_get_core_domain($init);

		return $init;

	}
	
	/**
	 * Is Purchased
	 * 
	 * This method determines if this package has been purchased already for this user.
	 * 
	 * @param	int		$plan	The plan id that we are determining has been purchased
	 * @param	int		$id		The ID of the user to do the lookup for
	 * @param	string	$type	This is the type of id that we need to perform lookup for (eg: client id, order id)
	 * 
	 * @return	boolean
	 */
	private function _is_purchased($plan,$id,$type)
	{
		// check with platform to see if this plan has already been purchased for this user
		return TRUE;	
	}
	
	/**
	 * This method gets domain and domain pack id for core domain
	 * @param  array  $init [description]
	 * @return [type]       [description]
	 */
	private function _get_core_domain($init=array())
	{
		// get core domain
		$domain 	= $this->CI->platform->post('ubersmith/client/get_core_domain',array('type'	=> $init['data']['funnel_type'], 'id' => $init['data']['_id']));
		
		// if there was an error, then we were unable to grab the core domain
		if ($domain['success'] === TRUE AND ! empty($domain['data'])):
		
			// set core domain to data array
			$init['data']['domain']			= $domain['data']['domain'];

			// set SLD
			$init['data']['domain_sld']		= $domain['data']['domain_sld'];
		
			// set TLD
			$init['data']['domain_tld']		= $domain['data']['domain_tld'];

			// set domain pack id to data array
			$init['data']['domain_pack_id']	= $domain['data']['pack_id'];

		endif;

		// return entire init array
		return $init;
	}
	
}