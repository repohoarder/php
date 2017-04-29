<?php 

class Initialize extends MX_Controller
{
	/**
	 * Funnel ID
	 * 
	 * @var _funnel_id
	 */
	var 
		$_language,
		$_funnel_id,
		$_partner_id,
		$_partner_info,
		$_affiliate_id,
		$_offer_id,
		$_default_page,
		$_visitor_id,
		$_posted      = array(),
		$_funnel_info = array(),
		$_partner_funnel_info = array(),
		$_pixels      = array(),
		$_build,
		$_build_version,
		$_pre_arpu_partner_id;

	
	public function __construct()
	{
		parent::__construct();
		
		// default language to english
		$this->_language 		= 'english';

		// set default first page (if we are unable to grab first page of passed funnel)
		$this->_default_page	= 'hosting/signup';
	}

	
	/**
	 * Index
	 * 
	 * This method determines and sets a funnel version
	 */
	public function index($partner_id=FALSE, $funnel_id=FALSE, $affiliate_id=FALSE, $offer_id=FALSE,$build=FALSE,$build_version=FALSE)
	{
		// ARPU on AP
		$partner_id 	= $this->_arpu($partner_id);

		// set build type information
		$this->_set_build_data($build,$build_version,$partner_id);

		# Country geoip blocking
		$ip_address = $this->session->userdata('ip_address');
		$resp       = $this->platform->post(
			'geoip/to_country',
			array(
				'ip_address' => $ip_address
			)
		);

		if ($resp['success']):

			if (isset($resp['data']['banned']) && $resp['data']['banned']):
				show_error('We\'re sorry, our products are not currently available in your region');
				return;
			endif;

		endif;
		# End country geoip blocking


		if ( ! $affiliate_id):

			$affiliate_info = $this->_get_affiliate_from_cookies();

			if (is_array($affiliate_info)):

				extract($affiliate_info);

			endif;

			unset($affiliate_info);

		endif;
		
		
		if ($partner_id === FALSE && $this->session->userdata('partner_id')):
			$partner_id = $this->session->userdata('partner_id');
		endif;


		// set global variables
		$this->_set_globals($partner_id,$funnel_id,$affiliate_id,$offer_id);

		// verify this is a valid partner
		if ( ! $this->_valid_partner()):

			if ($this->_partner_id == 1):

				show_error('Unable to retrieve partner info');
				return;

			endif;

			# instead of redirecting, preserve $_POST by calling directly
			return $this->index(1, $funnel_id, $this->_affiliate_id, $this->_offer_id);

		endif;


		// verify this is a valid funnel for this partner/affiliate
		if ( ! $this->_valid_funnel()):

			$default_funnel = $this->_get_default();

			if ($this->_funnel_id == $default_funnel):

				show_error('Unable to retrieve funnel info');
				return;

			endif;


			# instead of redirecting, preserve $_POST by calling directly
			return $this->index($this->_partner_id, $default_funnel, $this->_affiliate_id, $this->_offer_id);

		endif;

		$this->_set_pixels();

		$this->_set_funnel_info();
		
		$this->_set_partner_funnel_info();
		
		$this->_track_visitor();

		$this->_store_post();

		// set language
		$this->_set_default_language();

		// set sessions
		$this->_set_sessions();

		// redirect user to first page of this funnel
		redirect($this->_get_funnel_first_page());
		return;
	}

	private function _arpu($partner_id=FALSE)
	{
		// initialize variables
		$global_arpu 				= 30;
		$this->_pre_arpu_partner_id	= $partner_id;
		$algorithm					= array();
		$boolean					= FALSE;	// false means NOT to "track"

		// if no partner id, return false
		if ( ! $partner_id)		return FALSE;

		// if this partner is 1, then do nothing
		if ($partner_id == 1)	return $partner_id;

		// check partner arpu
		$arpu 			= $this->platform->post('partner/arpu/get',array('partner_id' => $partner_id));

		// if no partner arpu - use global arpu
		$percentage 	= (( ! isset($arpu['data']) OR empty($arpu['data']) OR ($arpu['data'] == NULL)) AND $arpu['data'] != 0)? $global_arpu: $arpu['data'];

		// create array with 100 values
		for ($i=0; $i < 100; $i++):
		
			// if $i < $percentage then boolean is true
			if ($i < $percentage) $boolean	= TRUE;
		
			// set algorithm array to boolean value
			$algorithm[]	= $boolean;
			
			// reset boolean to false
			$boolean	= FALSE;
			
		endfor;

		// shuffle the array
		shuffle($algorithm);

		// grab random array key
		$key	= array_rand($algorithm);

		// see if we are "tracking" this click (set to partner id == 1 if not)
		return ($algorithm[$key] === TRUE)? 1: $partner_id;
	}

