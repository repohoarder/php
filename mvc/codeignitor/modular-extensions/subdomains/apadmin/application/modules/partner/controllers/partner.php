<?php 

class Partner extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function view($partner_id=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// grab pages
		$queue	= $this->platform->post('partner/account/listing');

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('All Phase Partner Listing');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/partner/assets/css/listing.css">');
		
		// set data variables
		$data['list']	= $queue['data'];	// The available pages
		
		// load view
		$this->template->build('partner/view', $data);
	}

	public function edit($partner_id=FALSE,$error='')
	{
		// initialize variables
		$data	= array();

		// if data is submitted, then update the ifno
		if ($this->input->post())
			$this->_submit();

		// grab partner website information
		$website 	= $this->platform->post('partner/website/details',array('partner_id' => $partner_id));
		
		// if unable to grab website data, set as empty array (to avoid errors)
		if ( ! $website['success'] OR ! $website['data'] OR empty($website['data']))	$website	= array('data' => array());

		// set template layout to use
		$this->template->set_layout('default');

		// load data variables
		$data['error']		= $error;
		$data['partner_id']	= $partner_id;
		$data['website']	= $website['data'];

		// load view
		$this->template->build('partner/edit', $data);
	}

	public function create()
	{
		// initialize variables
		$data	= array();

		// set template layout to use
		$this->template->set_layout('default');

		// load view
		$this->template->build('partner/create', $data);
	}

	public function funnels($partner_id=false)
	{
		// initialize variables
		$data	= array();
		
		// grab pages
		if ($partner_id)
		{
			$queue	= $this->platform->post('partner/funnels/get', array('partner_id'=>$partner_id));
		}
		else
		{
			$queue	= $this->platform->post('partner/funnels/get_all');
		}
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('All Phase Partner Funnels Listing');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['partner_id']	= $partner_id;
		$data['list']	= $queue['data'];	// The available pages
		
		// load view
		$this->template->build('partner/funnels', $data);
	}
	
	public function pricings($partner_id, $funnel_id)
	{
		// initialize variables
		$data	= array();
		
		// grab pages
		$queue	= $this->platform->post('partner/pricing/get_all', array('partner_id'=>$partner_id, 'funnel_id'=>$funnel_id));
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('All Phase Partner Funnel Pricings Listing');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['list']	= $queue['data'];	// The available pages
		
		// load view
		$this->template->build('partner/pricings', $data);
	}

	private function _submit()
	{
		// initialize variables
		$partner_id 	= $this->input->post('partner_id');
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
	}

	private function _upload($partner_id, $logo, $logo_type)
	{
		$maxwidth		= 750;
		$maxheight		= 75;
		
		@mkdir('/home/infrastr/ci/subdomains/allphase/application/modules/manage/assets/images/partners/'.$partner_id);
		//@mkdir('application/modules/manage/assets/images/partners/'.$partner_id.'/'.$domain);

		if ($logo_type=='upload' && $_FILES['logo_upload']['name'])
		{
			$logo		= $_FILES['logo_upload']['name'];
			
			$newfile	= '/home/infrastr/ci/subdomains/allphase/application/modules/manage/assets/images/partners/'.$partner_id.'/'.$_FILES['logo_upload']['name'];
			//@move_uploaded_file($_FILES['logo_upload']['tmp_name'],
			//	'/home/infrastr/ci/subdomains/allphase/application//modules/manage/assets/images/partners/'.$partner_id.'/'.$domain.'/'.$_FILES['logo_upload']['name']);
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
					redirect('partner/edit/'.$partner_id.'/There was an error updating your website logo.');
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
			
			$savedfile	= '/home/infrastr/ci/subdomains/allphase/application/modules/manage/assets/images/partners/'.$partner_id.'/'.$dbfile;
			@unlink($savedfile);
			
			rename($newfile, $savedfile);
			
			return 'https://partner.allphasehosting.com/resources/modules/manage/assets/images/partners/'.$partner_id.'/'.$dbfile;
		}
		
		return false;
	}

}