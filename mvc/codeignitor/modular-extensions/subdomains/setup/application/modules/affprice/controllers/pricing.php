<?php

class Pricing extends MX_Controller {

	protected 
		$_partner_id = 1,
		$_funnel_id  = 6,
		$_locale_id  = 1,
		$_password   = 'Sinbad the Sailor!',
		$_salt       = 'p0p3y3',
		$_months     = array(
			1, 6, 12, 24, 36, 48
		),
		$_hosting_template = array(
			'trial_discount' => 0,
			'price'          => 0,
			'setup'          => 0,
			'active'         => 0,
			'service_id'     => 1
		);

	function __construct()
	{

		parent::__construct();

	}

	function _auth()
	{

		$cook = $this->input->cookie('sailor');

		if ( ! $cook):

			return FALSE;

		endif;

		$decrypted = $this->security->decrypt($cook, $this->_salt);

		return ($decrypted === $this->_password);

	}

	function _submit_auth($phrase)
	{

		$phrase = trim($phrase);

		if ($phrase !== $this->_password):

			return FALSE;

		endif;

		$params = array(
			'name'   => 'sailor',
		    'value'  => $this->security->encrypt($this->_password, $this->_salt),
		    'expire' => '2591999'
		);

		$this->input->set_cookie($params);

		return TRUE;

	}

	function index()
	{

		if ( ! $this->_auth()):

			$authed = FALSE;
			$errors = array();

			if ($this->input->post('popped')):

				$authed = $this->_submit_auth($this->input->post('phrase'));

				if ( ! $authed):

					$errors[] = 'Nope';

				endif;

			endif;

			if ( ! $authed):

				$data['errors'] = $errors;
				$this->load->view('auth', $data);
				return;

			endif;

		endif;



		$default_terms = array();

		$response = $this->platform->post(
			'partner/pricing/get_all_affiliate_default_hosting'
		);

		if ($response['success']):

			$default_terms = $response['data']['results'];

		endif;



		$errors = array();

		if ($this->input->post('form_submitted')):

			$errors = $this->_submit($this->input->post(NULL, TRUE));

		endif;

	
		$prices = $this->platform->post(
			'partner/pricing/get_affiliate_prices',
			array(
				'partner_id' => 1,
				'funnel_id'  => 1,
			)
		);

		if ( ! $prices['success']):

			show_error('Unable to get prices');
			return;

		endif;

		$prices    = $prices['data']['prices'];

		$formatted = $this->_format_prices($prices);
		$formatted = $this->_merge_default_terms($default_terms, $formatted);

		$default   = $this->_get_default_prices($formatted);
		$formatted = $this->_fill_in_blanks($default, $formatted);
		
		$data['default'] = $default;
		$data['prices']  = $formatted;
		$data['errors']  = $errors;

		$this->load->view('display', $data);

	}

	function _merge_default_terms($default_terms, $formatted)
	{

		foreach ($default_terms as $default):

			if ($default['locale_id'] != 1):

				continue;

			endif;


			if ( ! isset($formatted['affiliates'][$default['affiliate_id']]['offers'][$default['offer_id']]['plans'][1]['variants']['default']['terms'][$default['num_months']]['active']) || ! $formatted['affiliates'][$default['affiliate_id']]['offers'][$default['offer_id']]['plans'][1]['variants']['default']['terms'][$default['num_months']]['active']):

				continue;

			endif;

			$formatted['affiliates'][$default['affiliate_id']]['offers'][$default['offer_id']]['plans'][1]['variants']['default']['terms'][$default['num_months']]['default'] = 1;

		endforeach;

		return $formatted;
	}

	function _fill_in_blanks($default, $formatted)
	{

		foreach ($formatted['affiliates'] as $affkey => $aff):

			foreach ($aff['offers'] as $offkey => $off):

				foreach ($default as $plan_id => $plan):

					if ( ! isset($formatted['affiliates'][$affkey]['offers'][$offkey]['plans'][$plan_id])):

						$formatted['affiliates'][$affkey]['offers'][$offkey]['plans'][$plan_id] = $plan;

					endif;

					if (isset($plan['variants']['default']['terms'])):

						$has_default  = FALSE;
						$biggest_term = 1;

						foreach ($plan['variants']['default']['terms'] as $term => $options):

							if ( ! isset($formatted['affiliates'][$affkey]['offers'][$offkey]['plans'][$plan_id]['variants']['default']['terms'][$term])):

								$options['active'] = 0;

								$formatted['affiliates'][$affkey]['offers'][$offkey]['plans'][$plan_id]['variants']['default']['terms'][$term] = $options;

							endif;

							if ($has_default || ! $formatted['affiliates'][$affkey]['offers'][$offkey]['plans'][$plan_id]['variants']['default']['terms'][$term]['active']):

								continue;

							endif;


							$biggest_term = ($term > $biggest_term) ? $term : $biggest_term;

							if ($formatted['affiliates'][$affkey]['offers'][$offkey]['plans'][$plan_id]['variants']['default']['terms'][$term]['default']):

								$has_default = TRUE;

							endif;

						endforeach;

						if ( ! $has_default):

							$formatted['affiliates'][$affkey]['offers'][$offkey]['plans'][$plan_id]['variants']['default']['terms'][$biggest_term]['default'] = 1;

						endif;

						ksort($formatted['affiliates'][$affkey]['offers'][$offkey]['plans'][$plan_id]['variants']['default']['terms']);

					endif;

				endforeach;

			endforeach;

		endforeach; 

		return $formatted;

	}

