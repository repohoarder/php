<?php

class Forgot extends MX_Controller
{
	/**
	 * The ID of this Partner
	 * @var int
	 */
	var $_partner_id;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner_id	= $this->session->userdata('partner_id');
	}

	public function index($error=FALSE,$skip_submit=FALSE)
	{
		// initialize variables
		$data	= array();

		// if URI Segments is empty, then we need to do a redirect
		//if (empty($this->uri->segments))	redirect('signin');

		// if form was submitted, run _submit method
		if ($this->input->post())	$error = $this->_submit();

		// set template layout to use
		$this->template->set_layout('pre-login');
		
		// set the page's title
		$this->template->title('Partner Login');

		// set data variables
		$data['error']	= urldecode($error);
		
		// load view
		$this->template->build('pass/forgotpass', $data);
	}

	/**
	 * This method logs in a user and sets needed sessions
	 * @return boolean
	 */
	private function _submit()
	{
		// initialize variables
		$username 	= $this->input->post('forgotusername');
		$email		= $this->input->post('forgotemail');

		$result = $this->platform->post("partner/account/logincredentials",array("username"=>$username,"email"=>$email));
		if( ! $result['success']) :
			return 'Error Accessing Account';
		endif;
		
		
		$email = $result['data']['email'];
		$username = $result['data']['username'];
		$name = $result['data']['first_name'] . " " .$result['data']['last_name'];
		$password = $result['data']['password'];
		$message = "
		Hello $name, \n\n 
		Thank you for using our Password Recovery Tool. Please use the credentials below to access your dashboard.\n\n
		http://partners.allphasehosting.com  \n
		Username : $username \n
		Password: $password \n\n
		For additional assistance regarding your account or our Partner program, please  contact your account manager directly or email partners@allphasehosting.com\n\n
		Thank you, \n
		The All Phase Team";
		$config = array(
			'toemail' => $email,
			'subject' => "Forgot Password",
			'message' => $message,
			'from'	  => 'partners@allphasehosting.com',
			'fromname'=> "All Phase Hosting Team"
		);
		$result = $this->platform->post("emailer/sendthatemail",$config);
		if( ! $result['success'] ) :
			return 'Email failed to send';
		endif;
			return "An email has been sent to your email on file.";
	}

	
}