	private function _set_build_data($build,$version,$partner_id=FALSE)
	{
		// set build and version to global variables
		$this->_build					= $build;
		$this->_build_version			= $version;
		$this->_build_version_id		= FALSE;			// default this to FALSE
		$exclude 						= array(
			'162'
		);

		// dont auto give wpbase2 for following partners
		if ( ! in_array($partner_id,$exclude)):

			// if no build or version, default to wpbase2
			if ( ! $this->_build)
				$this->_build 	= 'wpbase2';

			if ( ! $this->_build_version)
				$this->_build_version 	= '1.00';

		endif;

		// if build and version, then we need to get the build_version_id
		if ($this->_build AND $this->_build_version):

			$params = array(
				'slug'    => $this->_build,
				'version' => $this->_build_version
			);
			
			$resp = $this->platform->post(
				'builder/build/getbuildversionid',
				$params
			);
			
			$this->_build_version_id	= $resp['data'];

		endif;
		
		return;
	}

	private function _set_pixels()
	{

		$params = array(
			'partner_id'   => $this->_partner_id,
			'affiliate_id' => $this->_affiliate_id,
			'offer_id'     => $this->_offer_id
		);

		$resp = $this->platform->post(
			'partner/pixel/get',
			$params
		);

		if ( ! $resp['success']):

			return;

		endif;

		foreach ($resp['data']['pixels'] as $pixel):

			$this->_pixels[$pixel['type']][] = $pixel['pixel'];

		endforeach;

		return;

	}

	/**
	 * This method sets the default language session
	 */
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

