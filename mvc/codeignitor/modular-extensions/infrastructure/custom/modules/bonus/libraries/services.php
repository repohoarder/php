<?php 

class Services
{
	/**
	 * Codeignitor Object
	 * 
	 * @var	object
	 */
	var $CI;
	
	function __construct()
	{
		$this->CI	= &get_instance();
	}
	
	/**
	 * The platinum package special offer
	 * @param  array  $post
	 * @return array
	 */
	public function platinum_package($post=array())
	{		
		// if alacarte items were selected, then we need to add these to the plans array
		if (isset($post['platinum_alacarte']) AND ! empty($post['platinum_alacarte'])):
		
			// iterate through alacarte items and add to array
			foreach ($post['platinum_alacarte'] AS $key => $value):
				$post['plans'][]	= $value;
			endforeach;
		
		endif;
		
		// if platinum package was selected, make this the only plan to add to order/client
		if (in_array('platinum_package',$post['plans']))	$post['plans']	= array('platinum_package');
		
		// if we are adding a package, then we need to add it as child of hosting
		if (is_array($post['plans'])):

			// see if hosting has a pack count
			$pack_count 	= $this->CI->orders->get_hosting_pack_count($post);

			// iterate through plans we are adding so that we can add a parent id
			foreach ($post['plans'] AS $key => $value):

				// add hosting package as parent of this package if we grabbed a pack count
				if ($pack_count !== FALSE AND is_numeric($pack_count))
					$post[$value]['parent_id']		= 'pack'.$pack_count;

			endforeach;	// end iterating through each pack we are adding

		endif;

		return $post;
	}
	/**
	 * add plans for business security package.
	 * @param type $post
	 * @return type
	 */
	public function business_security_package($post) {
		
		
		
		// if the action is for the package add the package
		if($post['action_id'] == 72) :
			$post['plans'][] = 'business_security_package';
		
		else: // else add the additional plans
			
			// if alacarte items were selected, then we need to add these to the plans array
			if (isset($post['security_alacarte'])):

				if(!empty($post['security_alacarte'])) :
					// iterate through alacarte items and add to array
					foreach ($post['security_alacarte'] AS $key => $value):
						$post['plans'][]	= $value;
					endforeach;
				endif;

			endif;
			
		endif;
		
		return $post;
	}
	
	/**
	 * se package submit
	 * @param type $post
	 * @return string
	 */
	public function se_package($post=array()){
		
		
		
		// if the action is for the package add the package
		if($post['action_id'] == 64) :
			$post['plans'][] = 'se_package';
		
		else: // else add the additional plans
			
			// if alacarte items were selected, then we need to add these to the plans array
			if (isset($post['se_package_alacarte'])):

				if(!empty($post['se_package_alacarte'])) :
					// iterate through alacarte items and add to array
					foreach ($post['se_package_alacarte'] AS $key => $value):
						$post['plans'][]	= $value;
					endforeach;
				endif;

			endif;
			
		endif;
		
		return $post;
	}
	
	/**
	 * Add email package to cart
	 * @param type $post
	 * @return string
	 */
	public function email($post=array()){
		$post['plans'][] = 'email';
		$post['email']['variant'] = $post['variant'];
		return $post;
	}
	/**
	 * add package for search engine submission
	 * @param type $post
	 * @return type
	 */
	public function search_engine_submission($post=array()){
		
		// if alacarte items were selected, then we need to add these to the plans array
			if (isset($post['packages'])):

				if(!empty($post['packages'])) :
					// iterate through alacarte items and add to array
					foreach ($post['packages'] AS $key => $value):
						$post['plans'][]	= $value;
					endforeach;
				endif;

			endif;		
		
		return $post;
	}
		/**
	 * add package for google local listing
	 * @param type $post
	 * @return type
	 */
	public function google_local_listing($post=array()){
		
		// if alacarte items were selected, then we need to add these to the plans array
			if (isset($post['packages'])):

				if(!empty($post['packages'])) :
					// iterate through alacarte items and add to array
					foreach ($post['packages'] AS $key => $value):
						$post['plans'][]	= $value;
					endforeach;
				endif;

			endif;		
		
		return $post;
	}
	/**
	 * The platinum package discount page
	 * @param  array  $post
	 * @return array
	 */
	public function platinum_package_discount($post=array())
	{
		// see if hosting has a pack count
		$pack_count 	= $this->CI->orders->get_hosting_pack_count($post);

		// add hosting package as parent of this package if we grabbed a pack count
		if ($pack_count !== FALSE AND is_numeric($pack_count))
			$post['platinum_package_discount']['parent_id']	= 'pack'.$pack_count;

		return $post;
	}

