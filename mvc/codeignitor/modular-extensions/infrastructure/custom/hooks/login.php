<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login
{
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	public function __construct()
	{
		// get codeignitor instance
		$this->CI =& get_instance();
	}
	
	public function check()
	{
		if (!$this->CI)
		{
			return true;
		}
		
		// load login config
		$this->CI->load->config('login');
		
		// grab config item (subdomains)
		$subdomains	= $this->CI->config->item('subdomains');
		
		// check that the requested subdomain is in config of subdomains to be checked
		if (in_array($this->_subdomain(),$subdomains) && $this->CI->uri->uri_string()!='login/user/login'){
		
			if
			(
				$this->CI->session->userdata('uber_id')==$this->_check
					(
						$this->CI->session->userdata('uber_user'),
						$this->CI->session->userdata('uber_pass')
					)
				&& $this->CI->session->userdata('uber_id')
			)
			{
				return true;
			}
			
			$query = $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '';  
			$this->CI->session->set_userdata
			(
				array
				(
					'login_redirect'=>$this->CI->config->site_url()
						.$this->CI->uri->uri_string().$query
				)
			);
			redirect('login/user/login');
			die();
		}
	}
	
	private function _check($user, $pass)
	{
		$params=array
		(
			'user'=>$user,
			'pass'=>$pass
		);
		$response = $this->CI->platform->post('ubersmith/client/login', $params);
		
		if ($response['success'])
		{
			return $response['data']['id'];
		}
		
		return false;
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
}