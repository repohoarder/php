<?php

class Inactive_clients extends MX_Controller {


	function get()
	{

		$response = $this->platform->post('ubersmith/inactive_customers/get');

		if ( ! $response['success']):

			var_dump($response['error']);
			return;

		endif;

		$this->load->view('csv', $response['data']);
		return;

	}

}