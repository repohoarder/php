<?php

class Thankyou extends MX_Controller
{
	/**
	 * The array of partner information
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

		// if data was submitted, then update account information
		if ($this->input->post())	:
			$this->_submit();
		endif;
		
		$cthy = $this->platform->post('aff_custom_content/content/get',
				array(
					'partner_id' => $this->_partner['id'],
					'slug'   => 'completed',
					'brand_id' => 4
				)
				);
		
		$data['content_custom'] = '';
		if($cthy['success']) :
			if( isset($cthy['data']['content'])) :
				$data['content_custom'] = $cthy['data']['content'];
			endif;
		endif;
		
		// set template layout to use
		$this->template->set_layout('default');
		$this->template->append_metadata('<script type="text/javascript" src="/resources/allphase/js/tiny_mce/tiny_mce.js"></script>');
		$this->template->append_metadata('<script type="text/javascript" src="/resources/allphase/js/tiny_mce/mce.js"></script>');

		// set the page's title
		$this->template->title('Manage Account');

		// set data variables
		$data['error']		= urldecode($error);
		$data['partnerid']  = $this->_partner['id'];
		// load view
		$this->template->build('manage/thankyou', $data);
	}

	private function _submit()
	{
		$cthy = $this->platform->post('aff_custom_content/content/insert',
				array(
					'partner_id' => $this->_partner['id'],
					'slug'   => 'completed',
					'brand_id' => 4,
					'content' => $this->input->post('content')
				)
				);
	}
	
	
}

