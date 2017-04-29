<?php

class Generate_csv extends MX_Controller {


	function index()
	{

		$data = array();

		if ($this->input->post('headers')):

			$data['headers'] = $this->input->post('headers');

		endif;

		if ($this->input->post('rows')):

			$data['rows'] = $this->input->post('rows');

		endif;


		$this->load->view('csv', $data);

	}

}