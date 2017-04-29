<?php


class Version {


	protected $_ci;
	protected $_version_slug    = 'funnel_id';
	protected $_version_cookie  = 'funnel_id';
	protected $_version_session = 'funnel_id';
	protected $_default_version = '1';

	/**
	 * Constructer; grab the Codeigniter instance
	 */
	function __construct()
	{

		$this->_ci = &get_instance();

	}

	/**
	 * Return default version
	 * @return string Default version
	 */
	function get_default()
	{

		return $this->_default_version;
	}


	/**
	 * Pre-controller hook
	 * Looks for /version/{version_id} in the URL
	 * If version is detected, sets the current version in session & cookie,
	 * removes the version from the URL and redirects
	 * @deprecated We're now using a /ver/ module instead of the hook() function
	 *             because CodeIgniter doesn't have a hook that works the way we needed 
	 */
	function hook()
	{

		// removes the index.php from the url
    	$url = str_replace('/index.php','',$_SERVER["REQUEST_URI"]);

    	$version = $this->_default_version;

    	// trying to get version from the URL
	    $pieces = explode('/',$url);

	    $redirect = FALSE;

	    // expected URL structure: http://yoursite.com/version/{version}/{redirect URL}
	    if ((sizeof($pieces) > 2) && ($pieces[1] === $this->_version_slug)):

	    	// override default version with the one in the url
	    	$version = $pieces[2];

	    	// redirect to URL without /version/{$version}
	    	$redirect = TRUE;

	    endif;

    	$this->set($version);

    	// only redirect if /version/ was detected in the url string
    	if ( ! $redirect):

    		return;

    	endif;

    	// remove /version/{version} from the current URL and redirect
    	$redirect_url = str_replace($this->_version_slug.'/'.$version,'',$_SERVER['REQUEST_URI']);

    	redirect($redirect_url);

    	exit(); // oh god I hate doing this
	}


	/**
	 * Sets the version in session and in a 30-day cookie
	 * @param string $version The version ID to set
	 */
	function set($version)
	{
		
		// set the version session
		$this->_ci->session->set_userdata($this->_version_session,$version);

		// set the version cookie
		$this->_ci->input->set_cookie(
			array(
				'name'     => $this->_version_cookie,
				'value'    => $version,
				'expire'   => 2592000, // 30 days in seconds,
				//'domain' => $_SERVER["SERVER_NAME"]?
				'path'     => '/'
			)
		);

	}

	/**
	 * Return the current version from the session or cookie
	 * @return string Current version ID
	 */
	function get()
	{

		// grab the version from the session unless it's absent;
		// if it's absent, grab the cookie instead
		$version = (($this->_ci->session->userdata($this->_version_session)) 
			? $this->_ci->session->userdata($this->_version_session) 
			: $this->_ci->input->cookie($this->_version_cookie));

		if ( ! $version):

			// oh, no session or cookie? return the default version
			$version = $this->_default_version;

		endif;

		return $version;

	}

}