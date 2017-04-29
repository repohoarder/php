<?php

class Track extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($publisher_id=FALSE,$ad_sizes_id=FALSE)
	{
		// initialize variables
		$redirect 	= FALSE;

		// make sure we have valid publisher and ad id's (and ehgith and width)
		if ( ! is_numeric($publisher_id) OR ! is_numeric($ad_sizes_id)):

			// we were unable to get a valid pub or ad id (or height and width) - default them
			$publisher_id	= 1;
			$ad_sizes_id 	= 1;

		endif;

		// create click tracking array
		$track 	= array(
			'ads_sizes_id'	=> $ad_sizes_id,
			'publisher_id'	=> $publisher_id,
			'referrer'		=> @$_SERVER['HTTP_REFERER'],
			'ip'			=> $_SERVER['REMOTE_ADDR']
		);
		
		// track click
		$click 	= $this->platform->post('network/track/click',$track);

		// grab details for this ad (mainly URL to redirect to)
		$ad 	= $this->platform->post('network/ad/get',array('ads_sizes_id' => $ad_sizes_id));
		
		// if we were unable to grab URL to redirect to - default it
		$redirect = ( ! $ad['success'] OR ! $ad['data'])
			? 'http://www.google.com'	// This is the default URL to send the user to if unable to grab ad URL
			: $ad['data']['url'];

		// redirect user
		header("Location: ".$redirect);
	}
}