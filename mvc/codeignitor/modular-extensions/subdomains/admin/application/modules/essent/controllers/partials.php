<?php

class Partials extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function data()
	{
		// initialize variables
		$first 	= $this->input->post('first_name');
		$last 	= ($this->input->post('last_name'))? $this->input->post('last_name'): 'LastName';
		$email 	= $this->input->post('email');
		$date 	= $this->input->post('date');
		$ip 	= $this->input->post('ip');

		// create POST array
		$post 	= array(
			'list'	=> 'essent_partials',
			'name'	=> $first,
			'email'	=> $email,
			'meta'	=> array(
				'last_name'	=> $last,
				'date'		=> $date,
				'ip'		=> $ip
			)
		);

		// submit the data to getresponse
		$response 	= $this->platform->post('esp/add',$post);

		echo json_encode($response);
	}
}