	function _get_default_prices($formatted)
	{

		$default = array();

		if (isset($formatted['affiliates'][0]['offers'][0])):

			$default = $formatted['affiliates'][0]['offers'][0]['plans'];

		endif;

		foreach ($this->_months as $month):

			if ( ! isset($default['1']['variants']['default']['terms'][$month])):

				$default['1']['variants']['default']['terms'][$month] = $this->_hosting_template;

			endif;

		endforeach;

		return $default;
	}

	function _format_prices($prices)
	{

		$formatted = array();

		foreach ($prices as $price):

			$formatted['affiliates'][$price['affiliate_id']]['offers'][$price['offer_id']]['plans'][$price['service_id']]['name'] = $price['name'];

			$formatted['affiliates'][$price['affiliate_id']]['offers'][$price['offer_id']]['plans'][$price['service_id']]['brand_service_id'] = $price['brand_service_id'];

			$formatted['affiliates'][$price['affiliate_id']]['offers'][$price['offer_id']]['plans'][$price['service_id']]['variants'][$price['variant']]['terms'][$price['num_months']] = array(
				'trial_discount' => $price['trial_discount'],
				'price'          => $price['price'],
				'setup'          => $price['setup_fee'],
				'active'         => $price['active'],
				'default'        => 0,
				'service_id'     => $price['service_id']
			);

		endforeach;

		return $formatted;

	}


	function _submit($post)
	{
		
		$post['affiliate_id'] = intval($post['affiliate_id']);
		$post['offer_id']     = intval($post['offer_id']);

		if ($post['affiliate_id'] && $post['offer_id']):

			$info = $this->platform->post(
				'affiliate/get_affiliate_offer_info/'.$post['affiliate_id'].'/'.$post['offer_id']
			);

			if ( ! $info['success']):

				return array('This offer is not accessible by this affiliate');
			
			endif;

		endif;

		$brand_services = array();

		foreach ($post['plans'] as $brand_service_id => $plan):

			$brand_services[] = $brand_service_id;

			foreach ($plan['terms'] as $mos => $term):

				if ($plan['service_id'] == 1 && ( ! isset($term['active']) || ! $term['active'])):

					unset($post['plans'][$brand_service_id]['terms'][$mos]);

				endif;

			endforeach;

		endforeach;

		unset($post['form_submitted']);

		// clear existing prices
		$params = array(
			'partner_id'     => $this->_partner_id,
			'funnel_id'      => $this->_funnel_id,
			'affiliate_id'   => $post['affiliate_id'],
			'offer_id'       => $post['offer_id'],
			'brand_services' => $brand_services,
			'locale_id'      => $this->_locale_id,
		);

		$response = $this->platform->post(
			'partner/pricing/clear_affiliate_brand_services',
			$params
		);

		if ( ! $response['success']):

			return $response['error'];

		endif;

		unset($params['brand_services']);

		$params['variant'] = 'default'; //hardcoded, oops


		foreach ($post['plans'] as $brand_service_id => $plan):

			$plan_params = $params;
			$plan_params['brand_service_id'] = $brand_service_id;

			foreach ($plan['terms'] as $mos => $term):

				$plan_params['num_months'] = $mos;
				$plan_params['price']      = $term['price'];
				$plan_params['setup_fee']  = $term['setup'];
				$plan_params['discount']   = $term['trial_discount'];

				$response = $this->platform->post(
					'partner/pricing/add',
					$plan_params
				);

				if ( ! $response['success']):

					return $response['error'];

				endif;

			endforeach;

			if (isset($plan['default_term']) && $plan['default_term']):

				$response = $this->platform->post(
					'partner/pricing/set_affiliate_default_hosting',
					array(
						'partner_id'     => $this->_partner_id,
						'funnel_id'      => $this->_funnel_id,
						'locale_id'      => $this->_locale_id,
						'affiliate_id'   => $post['affiliate_id'],
						'offer_id'       => $post['offer_id'],
						'num_months'     => $plan['default_term']
					)
				);

				if ( ! $response['success']):

					return $response['error'];

				endif;
			
			endif;

		endforeach;

		return array();

	}

}