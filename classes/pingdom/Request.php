<?php

namespace Pingdom;

class Request extends Platform
{
	public function __construct()
	{
		// do something
	}

	public function Make($domain,$type='http')
	{
		// init variables
		$params 	= array(
			'host'	=> $domain,
			'type'	=> $type
		);

		// make request
		return $this->Post('single',$params);
	}

	public function Checks($domain)
	{
		$params 	= array();

		// make request
		return $this->Post('checks',$params,'GET');
	}
}