	/**
	 * The SEO Package (Improve SEO) page
	 * @param  array  $post [description]
	 * @return [type]       [description]
	 */
	public function seo_package($post=array())
	{
		// set parent id (of domain pack)
		$post['seo_package']['parent_id']				= $post['domain_pack_id'];

		return $post;
	}
	
	public function traffic($post=array())
	{
		// add client core domain to package meta
		//$post['traffic']['meta']['install_server_name']	= $post['domain'];
		$post['traffic']['meta']['domain']				= $post['domain'];

		// add # hits to package meta
		$post['traffic']['meta']['traffic_hits']		= $post['hits'];

		// set dynamic name
		$post['traffic']['meta']['name']				= $post['name'];

		// set variant depending on # hits passed
		$post['traffic']['variant']						= 'reg_'.$post['name'];

		// set parent id (of domain pack)
		$post['traffic']['parent_id']					= $post['domain_pack_id'];

		return $post;
	}

	public function double_hosting($post = array())
	{

		if ($post['action_id'] != 84):
			return $post;
		endif;

		$resp = $this->CI->platform->post(
			'ubersmith/order/get_hosting_pack', 
			array(
				'order_id' => $post['_id']
			)
		);

		if ( ! $resp['success']):
			return $post;
		endif;


		$hpack  = $resp['data']['hosting_pack'];
		$period = $hpack['period'];


		$params = array(
			'funnel_id'		=> $post['funnel_id'],
			'partner_id'	=> $post['partner_id'],
			'uber_plan_id'	=> $hpack['plan_id'],
			'variant'		=> 'default',
		);

		if ($post['affiliate_id']):
			$params['affiliate_id'] = $post['affiliate_id'];
		endif;

		if ($post['offer_id']):
			$params['offer_id'] = $post['offer_id'];
		endif;

		// get prices details
		$resp =  $this->CI->platform->post('partner/pricing/get',$params);

		if ( ! $resp['success']):
			return $post;
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
			return $post;
		endif;

		$discount_price = number_format($next['price'] * .80, 2);

		$pack = $hpack;
		unset($pack['cost']);
		unset($pack['prorated_total']);
		unset($pack['total_setup']);
		unset($pack['pack_num']);

		$params = array(
			'info' => array(
				'pack'.$hpack['pack_num'] => array_merge(
					$pack,
					array(
						'price'  => $discount_price,
						'setup'  => $next['setup_fee'],
						'period' => $next['num_months']
					)
				)
			)
		);

		$resp = $this->CI->platform->post(
			'ubersmith/order/update/'.$post['_id'],
			$params
		);

		if ( ! $resp['success']):

			return $post;

		endif;


		// kill previous hosting credits since user upgraded...
		$sess = $this->CI->session->all_userdata();
		if (isset($sess['hosting_credit'])):
			
			$resp = $this->CI->platform->post(
				'ubersmith/order/clear_credit',
				array(
					'order_id'  => $post['_id'],
					'credit_id' => $sess['hosting_credit']
				)
			);

			$this->CI->session->unset_userdata('hosting_credit');

		endif;


		$conversion = ($discount_price + $next['setup_fee']) - ($hpack['price'] + $hpack['setup']);
		$post['custom_conversion_amount'] = $conversion;

		return $post;
	}

