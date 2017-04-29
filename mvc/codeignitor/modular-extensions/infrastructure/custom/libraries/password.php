<?php

class Password
{

	/**
	 * The Codeignitor Object
	 * @var object
	 */
	var $CI;

	public function __construct()
	{
		$this->CI 	= &get_instance();
	}

	/**
	 * This method generates a new password
	 * @param  string 	$password 	You can optionally pass a password to generate.  If none is supplied, this method will generate one for you.
	 * @return array 				This method returns an array of both the encrypted, unencrypted and salt used to generate password.
	 */
	public function generate($password=FALSE)
	{
		// generate salt
		$salt 		= rand(1000000,999999999);	// grab random number between 1,000,000 & 999,999,999

		// if no password is supplied, auto-generate one
		if ( ! $password):
			
			// auto-generate password (through platform)
			$password 	= $this->platform->post('ubersmith/password/generate',array());

			// if unable to grab from platform - default it
			$password 	= ( ! $password['success'] OR empty($password['data']))
				? 'butt3rfly64'
				: $password['data'];

		endif;

		// encrypt the password
		$encrypted 	= $this->CI->security->encrypt($password,$salt);		

		// set return array
		$return 	= array(
			'password'	=> $password,
			'salt'		=> $salt,
			'encrypted'	=> $encrypted
		);


		return $return;
	}

	/**
	 * This method decrypts a password and returns it unencrypted
	 * @param  string $password 	This is the password hash to be decrypted
	 * @param  string $salt     	This is the salt that was used to encrypt the password
	 * @return string 				Returns the decrypted password string
	 */
	public function decrypt($password,$salt)
	{
		// decrypt the password
		$password 	= $this->CI->security->decrypt($password,$salt);

		// return decrypted password
		return $password;
	}
}