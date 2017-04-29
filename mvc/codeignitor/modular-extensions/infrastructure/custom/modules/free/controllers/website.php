<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Special
 * 
 * This class handles the functionality for MCSD Special Offer Page
 * 
 * 
 */
class Website extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		// load MCSD config
		$this->load->config('sitebuilder');
	}

	public function index()
	{
		// show the free website offer page
		$this->offer();
	}


	/**
	 * The Brain Host MCSD 2.0 Page
	 * @param  boolean $affiliate_id [description]
	 * @param  boolean $offer_id     [description]
	 * @param  integer $banner_id    [description]
	 * @param  integer $subid        [description]
	 * @param  boolean $passback     [description]
	 * @return [type]                [description]
	 */
	public function offer($affiliate_id=false,$offer_id=false,$banner_id=0,$subid=0,$passback=false)
	{
		// initialize variables
		$data		= array();
		$redirect	= '';
		
		// redirect banned affiliates & affiliates without access to this offer
		$this->_redirect_banned_affiliates($affiliate_id,$offer_id);
		
		// create affiliate link
		//$redirect	= 'http://affiliate.brainhost.com/tracking/index/'.md5($affiliate_id).'/'.md5($offer_id).'/'.$banner_id.'/'.$subid.'/'.$passback;	
		$redirect	= 'http://affiliate.brainhost.com/tracking/index/'.md5($affiliate_id).'/2120606d6a751a93e392b46d945bba9d/'.$banner_id.'/'.$subid.'/'.$passback;


		// set the layout to use
		$this->template->set_layout('mcsd');
		
		// set the page title
		if ($affiliate_id=='102019' && $offer_id=='3662')
		{
			$this->template->title('Free Blog Offer');
		}
		else
		{
			$this->template->title($this->lang->line('brand_free_website_title'));
		}
		
		// set needed data variables
		$data['header']			= $this->_get_mcsd_header($affiliate_id,$offer_id);		// The header to show
		$data['video']			= $this->_get_mcsd_video($affiliate_id,$offer_id);		// The video to show
		$data['image'] 			= $this->_get_mcsd_logo($affiliate_id, $offer_id);		// The header to use (custom or not)
		$data['item']			= $this->_get_mcsd_item($affiliate_id, $offer_id);		// Gets if it's a blog or a website
		$data['pixel']			= $this->_get_mcsd_pixel($affiliate_id,$offer_id);		// This is a custom pixel to add to the page
		$data['brand'] 			= $this->lang->line('brand_company');					// The company brand
		$data['redirect']	 	= $redirect;											// The affiliate redirect URL
		$data['layout_style']	= '/resources/modules/free/assets/css/style.css'; 		// The custom stylesheet to use
		$data['affiliate_id']	= $affiliate_id;
		$data['offer_id']		= $offer_id;
		
		// display view/content
		$this->template->build('offer', $data);
	}


	/**
	 * The Brain Host MCSD 2.0 Page
	 * @param  boolean $affiliate_id [description]
	 * @param  boolean $offer_id     [description]
	 * @param  integer $banner_id    [description]
	 * @param  integer $subid        [description]
	 * @param  boolean $passback     [description]
	 * @return [type]                [description]
	 */
	public function offering($affiliate_id=false,$offer_id=false,$banner_id=0,$subid=0,$passback=false)
	{
		// initialize variables
		$data		= array();
		$redirect	= '';
		
		// redirect banned affiliates & affiliates without access to this offer
		$this->_redirect_banned_affiliates($affiliate_id,$offer_id);
		
		// create affiliate link
		$redirect	= 'http://affiliate.brainhost.com/tracking/index/'.md5($affiliate_id).'/'.md5($offer_id).'/'.$banner_id.'/'.$subid.'/'.$passback;	
		
		// set the layout to use
		$this->template->set_layout('mcsd');
		
		// set the page title
		if ($affiliate_id=='102019' && $offer_id=='3662')
		{
			$this->template->title('Free Blog Offer');
		}
		else
		{
			$this->template->title($this->lang->line('brand_free_website_title'));
		}

		$default_video = 'http://8beeda049e5d49c340dd-9e0fc90ca393afce126174d62bc6e1d5.r61.cf1.rackcdn.com/Brain Host Type Video 3-28-13.flv';
		
		// set needed data variables
		$data['header']			= $this->_get_mcsd_header($affiliate_id,$offer_id);		// The header to show
		$data['video']			= $this->_get_mcsd_video($affiliate_id, $offer_id, $default_video);		// The video to show
		$data['image'] 			= $this->_get_mcsd_logo($affiliate_id, $offer_id);		// The header to use (custom or not)
		$data['item']			= $this->_get_mcsd_item($affiliate_id, $offer_id);		// Gets if it's a blog or a website
		$data['pixel']			= $this->_get_mcsd_pixel($affiliate_id,$offer_id);		// This is a custom pixel to add to the page
		$data['brand'] 			= $this->lang->line('brand_company');					// The company brand
		$data['redirect']	 	= $redirect;											// The affiliate redirect URL
		$data['layout_style']	= '/resources/modules/free/assets/css/style.css'; 		// The custom stylesheet to use
		$data['affiliate_id']	= $affiliate_id;
		$data['offer_id']		= $offer_id;
		
		// display view/content
		$this->template->build('offer', $data);
	}




	
	public function setup($order_id=false)
	{
		// determine if user is submitting the form
		if ($this->input->post()) return $this->_setup_submit();
		
		// set the layout to use
		$this->template->set_layout('bare');
		
		// set the page title
		$this->template->title($this->lang->line('brand_free_website_title'));

		// append the CSS file
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/free/assets/css/style_setup.css">');
		
		// set needed data variables
		$data['categories']	= $this->_sitebuilder_categories();	// Categories dropdown array
		$data['order_id']	= hexdec($order_id);				// Decrypt the order_id and pass to view
		
		// display view/content
		$this->template->build('setup', $data);
	}
	
	public function success() 
	{	
		// initialize variables
		$affiliate_id 	= $this->session->userdata('affiliate_id');

		// set the layout to use
		$this->template->set_layout('bare');
		
		// set data variables
		$data['affiliate_id']	= $affiliate_id;

		// display view/content
		$this->template->build('success',$data);
	}
	
	public function progress()
	{
		
		// append the CSS file
		$this->template->append_metadata('<link type="text/css" href="/resources/modules/free/assets/css/ui-lightness/jquery-ui-1.8.23.custom.css" rel="stylesheet" />');
		// append the JS file
		$this->template->append_metadata('<script type="text/javascript" src="/resources/modules/free/assets/js/libs/jquery-ui-1.8.23.custom.min.js"></script>');
		
		
		//$domain = $_GET['domain'];
		// $domain = "www.thenewguy.info";
		// $fileContent = file_get_contents('http://sitebuilder.brainhost.com/api/site_completion_status.php?domain=' . $domain);
		// echo $fileContent;
		
		// set the layout to use
		$this->template->set_layout('bare');
		
		// display view/content
		$this->template->build('progress');		
	}
	
	public function invalid()
	{
		// set the layout to use
		$this->template->set_layout('bare');
		
		// display view/content
		$this->template->build('invalid');	
	}
	
	
	/**
	 * Get MCSD Header
	 * 
	 * This method determines which MCSD video to use and returns it
	 */
	private function _get_mcsd_header($affiliate_id, $offer_id)
	{
		// initialize variables
		$header		= 'A Special Offer Just For You!';				// The default video file
		$header_file= 'header.txt';									// The filename of the custom header
		$root_dir	= CUSTOM_PATH.'modules/free/views/affiliates/';	// The root directory path
		$http_dir	= '/resources/modules/free/views/affiliates/';	// The resource path for HTTP Requests
		
		// determine if there is a custom video in /views/affiliates/affiliate_id/offer_id
		if (file_exists($root_dir.$affiliate_id.'/'.$offer_id.'/'.$header_file))
		{
			$header	= file_get_contents($root_dir.$affiliate_id.'/'.$offer_id.'/'.$header_file);
		}
		// determine if there is a custom video in /views/affiliates/affiliate_id/
		elseif (file_exists($root_dir.$affiliate_id.'/'.$header_file))
		{
			$header	= file_get_contents($root_dir.$affiliate_id.'/'.$header_file);
		}
		
		return $header;
	}
	
	/**
	 * Get MCSD Video
	 * 
	 * This method determines which MCSD video to use and returns it
	 */
	private function _get_mcsd_video($affiliate_id, $offer_id, $default_vid = NULL)
	{
		// initialize variables
		//$video		= 'http://setup.brainhost.com/resources/modules/free/assets/vid/offer.flv';	// The default video file

		$video		= 'http://3ad6d0cb6f5c71565427-0ceefa5c30f5e4f21bf782807a37e6a1.r85.cf1.rackcdn.com/The_Brain_Host_2.0_New.mp4';	// The default video file

		if ( ! is_null($default_vid)):

			$video = $default_vid;

		endif;

		$video_file	= 'video.flv';																// The filename of the custom video
		$root_dir	= CUSTOM_PATH.'modules/free/views/affiliates/';								// The root directory path
		$http_dir	= '/resources/modules/free/views/affiliates/';								// The resource path for HTTP Requests
		
		// determine if there is a custom video in /views/affiliates/affiliate_id/offer_id
		if (file_exists($root_dir.$affiliate_id.'/'.$offer_id.'/'.$video_file))
		{
			$video	= $http_dir.$affiliate_id.'/'.$offer_id.'/'.$video_file;
		}
		// determine if there is a custom video in /views/affiliates/affiliate_id/
		elseif (file_exists($root_dir.$affiliate_id.'/'.$video_file))
		{
			$video	= $http_dir.$affiliate_id.'/'.$video_file;
		}
		
		return $video;
	}
	
	/**
	 * Get MCSD Header
	 * 
	 * This method determines which MCSD header image to use and returns it
	 */
	private function _get_mcsd_logo($affiliate_id, $offer_id)
	{
		// initialize variables
		$image		= '/resources/brainhost/img/logo.png';				// The default image to use
		$image_file	= 'logo';											// The name of the custom image file
		$root_dir	= CUSTOM_PATH.'modules/free/views/affiliates/';		// The root directory path
		$http_dir	= '/resources/modules/free/views/affiliates/';		// The resource path for HTTP Requests
		$valid_ext	= array(
			'png',
			'gif',
			'jpg'
		);
		
		// determine if we have a valid extension in the affiliate directory
		foreach($valid_ext AS $extension):
			
			// determine if file exists, if so, set $image and break out of loop
			if (file_exists($root_dir.$affiliate_id.'/'.$image_file.'.'.$extension)):
			
				// set new image name
				$image	= $http_dir.$affiliate_id.'/'.$image_file.'.'.$extension;
				
				// break
				break;
			
			endif;			
			
		endforeach;
		
		// determine if we have a valid extension in the offer directory
		foreach ($valid_ext AS $extension):

			// determine if file exists, if so, set $image and break out of loop
			if (file_exists($root_dir.$affiliate_id.'/'.$offer_id.'/'.$image_file.'.'.$extension)):
			
				// set new image name
				$image	= $http_dir.$affiliate_id.'/'.$offer_id.'/'.$image_file.'.'.$extension;
				
				// break
				break;
			
			endif;
		
		endforeach;
		
		return $image;
	}

	/**
	 * This method gets any custom tracking pixels for this affiliate/offer
	 * @param  [type] $affiliate_id [description]
	 * @param  [type] $offer_id     [description]
	 * @return [type]               [description]
	 */
	private function _get_mcsd_pixel($affiliate_id,$offer_id)
	{
		// initialize variables
		$pixel_file	= 'pixel.php';									// The filename of the custom header
		$pixel 		= FALSE;
		$root_dir	= CUSTOM_PATH.'modules/free/views/affiliates/';	// The root directory path
		$http_dir	= '/resources/modules/free/views/affiliates/';	// The resource path for HTTP Requests
		
		// determine if there is a custom video in /views/affiliates/affiliate_id/offer_id
		if (file_exists($root_dir.$affiliate_id.'/'.$offer_id.'/'.$pixel_file))
		{
			$pixel	= file_get_contents($root_dir.$affiliate_id.'/'.$offer_id.'/'.$pixel_file);
		}
		// determine if there is a custom video in /views/affiliates/affiliate_id/
		elseif (file_exists($root_dir.$affiliate_id.'/'.$pixel_file))
		{
			$pixel	= file_get_contents($root_dir.$affiliate_id.'/'.$header_file);
		}
		
		return $pixel;
	}

	private function _get_mcsd_item($affiliate_id, $offer_id)
	{
		if ($affiliate_id=='102019' && $offer_id=='3662')
		{
			return 'Blog';
		}

		return 'Website';
	}
	
	
	
	
	/**
	 * Sitebuilder Categories
	 * 
	 * This method grabs all categories from sitebuilder
	 */
	private function _sitebuilder_categories()
	{
		// initialize variables
		$cats	= array(
			''	=> '-- Select a Category --'
		);	// This variable will hold the array of categories that Codeignitor can read	
		
		// grab categories from sitebuilder (eventually grab form Platform)
		$categories	= json_decode(file_get_contents('http://sitebuilder.brainhost.com/wp_autobuild/api/get_categories.php?api_key=a9463e0b'), 1);
		
		// iterate categories and add to our array
		foreach($categories AS $key => $value):
			$cats[]	= array(
				$value['id'] => $value['name']
			);
		endforeach;

		// return		
		return $cats;
	}
	
	private function _setup_submit()
	{
	
		// initialize variables
		$url				= $this->config->item('url');
		$pasword 			= $this->config->item('password');

		// CONVERT POST DATA INTO PLATFORM FRIENDLY REQUEST
		$site['name']		= $this->input->post('txtName');
		$site['email']		= $this->input->post('txtEmail');
		$site['url']		= strtolower($this->input->post('txtURL')); // CLEAN UP URL
		$site['category'] 	= $this->input->post('selCategory');
		$site['title']		= $this->input->post('txtTitle');
		$site['slogan']		= $this->input->post('txtSlogan');
		$site['keywords']	= NULL;
		$site['client_id']	= NULL;
		$site['order_id']	= $this->input->post('order_id');
	
		$bh_directory = false;
	
		//push into directory 
		if($bh_directory){
			
			// set post array
			$post	= array(
				'url'			=> $site['url'],
				'description'	=> $site['slogan'],
				'title'			=> $site['title'],
				'api_password'	=> $password
			);
			
			// curl data to insert into directory
			$this->curl->post('http://sitebuilder.brainhost.com/api/directory/push.php',$post);
		}

		// set post variables
		$post	= array(
			'url'		=> $site['url'],
			'client_id'	=> $site['client_id'],
			'order_id'	=> $site['order_id'],
			'email'		=> $site['email'],
			'tagline'	=> $site['slogan'],
			'title'		=> $site['title'],
			'keywords'	=> $site['keywords'],
			'category'	=> $site['category'],
			'pending'	=> 0,
			'sent_from'	=> 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']
		);	
				
		// add ticket to MCSD
		$ticket	= $this->curl->post($url,$post);
		
		// success page	
		redirect("free/website/success");
			
	}
	
	private function _redirect_banned_affiliates($affiliate_id=false,$offer_id=false)
	{
		// make sure we have valid affiliate and offer
		if ( ! $affiliate_id OR empty($affiliate_id) OR ! $offer_id OR empty($offer_id))	
			redirect('free/website/invalid');
		
		// initialize post array
		$post	= array(
			'affiliate_id'	=> $affiliate_id,
			'offer_id'		=> $offer_id
		);
		
		/*
		// determine if this is a banned affiliate
		$access	= $this->platform->post('affiliates/banned',array('affiliate_id' => $affiliate_id, 'offer_id' => $offer_id));	// banned affiliates include affiliates who do not have access to the offer they are requesting
		
		// determine if affiliate is banned
		$banned	= ($access['success'] === TRUE)
			? $access['data']
			: FALSE;
		
		// if this is a banned affiliate, redirect to "banned" page
		if ($banned === TRUE) redirect('mcsd/banned');	
		*/
		
		return TRUE;
	}
	
}