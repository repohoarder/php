<?php

class Lead extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add()
	{
		// initialize variables
		$database 		= $this->input->post('database');
		$table 			= $this->input->post('table');
		$affiliate_id	= $this->input->post('affiliate_id');
		$offer_id		= $this->input->post('offer_id');
		$url 			= $this->input->post('url');
		$post 			= $this->input->post('post');
		$response 		= $this->input->post('response');

		// create POST array
		$post 	= array(
			'database'	=> $database,
			'table'		=> $table,
			'data'		=> array(
				'affiliate_id'	=> $affiliate_id,
				'offer_id'		=> $offer_id,
				'url'			=> $url,
				'post'			=> $post,
				'response'		=> $response,
				'added_by'		=> 'http://admin.brainhost.com/api/lead/add',
				'date_added'	=> date('Y-m-d H:i:s')
			)
		);

		// submit the data to getresponse
		$response 	= $this->platform->post('database/insert',$post);

		echo json_encode($response);
	}
}