	function _store_post()
	{

		$allowed = array(
			'core_num_years',
			'core_privacy',
			'core_domain',
			'first_name', 
			'last_name', 
			'core_type',
			'core_sld',
			'core_tld',
			'address', 			
			'hosting',
			'country',
			'zipcode',
			'state',
			'phone',
			'email',
			'city',			
			'cell',			
		);

		$post = $this->input->get_post(NULL, TRUE);
		
		if (empty($post)):

			return;

		endif;

		$template = array_combine(
			$allowed,
			array_fill(0, count($allowed), FALSE)
		);

		$this->_posted = array_filter(
			array_intersect_key(
				$post, 
				$template
			)
		);

		$this->_posted['external_post'] = 1;

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


	function _track_visitor()
	{

		$this->load->library('tracking');

		$params = array(
			'partner_id'       		=> $this->_partner_id,
			'funnel_id'        		=> $this->_funnel_id,
			'affiliate_id'     		=> $this->_affiliate_id,
			'offer_id'         		=> $this->_offer_id,
			'_pre_arpu_partner_id'	=> $this->_pre_arpu_partner_id
		);

		$visitor_id = $this->tracking->visitor($params);

		if ($visitor_id):

			$this->_visitor_id = $visitor_id;

		endif;

		return;
	}


	function _get_affiliate_from_cookies()
	{

		$cookies = $this->_get_cookies();

		if ( ! is_array($cookies) || empty($cookies)):

			return FALSE;

		endif;

		$response = $this->platform->post(
			'affiliate/get_info_from_cookies',
			array(
				'cookies' => $cookies
			)
		);

		if ( ! is_array($response) || ! isset($response['success']) || ! $response['success']):

			return FALSE;

		endif;
		
		return $response['data'];

	}

	function _get_cookies()
	{

		$cookies = $_COOKIE;
		unset($cookies['ci_session']);

		foreach ($cookies as $name=>$val):

			if (substr($name,0,1)=='_'):

				unset($cookies[$name]);
				continue;

			endif;

			$cookies[$name] = $this->input->cookie($name);

		endforeach;

		$cookies = array_map('trim', $cookies);

		return (is_array($cookies) ? $cookies : array());

	}


	
	/**
	 * Set Sessions
	 * 
	 * This method sets a version to a session
	 */
	private function _set_sessions()
	{

		$allowed_sessions = array(
			'session_id',
			'last_activity',
			'ip_address',
			'user_agent',
			'external_post',
			'visitor_id',
			'pixels',
			'_language',
			'build',
			'build_version',
		);

		$current_sessions = $this->session->all_userdata();

		if (isset($current_sessions['external_post'])):

			$allowed_sessions = array_merge($allowed_sessions,array(
				'core_num_years',
				'core_privacy',
				'core_domain',
				'first_name', 
				'last_name', 
				'core_type',
				'core_sld',
				'core_tld',
				'address', 			
				'hosting',
				'country',
				'zipcode',
				'state',
				'phone',
				'email',
				'city',			
				'cell',	
			));

		endif;

		$current_sessions = array_diff_key(array_filter($current_sessions), array_flip($allowed_sessions));

		foreach ($current_sessions as $key => $value):

			$this->session->unset_userdata($key);

		endforeach;



		foreach ($this->_posted as $key => $val):

			$this->session->set_userdata($key, $val);

		endforeach;

		// set build
		$this->session->set_userdata('_build', $this->_build);

		// set build version
		$this->session->set_userdata('_build_version', $this->_build_version);

		// set build version id
		$this->session->set_userdata('_build_version_id', $this->_build_version_id);		

		// set language session
		$this->session->set_userdata('_language', $this->_language);

		// set partner id session
		$this->session->set_userdata('partner_id', $this->_partner_id);
		
		// set funnel id session
		$this->session->set_userdata('funnel_id', $this->_funnel_id);

		// set funnel information session
		$this->session->set_userdata('funnel_info', $this->_funnel_info);
		
		// set partner funnel information session
		$this->session->set_userdata('partner_funnel_info', $this->_partner_funnel_info);

		// set pre arpu partner id session 
		$this->session->set_userdata('_pre_arpu_partner_id', $this->_pre_arpu_partner_id);

		// set affiliate id session
		if ($this->_affiliate_id AND is_numeric($this->_affiliate_id)) 
			$this->session->set_userdata('affiliate_id', $this->_affiliate_id);
			
		// set offer id session
		if ($this->_offer_id AND is_numeric($this->_offer_id)) 
			$this->session->set_userdata('offer_id', $this->_offer_id);
			
		if ($this->_visitor_id):

			$this->session->set_userdata('visitor_id', $this->_visitor_id);

		endif;

		if ($this->_partner_info):

			$this->session->set_userdata('partner_info', $this->_partner_info);

		endif;

		$this->session->set_userdata('pixels', $this->_pixels);

		return;
	}
	

	
	/**
	 * Get Default
	 * 
	 * This method gets the default version for this partner/affiliate
	 */
	private function _get_default()
	{

		$default_funnel = 1;

		$def		= array(
			'partner_id'	=> $this->_partner_id,
			'affiliate_id'	=> $this->_affiliate_id
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
	 * Set Globals
	 * 
	 * This method sets the global variables needed for this class
	 */
	private function _set_globals($partner_id=FALSE,$funnel_id=FALSE,$affiliate_id=FALSE,$offer_id=FALSE)
	{
		// if no affiliate id, default to 0 (false)
		$this->_affiliate_id	= $affiliate_id;
		
		// if no affiliate id, default to 0 (false)
		$this->_offer_id		= $offer_id;		
		
		// if not partner is set, default to 1 (Brain Host)
		$this->_partner_id		= ( ! $partner_id OR ! is_numeric($partner_id))? '1': $partner_id;

		if (FALSE && strstr($_SERVER['HTTP_HOST'],'hostingaccountsetup.com') !== FALSE && $this->_partner_id == 1):

			show_error('Unable to process your request. '."\n\n".'
				<script>
		    		document.write(\'<a href="\' + document.referrer + \'">Please go back and try again.</a>\');
		    	</script>
		    ');
			exit();

		endif;

		$this->_set_partner_info();

		// if no funnel_id was passed, then we need to grab the default
		$this->_funnel_id		= ( ! $funnel_id OR ! is_numeric($funnel_id))? $this->_get_default(): $funnel_id;


		## HACK
		if ($this->_partner_id == 239 AND $this->_funnel_id == 134):

			$this->_funnel_id 	= 106;

		endif;



		if ($this->session->userdata('visitor_id')):

			$this->_visitor_id = $this->session->userdata('visitor_id');

		endif;
		
		return;
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

		// if partner id == 1, then they have access to ALL funnels
		if ($this->_partner_id != 1):
		
			// gets the default version for this partner/affiliate
			$default	= $this->platform->post('sales_funnel/version/valid_funnel',$valid);

			// if not valid, return false
			if ( ! $default['success'] OR ! $default['data']) return FALSE;

		endif;	// end checking to see if partner id == 1
		
		// if we made it here, then we have a valid funnel
		return TRUE;
	}
	
	/**
	 * Get Funnel URI
	 * 
	 * This method grabs the funnel's first page URI (so that we can redirect user to proper page)
	 */
	private function _get_funnel_first_page()
	{
		// get funnel default first page
		$funnel		= $this->platform->post('sales_funnel/funnel/get',array('funnel_id' => $this->_funnel_id));
		
		// if we weren't able to grab default page information, return the default
		if ( ! $funnel['success'] OR ! is_numeric($funnel['data']['default_page_id']))
			return $this->_default_page;
		
		// set page id
		$page_id	= $funnel['data']['default_page_id'];
		
		// grab page information
		$page		= $this->platform->post('sales_funnel/page/get_by_id',array('id' => $page_id));
		
		// if we were unable to grab page information, return default page
		// or should we redirect back to this page with the Brain Host funnel ID since we know it's set?
		if ( ! $page['success'] OR ! $page['data']['uri'])
			return $this->_default_page;
			
		// return the page URI
		return $page['data']['uri'];
	}
	public function demo($partner_id=FALSE, $funnel_id=FALSE, $affiliate_id=FALSE, $offer_id=FALSE,$build=FALSE,$build_version=FALSE)
	{
		// set build type information
		$this->_set_build_data($build,$build_version);

		# Country geoip blocking
		$ip_address = $this->session->userdata('ip_address');
		$resp       = $this->platform->post(
			'geoip/to_country',
			array(
				'ip_address' => $ip_address
			)
		);

		if ($resp['success']):

			if (isset($resp['data']['banned']) && $resp['data']['banned']):
				show_error('We\'re sorry, our products are not currently available in your region');
				return;
			endif;

		endif;
		# End country geoip blocking


		if ( ! $affiliate_id):

			$affiliate_info = $this->_get_affiliate_from_cookies();

			if (is_array($affiliate_info)):

				extract($affiliate_info);

			endif;

			unset($affiliate_info);

		endif;
		
		
		// set global variables
		$this->_set_globals($partner_id,$funnel_id,$affiliate_id,$offer_id);

		// verify this is a valid partner
		if ( ! $this->_valid_partner()):

			if ($this->_partner_id == 1):

				show_error('Unable to retrieve partner info');
				return;

			endif;

			# instead of redirecting, preserve $_POST by calling directly
			return $this->index(208, $funnel_id, $this->_affiliate_id, $this->_offer_id);

		endif;


		// verify this is a valid funnel for this partner/affiliate
		if ( ! $this->_valid_funnel()):

			$default_funnel = $this->_get_default();

			if ($this->_funnel_id == $default_funnel):

				show_error('Unable to retrieve funnel info');
				return;

			endif;


			# instead of redirecting, preserve $_POST by calling directly
			return $this->index($this->_partner_id, $default_funnel, $this->_affiliate_id, $this->_offer_id);

		endif;

		$this->_set_pixels();
		
		$this->_set_funnel_info();
		
		$this->_set_partner_funnel_info();

		$this->_store_post();

		// set language
		$this->_set_default_language();

		// set sessions
		$this->_set_sessions();

		// redirect user to first page of this funnel
		redirect('/completed/sale/demo');
		
		return;
	}
}



