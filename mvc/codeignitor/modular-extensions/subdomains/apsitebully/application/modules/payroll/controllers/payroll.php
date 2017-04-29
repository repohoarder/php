<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Special
 * 
 * This class handles the functionality for MCSD Special Offer Page
 * 
 * 
 */
class Payroll extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		// load MCSD config
		//$this->load->config('sitebuilder');
	}

	public function index()
	{
		// show the free website offer page
		//$this->offer();
		$this->payroll();
		
	}
	
	public function payroll() {
	
		// determine if user is submitting the form
		if ($this->input->post()) return $this->_payroll_submit();
		
		// set the layout to use
		$this->template->set_layout('bare');
		
		// append the CSS files
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/payroll/assets/css/main.css">');
		$this->template->append_metadata('<link href="/resources/modules/payroll/assets/css/ui-lightness/jquery-ui-1.8.23.custom.css" rel="stylesheet" type="text/css" />');
			
		// append the JS files	
		$this->template->append_metadata('<script src="/resources/modules/payroll/assets/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>');
			    			
		// display view/content
		// $this->template->build('payroll', $data);
		$this->template->build('payroll');
		
	}
	
	private function _payroll_submit()
	{
	
		// initialize variables
		// $url				= $this->config->item('url');
		// $pasword 			= $this->config->item('password');

		// CONVERT POST DATA INTO PLATFORM FRIENDLY REQUEST
		
		$payroll['date_start']		= $this->input->post('dateStart');
		$payroll['date_end']		= $this->input->post('dateEnd');
		$payroll['department']		= $this->input->post('department');
		$payroll['expense_gross_payroll']	= $this->input->post('expenseGrossPayroll');
		$payroll['expense_benefit']	= $this->input->post('expenseCompanyBenefit');
		$payroll['expense_tax']		= $this->input->post('expenseCompanyTax');
		
		print_r($payroll);
		die;		
				
		// success page	
		redirect("payroll/payroll/success");
			
	}
	
	
	
	
	
	
	/*
	public function offer($affiliate_id=false,$offer_id=false,$banner_id=0,$subid=0,$passback=false)
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
		$this->template->title($this->lang->line('brand_free_website_title'));
		
		// set needed data variables
		$data['video']			= $this->_get_mcsd_video($affiliate_id,$offer_id);		// The video to show
		$data['image'] 			= $this->_get_mcsd_header($affiliate_id, $offer_id);	// The header to use (custom or not)
		$data['brand'] 			= $this->lang->line('brand_company');					// The company brand
		$data['redirect']	 	= $redirect;											// The affiliate redirect URL
		$data['layout_style']	= '/resources/modules/free/assets/css/style.css'; 		// The custom stylesheet to use
		
		// display view/content
		$this->template->build('offer', $data);
	}
	*/
	
	public function setup($order_id=false)
	{
		/*
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
		*/
		
		// set the layout to use
		$this->template->set_layout('bare');
		
		// set the page title
		// $this->template->title($this->lang->line('brand_free_website_title'));
		
		// append the CSS file
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/payroll/assets/css/main.css">');
				
		// display view/content
		$this->template->build('setup', $data);
	}
	
	public function success() {
		
		// set the layout to use
		$this->template->set_layout('bare');
		
		// display view/content
		$this->template->build('success');
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
	 * Get MCSD Video
	 * 
	 * This method determines which MCSD video to use and returns it
	 */
	private function _get_mcsd_video($affiliate_id, $offer_id)
	{
		// initialize variables
		$video		= 'http://setup.brainhost.com/resources/modules/free/assets/vid/offer.flv';	// The default video file
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
	private function _get_mcsd_header($affiliate_id, $offer_id)
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