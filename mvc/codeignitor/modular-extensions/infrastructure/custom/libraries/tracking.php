<?php 

class Tracking
{
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	public function __construct()
	{
		// get codeignitor instance
		$this->CI =& get_instance();

		// load tracking config
		$this->CI->load->config('tracking');
	}


	public function visitor($params = array())
	{

		$browser  = $this->_get_browser();

		$defaults = array(
			'partner_id'       		=> 0,
			'funnel_id'        		=> 0,
			'affiliate_id'     		=> 0,
			'offer_id'         		=> 0,		
			'browser'          		=> $browser['name'],
			'browser_version'  		=> $browser['version'],
			'operating_system' 		=> $this->_get_OS(),
			'ip'               		=> $this->CI->session->userdata('ip_address'),	// sets the IP address
			'_pre_arpu_partner_id'	=> FALSE
		);

		$params = array_merge($defaults, array_intersect_key($params, $defaults));

		if ( ! $params['partner_id'] || ! $params['funnel_id']):

			return FALSE;

		endif;

		$resp = $this->CI->platform->post(
			'sales_funnel/tracking/visitor',
			$params
		);

		if ( ! $resp['success'] || ! isset($resp['data']['visitor_id'])):

			return FALSE;

		endif;

		return $resp['data']['visitor_id'];

	}

	public function page_hit($params = array())
	{

		$defaults = array(
			'visitor_id' => 0,
			'slug'       => '',
			'url'        => $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
		);

		$params = array_merge($defaults, array_intersect_key($params, $defaults));

		if ( ! $params['visitor_id'] || ! $params['slug']):

			return FALSE;

		endif;

		$resp = $this->CI->platform->post(
			'sales_funnel/tracking/page_hit',
			$params
		);

		if ( ! $resp['success']):

			return FALSE;

		endif;

		return $resp;

	}

	public function page_action($params = array())
	{

		$defaults = array(
			'visitor_id'        => 0,
			'action_id'         => 0,
			'conversion'        => 0,
			'conversion_amount' => 0			
		);

		$params = array_merge($defaults, array_intersect_key($params, $defaults));

		if ( ! $params['visitor_id'] || ! $params['action_id']):

			return FALSE;

		endif;

		$resp = $this->CI->platform->post(
			'sales_funnel/tracking/page_action',
			$params
		);

		if ( ! $resp['success']):

			return FALSE;

		endif;

		return $resp;

	}


	


	/**
	 * Get Browser
	 * 
	 * Get's the user's browser and version
	 * 
	 * @url http://www.kingofdevelopers.com/php-classes/get-browser-name-version.php
	 */
	public function _get_browser()
	{	
		// initialize variables
	    $bname 		= FALSE;
	    $ub			= FALSE;
	    $version 	= "0.0.0";
	    $browsers 	= $this->CI->config->item('browsers');

		// grab the user agent
	    $visitor_user_agent	= (isset($_SERVER["HTTP_USER_AGENT"]) OR ($_SERVER["HTTP_USER_AGENT"] != ""))
	        ? $_SERVER["HTTP_USER_AGENT"]
		    : "Unknown";

	    // iterate browsers and see if we find a match
	    foreach ($browsers AS $key => $value):
	    	$add 	= FALSE;	// boolean to say whether to add this user agent or not
	    	// see if we have a match
	    	if (preg_match('#'.$key.'#i', $visitor_user_agent)):
	    		
	    		// set add boolean to TRUE
	    		$add 	= TRUE;

	    		// special case for MSIE to make sure it's not really Opera
	    		if ($key == 'MSIE' AND preg_match('#Opera', $visitor_user_agent)) $add = FALSE;

	    		// if we are supposed to add, then set variables and break
	    		if ($add === TRUE):
			        $bname 	= $value['bname'];
			        $ub		= $value['ub'];
			        break;
	    		endif;

	    	endif;
	    endforeach;

	    // if we were unable to get bname and ub, then set to unknown
	    if ( ! $bname AND ! $ub):
	    	$bname 	= 'Unknown';
	    	$ub 	= 'Unknown';
	    endif;
	 
	    // finally get the correct version number
	    $known		= array('Version', $ub, 'other');

	    // create pattern for Version
	    $pattern	= '#(?<browser>'.join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

	    // see if we have any matches
	    preg_match_all($pattern, $visitor_user_agent, $matches);
	 
	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
	        //we will have two since we are not using 'other' argument yet
	        //see if version is before or after the name
	        if (strripos($visitor_user_agent, "Version") < strripos($visitor_user_agent, $ub)) {
	            $version = $matches['version'][0];
	        } else {
	            $version = $matches['version'][1];
	        }
	    } else {
	        $version = $matches['version'][0];
	    }
	 
