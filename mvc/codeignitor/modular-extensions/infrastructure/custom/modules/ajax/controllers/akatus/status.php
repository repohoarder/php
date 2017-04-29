<?php
class Status extends MX_Controller
{
	/**
	 * The array that holds the response of the API
	 * @var array
	 */
	var $_response	= array(
		'success'	=> FALSE,
		'error'		=> '',
		'data'		=> ''
	);

	public function __construct()
	{
		parent::__construct();

		// load domain validation library
		$this->load->library('domain_validation');
	}

	/**
	 * This method returns the response json encoded
	 * @return json
	 */
	public function index()
	{
		echo json_encode($this->_response);
		return;
	}
	public function getstatus(){

		$apikey= $this->input->post('key') ? $this->input->post('key') : 'BFE1A808-CFEC-4F47-9439-3A76559921BB';
        $email=  $this->input->post('email') ? $this->input->post('email') : 'priscila.brainhost@gmail.com';
		
		$tid = $this->input->post('tid');
		$current_status = $this->input->post('status');
		//$tid= '4fcb2899-b137-44e5-9601-72513e10eff8';
		$url = "http://www.akatus.com/api/v1/transacao-simplificada/$tid.json?email=$email&api_key=$apikey";
		$response = file_get_contents($url);
		$response = json_decode($response);
		echo $response->resposta->status;
		
		
	}
}
?>
