<?php

class Website extends MX_Controller
{
	/**
	 * The array of Partner Information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}

	public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();

		// if user POSTed data, run submit function
		if ($this->input->post())	return $this->_submit();

		// grab partner website information
		$website 	= $this->platform->post('partner/website/details',array('partner_id' => $this->_partner['id']));

		// get partner subscribed services
		$services 	= $this->platform->post('partner/packages/getid',array('partner_id' => $this->_partner['id']));
		
		// if unable to grab website data, set as empty array (to avoid errors)
		if ( ! $website['success'] OR ! $website['data'] OR empty($website['data']))	$website	= array('data' => array());
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Manage Website');

		// set data variables
		$data['error']			= urldecode($error);
		$data['website']		= $website['data'];
		$data['services']		= $services['success'] === true ?  $services['data'] : array();
		$data['partner_id']		= $this->_partner['id'];
		
		// load view
		$this->template->build('manage/website', $data);
	}

	private function _submit()
	{
		// initialize variables
		$partner_id 	= $this->_partner['id'];
		$company_name	= $this->input->post('company_name');		// the company name of the partner website
		$domain			= $this->input->post('domain');				// the domain name of the partner's website
		$domain_type	= $this->input->post('domain_type');
		$logo_type		= $this->input->post('logo_type');			// this isthe type of logo the partner is using
		$logo 			= $this->input->post('logo_'.$logo_type);	// This is the logo (either text or upload URL)
		$facebook_url	= $this->input->post('facebook_url');
		$twitter_url	= $this->input->post('twitter_url');
		$google_url		= $this->input->post('google_url');
		
		$filename		= $this->_upload($partner_id, $logo, $logo_type);
		/*if ($filename)
		{
			$logo		= $filename;
		}*/

		// create array of fields to update
		$update 		= array(
			'partner_id'	=> $partner_id,
			'company_name'	=> $company_name,
			'domain'		=> $domain,
			'domain_type'	=> $domain_type,
			'logo_type'		=> $logo_type,
			'logo'			=> $logo,
			'logo_file'		=> $filename,
			'twitter_url'	=> $twitter_url,
			'google_url'	=> $google_url,
			'facebook_url'	=> $facebook_url
		);

		// update partner website details
		$update 		= $this->platform->post('partner/website/update',$update);

		// if update was not successful, then return error
		if ( ! $update['success'] OR ! $update['data']):

			// return an error
			redirect('manage/website/There was an error updating your website details.');
			exit;

		endif;

		// update partner website variables (custom fields that need replaced)

		// update theme on partner's website? (rebuild website)

		$upload = $this->platform->post(
			'partner/website/upload_options_config', 
			array(
				'partner_id' => $this->_partner['id']
			)
		);		
		
		redirect('manage/website/Successfully updated website.');
	}
	
	private function _upload($partner_id, $logo, $logo_type)
	{
		$maxwidth		= 750;
		$maxheight		= 75;
		
		@mkdir(APPPATH.'modules/manage/assets/images/partners/'.$partner_id);
		//@mkdir('application/modules/manage/assets/images/partners/'.$partner_id.'/'.$domain);
		
		if ($logo_type=='upload' && $_FILES['logo_upload']['name'])
		{
			$logo		= $_FILES['logo_upload']['name'];
			
			$newfile	= APPPATH.'modules/manage/assets/images/partners/'.$partner_id.'/'.$_FILES['logo_upload']['name'];
			//@move_uploaded_file($_FILES['logo_upload']['tmp_name'],
			//	APPPATH.'/modules/manage/assets/images/partners/'.$partner_id.'/'.$domain.'/'.$_FILES['logo_upload']['name']);
			@move_uploaded_file($_FILES['logo_upload']['tmp_name'], $newfile);
			
			$i			= strrpos($_FILES['logo_upload']['name'],".");
			$l			= strlen($_FILES['logo_upload']['name']) - $i;
			$extension	= substr($_FILES['logo_upload']['name'],$i+1,$l);
			
			switch ($extension)
			{
				case 'jpg':
					$src	= imagecreatefromjpeg($newfile);
					break;
				case 'jpeg':
					$src	= imagecreatefromjpeg($newfile);
					break;
				case 'png':
					$src	= imagecreatefrompng($newfile);
					imagealphablending($src, false);
					imagesavealpha($src, true);
					break;
				case 'gif':
					$src	= imagecreatefromgif($newfile);
					break;
				default:
					redirect('manage/website/There was an error updating your website logo.');
					break;
			}
			
			list($width,$height)=getimagesize($newfile);
			
			$newwidth		= $width;
			$newheight		= $height;
			if ($width>$maxwidth || $height>$maxheight)
			{
				$widthratio		= $maxwidth/$width;
				$heightratio	= $maxheight/$height;
				
				$ratio		= $widthratio;
				if ($widthratio>$heightratio)
				{
					$ratio		= $heightratio;
				}
				
				$newwidth	*= $ratio;
				$newheight	*= $ratio;
				$newwidth	= round($newwidth);
				$newheight	= round($newheight);
			}
			
			$tmp=imagecreatetruecolor($newwidth,$newheight);
			if ($extension=='png')
			{
				imagealphablending($tmp, false);
				imagesavealpha($tmp, true);
			}
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
			
			switch ($extension)
			{
				case 'jpg':
					imagejpeg($tmp,$newfile);
					break;
				case 'jpeg':
					imagejpeg($tmp,$newfile);
					break;
				case 'png':
					imagealphablending($tmp, false);
					imagesavealpha($tmp, true);
					imagepng($tmp,$newfile);
					break;
				case 'gif':
					imagegif($tmp,$newfile);
					break;
			}
			
			$dbfile		= time().'.'.$extension;
			
			$savedfile	= APPPATH.'modules/manage/assets/images/partners/'.$partner_id.'/'.$dbfile;
			@unlink($savedfile);
			
			rename($newfile, $savedfile);
			
			return 'https://partner.allphasehosting.com/resources/modules/manage/assets/images/partners/'.$partner_id.'/'.$dbfile;
		}
		
		return false;
	}
}
