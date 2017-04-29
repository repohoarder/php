<?php 

class Declined extends MX_Controller
{

	var 
		$_funnel_id,
		$_partner_id,
		$_affiliate_id,
		$_offer_id,
		$_session_id;

	public function __construct()
	{
		parent::__construct();

		$this->lang->load('billing_declined',$this->session->userdata('_language'));

		$this->_funnel_id      = $this->session->userdata('funnel_id');
		$this->_partner_id     = $this->session->userdata('partner_id');
		
		$this->_affiliate_id   = $this->session->userdata('affiliate_id');
		$this->_offer_id       = $this->session->userdata('offer_id');

		$this->_session_id     = $this->session->userdata('session_id');


	}
	
	public function index($error=FALSE)
	{

		$allowed_sessions = array(
			'session_id',
			'last_activity',
			'ip_address',
			'user_agent',
			'visitor_id',
			'partner_id',
			'partner_info',
			'partner_funnel_info'
		);

		$current_sessions = $this->session->all_userdata();

		if (is_array($current_sessions) && ! empty($current_sessions)):

			$current_sessions = array_diff_key($current_sessions, array_flip($allowed_sessions));

			foreach ($current_sessions as $key => $value):

				$this->session->unset_userdata($key);

			endforeach;

		endif;

		$this->tracking->page_hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => 'declined',
			)
		);
		

		// initialize variables
		$data	= array();

		$data['partner_info'] = $this->session->userdata('partner_info');
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title($this->lang->line('declined_title'));
		
		// append the CSS file
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/declined.css" type="text/css" />');
		
		$data['error']	= urldecode($error);
		$data['partner_id']	= $this->_partner_id;

		// load view
		$this->template->build('billing/declined', $data);
	}
	
	public function paypal()
	{
		redirect('paypal/declined');
	}
}