	    // if we have no version, then set to ?
	    if ($version == null || $version == "") {
	        $version = "?";
	    }
	 
	    return array(
	        'userAgent' => $visitor_user_agent,		// user agent of user
	        'name' 		=> $bname,					// name of the browser
	        'version' 	=> $version,				// version of the browser
	        'pattern' 	=> $pattern					// pattern we used
	    );
	}
	
	/**
	 * Get Operating System
	 * 
	 * This method gets a user's operating system
	 * 
	 * @url http://www.danielkassner.com/2010/06/11/get-user-operating-system-with-php
	 */
	public function _get_OS()
	{
		$userAgent	= $_SERVER['HTTP_USER_AGENT'];
		
	  	// Create list of operating systems with operating system name as array key 
		$oses	= $this->CI->config->item('operating_systems');
	
		foreach($oses as $os=>$pattern){ // Loop through $oses array
	  		// Use regular expressions to check operating system type
			if(preg_match('#'.$pattern.'#i', $userAgent)) { // Check if a value in $oses array matches current user agent.
				return $os; // Operating system was matched so return $oses key
			}
		}
		return 'Unknown'; // Cannot find operating system so return Unknown
	}








	/**
	 * @deprecated
	 * Track Hit
	 * 
	 * This method tracks a hit
	 */
	public function hit($session_id,$partner_id=FALSE,$funnel_id=FALSE,$slug=FALSE,$affiliate_id=FALSE,$offer_id=FALSE)
	{
		// make sure funnel and page are set at the least
		if ($partner_id AND $funnel_id AND $slug):

			// get browser
			$browser 	= $this->_get_browser();
		
			// set track post array
			$track	= array(
				'session_id'		=> $session_id,
				'partner_id'		=> $partner_id,
				'funnel_id'			=> $funnel_id,
				'slug'				=> $slug,
				'url'				=> $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
				'browser'			=> $browser['name'],
				'browser_version'	=> $browser['version'],
				'operating_system'	=> $this->_get_OS(),
				'ip'				=> $this->CI->session->userdata('ip_address'),	// sets the IP address
				'affiliate_id'      => $affiliate_id,
				'offer_id'          => $offer_id,
			);
				
			// track a page hit
			$this->CI->platform->post('sales_funnel/tracking/hit',$track);
		
		endif;
		
		return;
	}
	
	/**
	 * @deprecated
	 * Track Action
	 * 
	 * This method tracks an action
	 */
	public function action($partner_id,$funnel_id,$action_id,$conversion=FALSE,$amount='0',$affiliate_id=FALSE,$offer_id=FALSE)
	{
		// make sure we have a funnel and action to track
		if ($partner_id AND $funnel_id AND $action_id):

			// create tracking post array
			$track	= array(
				'partner_id'		=> $partner_id,
				'funnel_id'			=> $funnel_id,
				'action_id'			=> $action_id,
				'conversion'		=> $conversion,
				'conversion_amount'	=> $amount,
				'affiliate_id'      => $affiliate_id,
				'offer_id'          => $offer_id,
			);
			
			// track that this action was taken
			$this->CI->platform->post('sales_funnel/tracking/action',$track);
		
		endif;
		
		return;
	}
}