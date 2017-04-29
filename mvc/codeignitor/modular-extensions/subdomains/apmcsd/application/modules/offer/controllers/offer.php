<?php

class Offer extends MX_Controller
{
	var $_video;
	var $_text;
	var $_landing_page_id;	// this is the landing_pages id in the database for this page
	var $_theme;			// this is the custom theme for this page
	var $_layout;			// this is the custom layout for this page
	var $_view;				// this is the custom view for this page
	var $_logo;
	var $_language;

	public function __construct()
	{
		parent::__construct();

		// default language to english
		$this->_language 		= 'english';

		// set default theme variables
		$this->_theme			= 'apmcsd';
		$this->_layout			= 'default';
		$this->_view			= 'video';

		// set default logo to load
		$this->_logo			= 'http://a.hostingaccountsetup.com/resources/apmcsd/img/logo.png';

		// set default video and text variables
		## The default video changed on 4/19 per Jessica
		$this->_video			= 'http://8beeda049e5d49c340dd-9e0fc90ca393afce126174d62bc6e1d5.r61.cf1.rackcdn.com/Brain_Host_Affiliate_Video_4-18-13.flv'; //'http://3ad6d0cb6f5c71565427-0ceefa5c30f5e4f21bf782807a37e6a1.r85.cf1.rackcdn.com/Generic_Sales_Video_1-7.flv';
		$this->_text			= 'A Special Offer For You';
	}
        
    public function index($landing_page_id=1,$partner_id=FALSE,$affiliate_id=0,$offer_id=0,$build_type=FALSE,$build_version=FALSE,$subid=FALSE,$funnel_id=FALSE)
	{
		// initialize variables
		$data	= array();

		// load language
		$this->_load_language_files($partner_id,$funnel_id);

		// set landing page id to global variable
		$this->_landing_page_id 	= $landing_page_id;

		// if no funnel id was passed, then we need to get the default funnel for this partner
		if ( ! $funnel_id)
			$funnel_id 	= $this->_get_default_funnel($partner_id,$affiliate_id,$offer_id);

		// offer_id partner mcsd variables
		$variables 	= $this->_get_custom_variables($partner_id,$affiliate_id,$offer_id);

        // set data variables    
        $data['partner_id'] 	= $partner_id;
        $data['funnel_id']		= $funnel_id;
        $data['affiliate_id']	= $affiliate_id;
        $data['offer_id']		= $offer_id;
        $data['video']			= $variables['video'];	// the cusotm video to show
        $data['text']			= $variables['text'];	// the custom header text to display
        $data['theme']			= $variables['theme'];
        $data['layout']			= $variables['layout'];
        $data['logo']			= $variables['logo'];
        $data['view']			= @$variables['view'];

        $data['youtube_id']     = FALSE;

        if (strstr($data['video'],'youtu') !== FALSE):
        	$data['youtube_id'] = $this->_parse_youtube($data['video']);
        endif;

        // set aff and offer to 0 if not set
        if ( ! $affiliate_id)
        	$affiliate_id 	= 0;

        // set offer to 0 if not 
        if ( ! $offer_id)
        	$offer_id 		= 0;

        $data['url']			= 'https://infrastructure.hostingaccountsetup.com/initialize/'.$partner_id.'/'.$funnel_id.'/'.$affiliate_id.'/'.$offer_id;	// the sales funnel URL

        // if build type was passed, then append to URL
        if ($build_type AND $build_version)
        	$data['url']	.= '/'.$build_type.'/'.$build_version;
        
        $this->template->set_theme($data['theme']);

		// set template layout to use
		$this->template->set_layout($data['layout']);

		// set the page's title
		$this->template->title($data['text']);

		// set logo to session (for later use - aka T&C)
		$this->session->set_userdata('_logo',$data['logo']);
		
		// set exit pop
		$data['exitpopup'] = true ;

		// if no view is loaded, show error
		if ( ! $data['view'] OR $data['view'] == '')
			show_error('You are not allowed to access this page.');

		// load view
		$this->template->build('offer/'.$data['view'], $data);
	}

	private function _parse_youtube($embed_url)
	{
	
		preg_match('/<iframe.*?src="(.*?)"/',$embed_url, $matches);

		if(is_array($matches) && count($matches) > 1):
			$embed_url = $matches[1];	
		endif;		
		
		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/|youtube.com/embed/|youtube-nocookie.com/embed/)[^&\n]+#", $embed_url, $matches);	

		if( ! is_array($matches) || count($matches) < 1):
			return FALSE;
		endif;
		
