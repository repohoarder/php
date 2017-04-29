<?php

class Opt_out extends MX_Controller {
	
	function customer($renewal_id, $client_id, $secret_key)
	{

		$data['opt_in_link'] = site_url('renewals/process/2/'.$renewal_id.'/'.$client_id.'/'.$secret_key);

		$response = $this->platform->post('ubersmith/renewals/dom_only_get_row/'.$renewal_id);

		if ($response['success']):

			$esp_response = $this->platform->post(
				'esp/move',
				array(
					'list'  => 'refunded',
					'email' => $response['data']['row']['email'],
				)
			);

		endif;


		$this->lang->load('opt');

		$this->template->set_layout('bare');

		$this->template->title($this->lang->line('opt_out_title'));
		
		$this->template->build('opt_out', $data);

	}


}