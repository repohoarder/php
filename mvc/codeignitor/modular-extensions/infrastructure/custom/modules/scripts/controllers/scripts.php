<?php 

class Scripts extends MX_Controller
{
	public function __construct(){
		parent::__construct();
	}
	
	public function index()
	{
		echo 'test';
	}

	public function base64($string)
	{
		echo base64_encode($string);
	}

	public function base64decode($string)
	{
		echo base64_decode($string);
	}
	
	public function sha1($string)
	{
		echo SHA1($string);
	}
	
	public function md5($string)
	{
		echo md5($string);
	}

	public function encrypt($string)
	{
		$this->debug->show($this->password->generate($string));
	}

	public function decrypt($string,$salt)
	{
		echo $this->password->decrypt($string,$salt);
	}
	
	public function session()
	{
		$this->debug->show($this->session->all_userdata(),true);
	}

	public function test()
	{
		// initialize variables
		$url 	= 'http://clickbetter.com/vipaddapi.php';

		// create post array
		$post 	= array(
			'campid'	=> '103514399',
			'secretkey'	=> 'sdfa423sdg89sku',
			'affiliate'	=> '0',
			'fname'		=> 'test',
			'lname'		=> 'last',
			'cemail'	=> 'test@email.com',
			'phone'		=> '3306667777'
		);

		// submit data
		$response 	= $this->curl->post($url,$post);


		$this->debug->show($response,true);
	}
}