		return $matches[0];

	}

	private function _load_language_files($partner_id=FALSE,$funnel_id=FALSE)
	{
		// get language to load
		$language 	= $this->_get_language($partner_id,$funnel_id);

		// set alnguage session
		$this->session->set_userdata('_language',$language);

		// set config item for language
		$this->config->set_item('language',$language);

		// load all files
		$this->lang->load('mcsd',$language);
		$this->lang->load('footer',$language);

		// return
		return;
	}

	private function _get_language($partner_id=FALSE,$funnel_id=FALSE)
	{
		// if funnel id is empty, grab default funnel for this partner
		if ( ! $funnel_id):

			// grab deafult funnel for this partner
			$default	= $this->platform->post('sales_funnel/version/get_default',array('partner_id' => $partner_id));

			// if no default funnel id is found, set funnel to 1
			if ( ! $default['success'] OR ! isset($default['data'][0]) OR ! is_array($default['data'][0])):

				$funnel_id 	= 1;

			else:	// we were able to grab default funnel id, set it

				$funnel_id 	= $default['data'][0]['funnel_id'];

			endif;

		endif;

		// grab language from platform
		$lang 		= $this->platform->post('partner/language/get',array('partner_id' => $partner_id, 'funnel_id' => $funnel_id));

		// set global language variable
		return (isset($lang['data']['slug']) AND ! empty($lang['data']['slug']))? $lang['data']['slug']: $this->_language;
	}

	private function _get_default_funnel($partner_id=FALSE,$affiliate_id=FALSE,$offer_id=FALSE)
	{
		// if no partner, then return 0
		if ( ! $partner_id)	return 0;

		// create post array
		$post 		= array(
			'partner_id'	=> $partner_id,
			'affiliate_id'	=> $affiliate_id,
			'offer_id'		=> $offer_id
		);

		// grab default funnel for this partner
		$default 	= $this->platform->post('sales_funnel/version/get_default',$post);

		// if platform call was not successful, then return 0
		if ( ! $default['success'] OR ! is_array($default['data']))	
			return 0;

		// return default funnel id
		return $default['data'][0]['funnel_id'];
	}

	/**
	 * This method grabs custom variables to load on the page for this partner
	 * @param  array  $post [description]
	 * @return [type]       [description]
	 */
	private function _get_custom_variables($partner_id=FALSE,$affiliate_id=FALSE,$offer_id=FALSE)
	{
		// initialize variables
		$defaults 	= array(
			'video'		=> $this->_video,
			'text'		=> $this->_text,
			'theme'		=> $this->_theme,
			'layout'	=> $this->_layout,
			'logo'		=> $this->_logo
		);

		// if no partner, then return defaults
		if ( ! $partner_id)	return $defaults;

		// create post array
		$post 	= array(
			'landing_page_id'	=> $this->_landing_page_id,
			'partner_id'		=> $partner_id,
			'affiliate_id'		=> $affiliate_id,
			'offer_id'			=> $offer_id
		);

		// see if this landing_page/partner/affiliate/offer has custom variables for this page
		$variables 	= $this->platform->post('partner/lander/get',$post);

		// if we didn't get any data, then return defaults
		if ( ! $variables['success'] OR ! is_array($variables['data']))
			return $defaults;

		// initialize variables
		$video 		= $variables['data'][0]['video'];
		$text 		= $variables['data'][0]['text'];
		$theme 		= $variables['data'][0]['theme'];
		$layout 	= $variables['data'][0]['layout'];
		$view 		= $variables['data'][0]['view'];
		//$logo 		= $variables['data'][0]['logo_type'];
		$img 		= $variables['data'][0]['logo_file'];
		//$img 		= 'http://partner.allphasehosting.com/resources/modules/manage/assets/images/partners/'.$partner_id.'/'.$file;

		// if we made it here, then we successfully grabbed variables - return them
		return array(
			'video'		=> ( ! empty($video))? 		$video: 	$this->_video,
			'view'		=> ( ! empty($view))? 		$view: 		$this->_view,
			'text'		=> ( ! empty($text))? 		$text: 		$this->_text,
			'theme'		=> ( ! empty($theme))? 		$theme: 	$this->_theme,
			'layout'	=> ( ! empty($layout))? 	$layout: 	$this->_layout,
			'logo'		=> ($this->_is_image($img))? $img: $this->_logo	// see if partner has a logo and its a valid image URL
		);
	}

	private function _is_image($url)
	{
		// see if we get data back
		$image 	= @getimagesize($url);

		return (is_array($image))
			? TRUE 
			: FALSE;
	}
}