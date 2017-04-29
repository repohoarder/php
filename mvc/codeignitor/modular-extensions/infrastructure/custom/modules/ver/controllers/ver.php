<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sets the user's version and redirects to a given URL
 * Example: yoursite.com/ver/split_test/billing
 * 	will set "split_test" as the user's version
 * 	and redirect to yoursite.com/billing
 */
class Ver extends MX_Controller
{

	/**
	 * Sets the user's session based on URL segments and redirects
	 * @return NULL
	 */
	function index()
	{

		$this->load->library('version');

		// routers config redirects all /ver/... requests to ver/index/$1
		// get all URL segements after /ver/index/
		$args = @func_get_args();

		// assume the version is the first segment
		// remove version from the $args array for redirection later
		$version = array_shift($args);

		// set the version
		$this->version->set($version);

		// re-assemble the URL after removing version
		$url = implode('/',$args);

		// redirect to new URL
		redirect($url);

		return;

	}

}