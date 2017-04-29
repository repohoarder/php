<?php

/**
 *
 * @deprecated Never finished; idea aborted
 */

class Prices {

	private $_ci, $_country = 'US', $_tld_prices;

	function __construct()
	{
		$this->_ci = &get_instance();

		$this->_ci->load->config('prices_domains');

		$this->_tld_prices = $this->_ci->config->item('prices_domains');
	}

	public function get_tld_prices($tld, $formatted = FALSE)
	{
		
		$prices = $this->_tld_prices[$this->_country][$tld];

		if ( ! $formatted):

			return $prices;

		endif;

		$format_func = '_format_price_'.$this->_country;


		if ( ! is_array($prices)):

			return $this->$format_func($prices);

		endif;

		foreach ($prices as $key=>$price):

			$prices[$key] = $this->$format_func($price);

		endforeach;

		return $prices;

	}

	public function format_price($price)
	{

		$func = '_format_price_'.$this->_country;
		return $this->$func($price);
	}


	private function _format_price_US($price) 
	{

		return '$'.number_format($price,2);

	}

}
	