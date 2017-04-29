<?php

class Financial extends MX_Controller
{
	/**
	 * The array of PArtner Information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}

	public function index()
	{
		$this->statements();
	}

	public function statements($error=FALSE)
	{
		// initialize variables
		$data	= array();

		// if form was submitted, run _submit method
		if ($this->input->post())	return $this->_submit();

		// get financial statements
		$statements 	= $this->platform->post('partner/payout/get',array('partner_id' => $this->_partner['id']));

		// make sure we got valid statement info - if not, default to empty array
		if ( ! $statements['success'] OR ! $statements['data'])	$statements['data'] 	= array();

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Financial Statements');

		// set data variables
		$data['error']		= urldecode($error);
		$data['statements']	= $statements['data'];
		
		// load view
		$this->template->build('financial/statements', $data);
	}

	public function download($payout_id=FALSE)
	{
		// make sure payout id was passes
		if ( ! $payout_id OR ! is_numeric($payout_id))
			redirect('financial/statements/There was no payout id passed.');

		// set payout_id to POST variable
		$_POST	= array(
			'payout_id'	=> $payout_id
		);

		// run submit function (to download CSV)
		return $this->_submit();
	}

	/**
	 * This method downloads a user's financial statement
	 * @return boolean
	 */
	private function _submit()
	{
		// load CSV library
		$this->load->library('csv');

		// initialize variables
		$payout_id 		= $this->input->post('payout_id');

		// get statement information
		$statements 	= $this->platform->post('partner/payout/get',array('partner_id' => $this->_partner['id'], 'payout_id' => $payout_id));

		// prompt CSV download
		$this->csv->create('financial_statement.csv',$statements['data'],TRUE);
	}
}