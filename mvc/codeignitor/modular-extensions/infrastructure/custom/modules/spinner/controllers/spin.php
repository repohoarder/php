<?php

class Spin extends MX_Controller {

	private 
		$_prefix        = 'my',
		$_word_meat     = 'freeoffers',
		$_submit_url    = 'https://orders.brainhost.com/signup',
		$_new_spin_view = 'spinner/old_funnel/spinner_with_layout';


	function green()
	{

		$this->_prefix    = 'my';
		$this->_word_meat = 'greenoffers';
		
		return $this->index();
		
	}

	function freedeals()
	{

		$this->_prefix    = 'my';
		$this->_word_meat = 'freedeals';

		return $this->index();
	}

	function wfhm()
	{
		$this->_prefix = array(
			'great',
			'top',
			'best',
		);

		$this->_word_meat = array(
			'webhosting',
			'webhost',
			'hosting',
			// 'hostingservice',
		);

		return $this->index();
	}

	function faa() 
	{

		$this->_prefix    = 'my';
		$this->_word_meat = 'freeoffers';

		return $this->index();
	}

	function index() 
	{

		return $this->spinner_layout();

		/*
		$available = $this->_spin();

		$data['suggestions'] = $available;
		$data['submit_url']  = $this->_submit_url;

		$this->load->view($this->_new_spin_view, $data);
		*/

	}

	function spinner_layout()
	{

		$available = $this->_spin();

		$data['suggestions'] = $available;
		$data['submit_url']  = $this->_submit_url;

		$this->template->set_theme('brainhost_aress');
		$this->template->set_layout('aress');
		$this->template->build($this->_new_spin_view, $data);

	}


	function _spin()
	{

		$ip     = ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') ? $_SERVER['REMOTE_ADDR'] : '98.100.69.22';

		$resp   = $this->platform->post(
			'geoip/get_record',
			array(
				'ip_address' => $ip
			)
		);

		$city   = 'local';
		$state  = '';

		$this->load->config('address_validation');

		$states = $this->config->item('addr_states');
		$states = $states['US'];

		if ($resp['success'] && isset($resp['data']['record']['city'])):

			$city   = strtolower($resp['data']['record']['city']);
			$city   = preg_replace("/[^a-z]/", '', $city);
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


		if (empty($available) && $_SERVER['REMOTE_ADDR'] == '127.0.0.1'):

			$city      = 'akron';

			$meat      = $this->_word_meat;
			$prefix    = $this->_prefix;

			if (is_array($meat)):
				$meat   = $meat[0];
			endif;

			if (is_array($prefix)):
				$prefix = $prefix[0];
			endif;

			$available = array(
				$city   . $meat . '.com',
				$meat   . $city . '.com',
				$prefix . $city . $meat . '.com'
			);

		endif;

		// return the available array
		return $available;
	}

	function _arr_len_sort($a, $b) {

	    return strlen($a) - strlen($b);
	
	}

	private function _get_available($tld,$city,$available=array())
	{
		$chunk_loops  = 0;
		$dom_loops    = 0;


		$strings      = array();

		if ( ! is_array($this->_word_meat)):

			$this->_word_meat = array($this->_word_meat);

		endif;

		if ( ! is_array($this->_prefix)):

			$this->_prefix = array($this->_prefix);

		endif;

		
		foreach ($this->_word_meat as $meat):

			$strings[] = $city . $meat;
			$strings[] = $meat . $city;

			foreach ($this->_prefix as $prefix):

				$strings[] = $prefix . $city . $meat;

			endforeach;

		endforeach;



		$strings      = array_unique($strings);
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
	}

}

