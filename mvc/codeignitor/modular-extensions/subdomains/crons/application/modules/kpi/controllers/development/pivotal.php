<?php 
class Pivotal extends MX_Controller
{
	/**
	 * The response to display
	 * @var array
	 */
	var $_response	= array(
		'success'	=> FALSE,
		'error' 	=> array().
		'data'		=> array()
	);

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// set data variable
		$data['output']	= $this->_response;
		
		// output
		$this->load->view('json',$data);
	}

	public function velocity()
	{
		// initialize variables
		$start 	= $this->input->post('start');
		$end 	= $this->input->post('end');

		// create post array
		$post 	= array(
			'start'	=> $start,
			'end'	=> $end
		);

		// grab velocity for date range
		$velocity 	= $this->platform->post('pivotal/velocity/get',$post);

		$this->debug->show($velocity,true);
	}

}