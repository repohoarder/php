<?php

class Score extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		// load libraries
		$this->load->library('spam');
	}

	public function index()
	{
		// check to see if data was submitted
		if ($this->input->post())	return $this->_submit();

		// set data variables
		$data	= array();

		// load view
		$this->load->view('score',$data);
	}

	private function _submit()
	{
		// grab email
		$email 	= $this->input->post('email');

		// grab score
		$score 	= $this->spam->filter($email,"long");

		// see fi we were successful or not
		if ($score->success):

			// set data variables
			$data	= array(
				'report'	=> $score->report,
				'score'		=> $score->score
			);

		else:

			// set data variables
			$data 	= array(
				'error'		=> $score->error
			);

		endif;

		// load view
		$this->load->view('score',$data);
	}
}