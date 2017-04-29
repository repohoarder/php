<?php

class Internal
{
	var $CI;

	public function __construct()
	{
		$this->CI 	= &get_instance();
	}

	public function limelight($data=array())
	{
		// load database
		$this->CI->load->database('limelight');

		// insert data
		$this->CI->db->insert('limelight.prospects',$data);

		return;
	}

	public function triangle($data=array())
	{
		// load database
		$this->CI->load->database('elevate');

		// insert data
		$this->CI->db->insert('triangle.buyers',$data);

		return;
	}
}