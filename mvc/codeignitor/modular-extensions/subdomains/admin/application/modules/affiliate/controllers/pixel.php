<?php 

class Pixel extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// see if data was submitted
		if ($this->input->post())	return $this->_submit();

		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title($this->lang->line('brand_company').' Affiliate Pixels');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['partners']	= $this->_partners_dropdown();	// active partners
		$data['error']		= urldecode($error);
		
		// load view
		$this->template->build('affiliate/pixel', $data);
	}

	private function _submit()
	{
		// initialize variables
		$post['partner_id'] 	= $this->input->post('partner_id');
		$post['affiliate_id'] 	= $this->input->post('affiliate_id');
		$post['offer_id']		= $this->input->post('offer_id');
		$post['pixel'] 			= $this->input->post('pixel');
		$post['type'] 			= $this->input->post('type');

		// add the pixel
		$add 		= $this->platform->post('partner/pixel/add',$post);

		// was adding successful?
		$message	= ($add['success'] AND $add['data'])? 'Pixel successfully added.': 'Adding pixel was unsuccessful.';

		// return response
		redirect('affiliate/pixel/'.$message);
	}

	private function _partners_dropdown()
	{
		// initialize variables
		$dropdown 	= array();	// the dropdown array to display

		// grab active partners
		$partners 	= $this->platform->post('partner/account/queue',array('where' => array('active' => 1)));

		if ( ! $partners['success'] OR empty($partners['data']))
			return array('1', 'Brain Host - Ryan Niddel');

		// iterate through partners to create dropdown array
		foreach ($partners['data'] AS $key => $value):

			// add to dropdown array
			$dropdown[]	= array($value['id'] => $value['company'].' - '.$value['first_name'].' '.$value['last_name']);

		endforeach;

		// return dropdown array
		return $dropdown;
	}
}