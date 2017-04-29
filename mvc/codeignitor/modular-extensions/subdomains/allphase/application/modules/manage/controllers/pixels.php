<?php

class Pixels extends MX_Controller
{
	/**
	 * The array of partner information
	 * @var int
	 */
	var $_partner;
	var $_pixel_types;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');

		$this->load->config('pixels');
		$this->_pixel_types = $this->config->item('pixel_types');
	}

	public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();

		// if data was submitted, then update account information
		if ($this->input->post('add_pixel'))	return $this->_submit_add_pixel();
		if ($this->input->post('delete_pixel'))	return $this->_submit_delete_pixel();
		
		// grab partner account details
		$pixels 	= $this->platform->post(
			'partner/pixel/get',
			array(
				'partner_id' => $this->_partner['id'],
				'approved'   => 'both'
			)
		);
		
		// make sure we got valid pixels
		if ( ! $pixels['success'] OR empty($pixels['data']))	$pixels['data']	= array('pixels' => array());

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Manage Pixels');

		// set data variables
		$data['error']  = urldecode($error);
		$data['pixels'] = $pixels['data']['pixels'];
		$data['types']  = $this->_pixel_types;
		
		// load view
		$this->template->build('manage/pixels', $data);
	}

	function _submit_add_pixel()
	{

		$post  = $this->input->post(NULL, TRUE);
		$pixel = $this->input->unclean_post('pixel'); // MY_Input.php
		$type  = (isset($post['type']) ? $post['type'] : 'all');

		if ( ! array_key_exists($type, $this->_pixel_types)):

			return redirect('manage/pixels/Invalid pixel type specified');

		endif;

		$name = (isset($post['name']) ? $post['name'] : 'Custom Tracking Pixel');

		if ( ! $pixel):

			return redirect('manage/pixels/Please enter tracking pixel code');

		endif;

		$params = array(
			'pixel'      => $pixel,
			'name'       => $name,
			'type'       => $type,
			'partner_id' => $this->_partner['id']
		);

		$resp = $this->platform->post(
			'partner/pixel/add',
			$params
		);

		if ( ! $resp['success']):

			$errors = implode(' ',$resp['error']);
			$errors = ($errors ? $errors : 'Unable to add pixel');

			return redirect('manage/pixels/'.$errors);

		endif;

		@mail(
			'developers@brainhost.com',
			'Partner #'.$this->_partner['id'].' tracking pixel',
			'Please review and approve Partner #'.$this->_partner['id'].'\'s tracking pixel. '."\n\n".'http://a.allphasehosting.com/admin/partner/pixels/view'
		);

		$upload = $this->platform->post(
			'partner/website/upload_pixels_config', 
			array(
				'partner_id' => $this->_partner['id']
			)
		);

		return redirect('manage/pixels/Successfully added tracking pixel');

	}

	function _submit_delete_pixel()
	{

		$pixel_id = $this->input->post('pixel_id');

		if ( ! $pixel_id):

			return redirect('manage/pixels/Invalid pixel ID specified');

		endif;

		$params = array(
			'pixel_id'   => $pixel_id,
			'partner_id' => $this->_partner['id']
		);

		$resp = $this->platform->post(
			'partner/pixel/deactivate',
			$params
		);	

		if ( ! $resp['success']):

			$errors = implode(' ',$resp['error']);
			$errors = ($errors ? $errors : 'Unable to delete pixel');

			return redirect('manage/pixels/'.$errors);

		endif;

		$upload = $this->platform->post(
			'partner/website/upload_pixels_config', 
			array(
				'partner_id' => $this->_partner['id']
			)
		);

		return redirect('manage/pixels/Pixel deleted');

	}

}