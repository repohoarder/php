<?php 

class Cdns
{
	public function __construct()
	{
		$this->CI 	= &get_instance();

		// load cdn config
		$this->CI->load->config('cdn');

		// set FTP details
		$this->_server	= $this->CI->config->item('server');
	}

	public function add($file,$path='')
	{
		$this->_ftp('');
	}

	private function _ftp($path,$file)
	{
		// connect to FTP
		$conn 	= ftp_connect($this->_server['host']) or die(json_encode(array('success' => FALSE, 'error' => 'Unable to connect via FTP.')));

		// log in
		ftp_login($this->_server['host'],$this->_server['user'],$this->_server['pass']) or die(json_encode(array('success' => FALSE, 'error' => 'Unable to log in via FTP.')));

		// see if directory exists
		

		// if doesn't exist, create it
		
		// upload file
		ftp_put() or die(json_encode(array('success' => FALSE, 'error' => 'Unable to upload file via FTP.')));

		// return CDN URL
		return $this->_server['cdn_url'].$path;


	}
}