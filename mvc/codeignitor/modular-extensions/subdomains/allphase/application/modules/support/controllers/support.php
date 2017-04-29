<?php

class Support extends MX_Controller
{
	/**
	 * The Partner Info
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner		= $this->session->userdata('partner');
	}

	public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();

		// if data was submitted, run _submit()
		if ($this->input->post())	return $this->_submit();
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Partner Support');
		
		// set data variables
		$data['error']	= urldecode($error);

		// load view
		$this->template->build('support/support', $data);
	}

	/**
	 * This method send the Partner's message to All Phase Partner Support
	 * @return boolean
	 */
	private function _submit()
	{
		// intiialize variables
		$subject 	= $this->input->post('subject');
		$message 	= $this->input->post('message');
		
		// grab partner details
		$partner 	= $this->platform->post('partner/account/details',array('partner_id' => $this->_partner['id']));

		// if unable to grab partner details, return error
		if ( ! $partner['success'] OR empty($partner['data']))	redirect('support/There was an error grabbing partner details.');

		// set partner details
		$name 		= $partner['data'][0]['first_name'].' '.$partner['data'][0]['last_name'];
		$email 		= $partner['data'][0]['email'];


		// set account manager details
		$manager_name	= $partner['data'][0]['manager']['first_name'].' '.$partner['data'][0]['manager']['last_name'];
		$manager		= $partner['data'][0]['manager']['email'];														// This is this partner's account manager's email
		$message		.= "\n\nPartner ID: ".$partner['data'][0]['id'];
		
		// load email library
		$this->load->library('email');

		// send email to All Phase Account Manager for this Partner
		$this->email->from($email,$name);							// set from name & address
		$this->email->to($manager);									// send email to account manager
		$this->email->cc($email);									// CC Support Staff & send copy to partner as well
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();										// send email
		
		// redirect user
		redirect('support/Your request has been sent.');
	}
}