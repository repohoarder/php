<?php


class Ads4dough extends MX_Controller {

	protected $_ads;

	function __construct()
	{

		$this->load->config('ads4dough/ads4dough');
		$this->_ads = $this->config->item('ads4dough_ads');
	}

	/**
	 * Ads4Dough Site Suspended Ads
	 * 
	 * This method shows a random Ads4Dough ad for our Site Suspended page(s)
	 * 
	 */
	public function index()
	{
		// create array of ad URL's
		$ads	= $this->config->item('site_suspended_urls');
		
		// redirect to a random URL
		header("Location: ".$ads[array_rand($ads)]);
	}
	

	function _shuffle_assoc($array)
	{
	   $keys = array_keys($array);
	   shuffle($keys);

	   return array_merge(array_flip($keys), $array);
	} 

	function get($size = '300x250')
	{

		if ( ! array_key_exists($size, $this->_ads)):

			return;

		endif;

		$viewed_ads = @json_decode($this->input->cookie('bulletins'), TRUE);

		if (is_array($viewed_ads) && array_key_exists($size, $viewed_ads) && count($viewed_ads[$size])):

			foreach ($viewed_ads[$size] as $ad):

				if (count($this->_ads[$size]) < 2):

					break;

				endif;  

				unset($this->_ads[$size][$ad]);

			endforeach;

		endif;

		$possible_ads = $this->_ads[$size];

		$possible_ads = $this->_shuffle_assoc($possible_ads);

		foreach ($possible_ads as $ad_key => $ad_data):

			$viewed_ads[$size][] = $ad_key;

			$cookie = array(
			    'name'   => 'bulletins',
			    'value'  => json_encode($viewed_ads),
			    'expire' => '5',
			    'domain' => '.'.$_SERVER['HTTP_HOST'],
			    'path'   => '/'
			);

			$this->input->set_cookie($cookie);  

			break;

		endforeach;

		$data['ad_key']     = $ad_key;
		$data['ad_size']    = $size;
		$data['dimensions'] = explode('x',$size);
		$data['ad_img']     = $ad_data['file'];
		$data['ad_link']    = $ad_data['link'];

		$this->load->view('ads4dough/ad4dough', $data);

	}


}