<?php

class Reputations
{
	public function __construct()
	{
		$this->CI 	= &get_instance();
	}

	public function blacklisted($ip=FALSE)
	{
		// initialize variables
		$result 	= array();

		// error handling
		if ( ! $ip)
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_ip').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// load blacklist config
		$this->CI->load->config('blacklist');

		// load blacklist servers
		$server 		= $this->CI->config->item('servers');

		// reverse ip
		$reverse		= implode('.',array_reverse(explode(".",$ip)));

		// iterate blacklist servers to check
		foreach ($server AS $dnsbl):

			// check blacklist server
			if (checkdnsrr($reverse.'.'.$dnsbl.'.','A'))
				$result[]	= $dnsbl;		// add this dnsbl to the results (ip is blacklisted in this dnsbl)

		endforeach;

		return $result;
	}
}