<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Special
 * 
 * This class handles the functionality for MCSD Special Offer Page
 * 
 * 
 */
class Offer extends MX_Controller
{
	public function index()
	{
		$this->admin();
	}
	
	public function admin()
	{
		if ($this->input->post()) return $this->_admin_submit();
		
		// set the layout to use
		$this->template->set_layout('bare');
		
		// append the CSS files
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/main.css">');
		
		// display view/content
		$this->template->build('offer');
	}
	
	private function _admin_submit()
	{

		// CONVERT POST DATA INTO PLATFORM FRIENDLY REQUEST
		
		$offer['affiliate_id']	= $this->input->post('affID');
		$offer['offer_id']		= $this->input->post('offID');
		$offer['logo']			= $this->input->post('logo');
		$offer['video']			= $this->input->post('video');
		$offer['header']		= $this->input->post('header');
		
		$root_dir	= CUSTOM_PATH.'modules/free/views/affiliates/';
		$dir = $root_dir.$offer['affiliate_id'].'/';
		@mkdir($dir);
		
		if ($offer['offer_id'] > 0)
		{
			$dir.=$offer['offer_id'].'/';
			@mkdir($dir);
		}
		
		$config['upload_path']	= $dir;
		$config['overwrite']	= true;
		$config['allowed_types'] = '*';
		
		$config['file_name'] = 'logo.png';

		$this->load->library('upload', $config);
		$this->upload->do_upload('logo');
		
		//$config['file_name'] = 'video.flv';
		
		//$this->load->library('upload', $config);
		//$this->upload->do_upload('video');
		
		if (isset($offer['video']) && $offer['video']!='')
		{
			$strVideoContent=file_get_contents($offer['video']);
			file_put_contents($dir.'video.flv', $strVideoContent);
		}
		
		if (isset($offer['header']) && $offer['header']!='')
		{
			file_put_contents($dir.'header.txt', $offer['header']);
		}
		

		$this->load->view('success');
			
	}
}
?>