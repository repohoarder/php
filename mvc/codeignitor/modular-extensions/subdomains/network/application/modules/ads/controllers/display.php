<?php


class Display extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * This method determines username and ad to show
	 * 
	 * @param  boolean $username The username of the user who is requesting this ad
	 * @param  string  $size     The size of the ad to be displayed
	 * @return view
	 */
	public function index($username=FALSE,$width="300",$height="250",$category=FALSE)
	{
		// initialize variables
		$size 		= $height.'x'.$width;

		// grab array of ads already shown to this customer & exclude them (if we can)
		$exclude 	= @json_decode($this->input->cookie('_ads_shown'),TRUE);

		// create post array to grab an ad to display
		$post 		= array(
			'height'	=> $height,
			'width'		=> $width,
			'username' 	=> $username,
			'category' 	=> $category,
			'exclude' 	=> $exclude
		);

		// grab an ad to display with this height and width for this user & category (only excludes ads if other ads are able to be shown)
		$ad 		= $this->platform->post('network/ad/display',$post);

		// make sure we were able to grab an ad
		if ( ! $ad['success'] OR empty($ad['data']))	continue;	// Do something here since we were unable to grab an ad to display

		// set ad array
		$ad 		= $ad['data'];

		// add this ad to the "excluded" array
		$exclude[]	= $ad['ads_sizes_id'];

		// set ad shown to a cookie (so we don't show it later)
		$cookie = array(
		    'name'   => '_ads_shown',
		    'value'  => json_encode($exclude),
		    'expire' => '5',
		    'domain' => '.'.$_SERVER['HTTP_HOST'],
		    'path'   => '/'
		);
		$this->input->set_cookie($cookie); 	// Set the cookie

		// get browser information
		$browser 	= $this->tracking->_get_browser();

		// track an impression
		$track 		= array(
			'publisher_id'		=> $ad['publisher_id'],
			'ads_sizes_id'		=> $ad['ads_sizes_id'],
			'height'			=> $height,
			'width'				=> $width,
			'browser'			=> $browser['name'],
			'browser_version'	=> $browser['version'],
			'operating_system'	=> $this->tracking->_get_OS(),
			'referrer'			=> @$_SERVER['HTTP_REFERER'],
			'longitude'			=> '',
			'latitude'			=> '',
			'ip'				=> $_SERVER['REMOTE_ADDR']
		);
		$this->platform->post('network/track/impression',$track);

		// set data array
		$data['username']	= $username;			// The username requesting the ad
		$data['pub_id']		= $ad['publisher_id'];	// This is the publisher ID of the pub requesting the ad
		$data['size']		= $size;				// The size of the ad to display
		$data['ad']			= $ad['name'];			// The ad name to show
		$data['id']			= $ad['ads_sizes_id'];	// The ad sizes id that we are displaying
		$data['img']		= $ad['image'];			// The ad image to show
		$data['height']		= $height;				// The height of the ad we are displaying
		$data['width']		= $width;				// The width of the ad we are displaying

		$this->load->view('display',$data);
	}
}