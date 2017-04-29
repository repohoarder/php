<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Special
 * 
 * This class handles the functionality for MCSD Special Offer Page
 * 
 * 
 */
class Ads4dough extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// create array of ad URL's
		$ads	= array(
			'http://a4dtrk.com/?a=466047&c=146&s1=',
			'http://a4dtrk.com/?a=466047&c=1104&s1=',
			'http://a4dtrk.com/?a=466047&c=9883&s1=',
			'http://a4dtrk.com/?a=466047&c=4659&s1=',
			'http://a4dtrk.com/?a=466047&c=1928&s1=',
			'http://a4dtrk.com/?a=466047&c=1928&s1=',
			'http://a4dtrk.com/?a=466047&c=11420&s1=',
			'http://a4dtrk.com/?a=466047&c=506&s1='
		);
		
		// show a random URL
		echo file_get_contents($ads[array_rand($ads)]);
	}
	
	
	
	
	
	
	
	
	
	
	
	public function banner($size='300x250',$banner_id=false)
	{
		// load the cookie helper
		$this->load->helper('cookie');
		
		// load banners config
		$this->load->config('banners');
		
		// grab array of banners for this size
		$banners	= $this->config->item($size);
		
		// grab random banner not in cookie
		$rand		= $this->_grab_random_banners($banners);
		
		// grab size
		$sizes	= explode("x",$size);
		
		// set data variables
		$data['banner']	= $banners[$rand];
		$data['height']	= $sizes[0];
		$data['width']	= $sizes[1];
		$data['size']	= $size;
		
		// display banner
		$this->load->view('banners',$data);
	}
	
	private function _grab_random_banners($banners)
	{
		// set cookie value
		$cookie		= (array)json_decode($this->input->cookie('banners'),TRUE);
		
		// iterate through all cookie values and unset our banners array of those cookies
		foreach($cookie AS $banner_id):
		
			// don't unset if this is the last banner in the array
			if (count($banners) <= 1) break;
			
			// unset the banner
			unset($banners[$banner_id]);
			
		endforeach;
		
		// grab random id
		$rand	= array_rand($banners);
		
		// add new rand id to cookie
		$cookie[]	= $rand;
		
		// create new cookie array
		$cook		= array(
			'name'		=> 'banners',
			'value'		=> json_encode($cookie),
			'expire'	=> 1,
			'domain'	=> '.'.$_SERVER['HTTP_HOST'],
			'path'		=> '/'
		);
		
		// set cookie to show we saw this banner
		$this->input->set_cookie($cook);
		
		// return a random banner id
		return $rand;
	}
}