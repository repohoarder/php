<?php

class Partners
{
	/**
	 * Codeignitor Object
	 * @var [type]
	 */
	var $CI;

	public function __construct()
	{
		$this->CI 	= &get_instance();
	}

	/**
	 * This method grabs the total # of partner's activated
	 * @return [type] [description]
	 */
	public function activated()
	{
		// grab count of activated partners
		$activated 	= $this->CI->platform->post('partner/account/activated');

		// if unable to grab count, set to 0
		if ( ! $activated['success'] OR ! is_numeric($activated['data']))
			$activated['data']	= 0;

		return $activated['data'];
	}

	/**
	 * This method grabs the total # of partner's registered
	 * @return [type] [description]
	 */
	public function registered()
	{
		// grab count of registered partners
		$registered 	= $this->CI->platform->post('partner/account/registered');

		// if unable to grab count, set to 0
		if ( ! $registered['success'] OR ! is_numeric($registered['data']))
			$registered['data']	= 0;

		return $registered['data'];
	}
}