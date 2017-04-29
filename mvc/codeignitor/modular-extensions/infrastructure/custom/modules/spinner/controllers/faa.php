<?php

class Faa extends MX_Controller {

	function index() {

		$this->load->config('spinner');
		
		if ( ! $this->config->item('use_old_funnel')):

			redirect('/spinner/updated/faa');
			return;

		endif;

		redirect('/spinner/spin/faa');
		return;

		/*
		
		$available = $this->_spin();

		$data['suggestions'] = $available;

		$this->load->view('faa', $data);

	}

	function _spin()
	{

		$ip = $_SERVER['REMOTE_ADDR'];

		$resp = $this->platform->post(
			'geoip/get_record',
			array(
				'ip_address' => $ip
			)
		);

		$city  = 'local';
		$state = '';

		$this->load->config('address_validation');

		$states = $this->config->item('addr_states');
		$states = $states['US'];

		if ($resp['success'] && isset($resp['data']['record']['city'])):

			$city = strtolower($resp['data']['record']['city']);
			$city = preg_replace("/[^a-z]/", '', $city);

			$region = strtoupper($resp['data']['record']['region']);

			if (array_key_exists($region, $states)):

				$state = strtolower($states[$region]);
				$state = preg_replace("/[^a-z]/", '', $state);

			endif;

		endif;

		$available 	= $this->_get_available('com',$city);

		// attempt .nets
		if (count($available) < 3)
			$available 	= $this->_get_available('net',$city,$available);

		// attempt .orgs
		if (count($available) < 3)
			$available 	= $this->_get_available('org',$city,$available);

		// attempt .infos
		if (count($available) < 3)
			$available 	= $this->_get_available('info',$city,$available);

		// return the available array
		return $available;
	}

	function _arr_len_sort($a, $b) {

	    return strlen($a) - strlen($b);
	
	}

	private function _get_available($tld,$city,$available=array())
	{
		$chunk_loops = 0;
		$dom_loops   = 0;

		$strings = array(
			$city.'freeoffers',
			'freeoffers'.$city,
			'my'.$city.'freeoffers'
		); 

		$strings = array_unique($strings);
		$permutations = array();

		foreach ($strings as $sld):

			$permutations[] = $sld.'.'.$tld;

		endforeach;

		usort($permutations,array($this,'_arr_len_sort'));

		$perm_chunks = array_chunk($permutations,6,TRUE);

		foreach ($perm_chunks as $chunk):

			$chunk_loops++;

			$resp = $this->platform->post(
				'registrars/domain/bulk_lookup',
				array(
					'domains' => $chunk
				)
			);

			if ( ! $resp['success'] || ! isset($resp['data']['results']) || empty($resp['data']['results'])):

				continue;

			endif;


			foreach ($resp['data']['results'] as $dom):

				$dom_loops++;

				if ( ! $dom['available']):

					continue;

				endif;

				$available[$dom['domain']] = $dom['domain'];

				if (count($available) > 2):

					break;

				endif;

			endforeach;

			if (count($available) > 2):

				break;

			endif;

		endforeach;

		return $available;

		*/
	}

}