	public function double_traffic($post=array())
	{
		// initialize meta array
		$post['double_traffic']									= array('meta' => array());

		// add client core domain to package meta
		$post['double_traffic']['meta']['domain']				= $post['domain'];

		// add # hits to package meta
		$post['double_traffic']['meta']['traffic_hits']			= $post['hits'];

		// set dynamic name
		$post['double_traffic']['meta']['name']					= $post['name'];

		// set that the traffic package was doubled
		$post['double_traffic']['meta']['traffic_doubled']		= 1;		

		// set variant depending on # hits passed
		$post['double_traffic']['variant']						= 'ups_'.$post['name'];

		// set parent id (of domain pack)
		$post['double_traffic']['parent_id']					= $post['domain_pack_id'];

		// determine if funnel type is order or client
		if ($post['funnel_type'] == 'client'):

			// set variables to add this as new pack
			
			// grab traffic package pack id
			
			// remove existing traffic pack from client
			

		elseif ($post['funnel_type'] == 'order'):

			// load prices library
			$this->CI->load->library('orders');

			// get traffic pack count from order info array
			$pack 	= $this->CI->orders->get_order_pack($post['_id'],'traffic');

			// set pack_count variable (this will overwrite existing traffic pack)
			$post['double_traffic']['pack_count']	= $pack['pack_count'];

		endif;	// end seeing if funnel type is order/client

		return $post;
	}

	/**
	 * The hosting for life special offer
	 * @param  array  $post [description]
	 * @return [type]       [description]
	 */
	public function hosting_for_life($post=array())
	{
		// see if hosting has a pack count
		$pack_count 	= $this->CI->orders->get_hosting_pack_count($post);

		// add hosting package as parent of this package if we grabbed a pack count
		if ($pack_count !== FALSE AND is_numeric($pack_count))
			$post['hosting_for_life']['parent_id']	= 'pack'.$pack_count;
		 
		return $post;
	}

	/**
	 * This method determines which social media packages the user is attempting to purchase and adjusts the plans[] accordingly
	 * @param  array  $post [description]
	 * @return [type]       [description]
	 */
	public function social_media($post=array())
	{
		// if the action id == 44, then user is trying to add items individually
		if ($post['action_id'] == 44):

			// iterate through plans, and unset the social media plan
			foreach ($post['plans'] AS $key => $value):
				if ($value == 'social_media')	unset($post['plans'][$key]);
			endforeach;

		endif;

		// if the action id == 43, then the user is trying to add the social media package
		if ($post['action_id'] == 43)	$post['plans']	= array('social_media');

		// if the action id == 42, then the user isn't buying upsells
		if ($post['action_id'] == 42)	unset($post['plans']);

		// set parent id (of domain pack)
		$post['social_media']['parent_id']				= $post['domain_pack_id'];

		return $post;
	}

	/**
	 * This is the addon domain package post submit functionality
	 * @param  array  $post
	 * @return array
	 */
	public function addon_domain($post=array(), $action_id = 13, $service='addon_domain')
	{
		// if action_id = 13, then user is attempting to add domain, we need to add that domain to meta
		if ($post['action_id'] == $action_id):
		
			// iterate through each plan
			foreach ($post['plans'] AS $key => $domain):

				// set plan to addon_domain & add meta & variant
				$post['plans'][$key]	= array(
					'service'       => $service,
					'variant'       => 'default',
					'name'          => $domain,
					'enable_cpanel' => 1,
					'server'        => $domain,
					'meta'          => array(
						'domain' => $domain,
					),
				);

			endforeach;	// end iterating through domains to add

		endif;	// end seeing is user is attempting to add domains

		return $post;
	}

	public function business_domain($post = array())
	{

		return $this->addon_domain($post, $action_id = 58,'business_domain');

	}

	public function domain_insurance($post = array())
	{

		$post['domain_insurance']['parent_id'] = $post['domain_pack_id'];

		return $post;

	}

	public function hosting_offer($post=array())
	{
		$post['plans']			= array('hosting_offer');
		$post['hosting_offer']	= array();

		return $post;
	}
}