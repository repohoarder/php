<?php

class Setup extends MX_Controller
{
	public function _construct()
	{
		parent::__construct();
	}

	/**
	 * This method displays the setup form
	 * @param  boolean 	$error       	This is the variable that holds any error that needs passed
	 * @param  boolean 	$skip_submit 	If this is TRUE, then we will skip the section that _submit 's the form
	 * @return view
	 */
	public function index($error=FALSE,$skip_submit=FALSE)
	{
		// initialize variables
		$data	= array();

		// if form was submitted, run _submit method
		if ($this->input->post() AND $skip_submit === FALSE)	return $this->_submit();

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Partner Setup');

		// set any errors passed
		$data['error']	= urldecode($error);
		
		// load view
		$this->template->build('setup/setup', $data);
	}

	/**
	 * This method logs in a user and sets needed sessions
	 * @return boolean
	 */
	private function _submit()
	{
		// initialize variables
		$company	= $this->input->post('company');
		$website	= $this->input->post('website');
		$first_name	= $this->input->post('first_name');
		$last_name	= $this->input->post('last_name');
		$email		= filter_var($this->input->post('email'), FILTER_SANITIZE_EMAIL);	// sanitize email
		$address	= $this->input->post('address');
		$city		= $this->input->post('city');
		$state		= $this->input->post('state');
		$zip		= $this->input->post('zip');
		$country	= $this->input->post('country');
		$phone		= preg_replace("/\D/","",$this->input->post('phone'));				// remove non numeric characters
		$username	= $this->input->post('username');
		$password 	= $this->input->post('password');									// user's password to use
		$confirm 	= $this->input->post('confirm');									// user's confirmation password

		// validate form submission
		$validate 	= $this->_form_validate();

		// if validation returned an error, stop & display it
		if ($validate !== TRUE)	return $this->index($validate,TRUE);

		// generate password
		$pass 		= $this->password->generate($password);

		// set password variables
		$password 	= $pass['password'];
		$encrypted	= $pass['encrypted'];
		$salt 		= $pass['salt'];


		// create post array (to add partner)
		$post 		= array(
			'company'		=> $company,
			'website'		=> $website,
			'first_name'	=> $first_name,
			'last_name'		=> $last_name,
			'email'			=> $email,
			'address'		=> $address,
			'city'			=> $city,
			'state'			=> $state,
			'zip'			=> $zip,
			'country'		=> $country,
			'phone'			=> $phone,
			'username'		=> $username,
			'password'		=> $encrypted,
			'password_salt'	=> $salt
		);

		// add partner
		$add 	= $this->platform->post('partner/account/add',$post);

		if ( ! $add['success'] OR ! $add['data'])	return $this->index($add['error'],TRUE);

		// if we made it here, then adding partner was successful
		redirect('setup/Adding partner was successful.');
	}

	private function _form_validate()
	{
		// initialize library
		$this->load->library('address_validation');

		// initialize variables
		$post 	= $this->input->post();

		// all fields are required, make sure they aren't empty
		foreach ($post AS $key => $value):
			if ( ! isset($post[$key]) OR empty($post[$key]))								return 'Please enter a valid '.$key;	// if this required field is empty (or not set) return error
		endforeach;

		// validate email address
		if ( ! filter_var($post['email'], FILTER_VALIDATE_EMAIL))							return 'Please enter a valid email address.';

		// validate phone number
		if (strlen($post['phone']) < 10)													return 'Please enter a valid phone number.';

		// verify username is not already taken
		if ( ! $this->_valid_username($post['username']))									return 'Username is already taken.';

		// validate username requirements
		if (strlen($post['username']) > 20 OR strlen($post['username']) < 8)				return 'Username must be between 8 and 20 cahracters.';	

		// verify password == confirmation password
		if ($post['password'] != $post['confirm'])											return 'Please enter valid password.';

		// validate zip
		if ( ! $this->address_validation->is_valid_zipcode($post['zip'],$post['country']))	return 'Please enter a valid zip code.';

		// validate state
		if ( ! $this->address_validation->is_valid_state($post['state'],$post['country']))	return 'Please enter a valid state.';
		
		return TRUE;
	}

	/**
	 * This method determines if given username is available for use
	 * @param  boolean $username The username to check validity
	 * @return boolean
	 */
	private function _valid_username($username=FALSE)
	{
		// error handling
		if ( ! $username)	return FALSE;

		// see if this username has already been taken
		$user 	= $this->platform->post('partner/account/valid_username',array('username' => $username));

		// return TRUE on not taken, FALSE on taken
		return ( ! $user['success'] OR $user['data'] !== TRUE)
			? FALSE
			: TRUE;
	}
}