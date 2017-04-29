<?php

class Pop extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->par();
	}

	public function par()
	{
		// initialize variables
		$data	= array();

		// set template layout
		$this->template->set_layout("bare");

		// set page title
		$this->template->title("Sign Up Here");

		// Add CSS
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/exit/assets/css/style.css">');
		
		if ($this->input->post())
		{
			$data['success']=$this->_submit_par();
			
			$this->template->title("Thank you for signing up!");
		}

		// build page
		$this->template->build("par",$data);
	}
	
	private function _submit_par()
	{
		/*
		$params=array();
		$params['list']='BRAINHOST';
		$params['email']=$this->input->post('email');
		
		$this->platform->post('par/par/add', $params);
            */
        $params['email']=$this->input->post('email');
		$params['list'] = 'par_signups';
                $this->platform->post('esp/add',$params);

		return true;
	}
}