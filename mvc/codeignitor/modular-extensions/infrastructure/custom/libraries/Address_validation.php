<?php

class Address_validation {

	protected $_ci;

	function __construct()
	{

		$this->_ci = &get_instance();
		$this->_ci->load->config('address_validation');

	}

	/**
	 * Validate zipcode based on country
	 * @param  string  $zip     Zipcode or Postal Code
	 * @param  string  $country Country 
	 * @return boolean          Whether zipcode is valid for the given country
	 */
	function is_valid_zipcode($zip, $country = 'US')
	{

		if ( ! $this->is_valid_country($country)):

			return FALSE;

		endif;


		$zip_req_countries = $this->_ci->config->item('addr_req_zip_countries');

		if ( ! array_key_exists($country, $zip_req_countries)):

			return TRUE;

		endif;


		# Check if a zipcode validation pattern matching function exists for selected country
		$country_function = 'is_valid_' . strtoupper($country) . '_zipcode';
	
		if (is_callable(array($this, $country_function))):
		
			# Run zipcode against country's validation function and return result
			return $this->$country_function($zip);
		
		endif;
		
		# Default to true; some countries don't require zipcode
		return TRUE;

	}
	
	/**
	 * United States zipcode validation
	 * May be 5 digit or 9 digit zipcode with hyphen (12345 or 12345-1234)
	 * Borrowed from: http://roshanbh.com.np/2008/03/usa-zip-code-format-validation-php.html
	 * @param  string  $zip Zipcode
	 * @return boolean      Whether zipcode is valid for US
	 */
	function is_valid_US_zipcode($zip)
	{

		return (boolean) preg_match("/^([0-9]{5})(-[0-9]{4})?$/i",$zip);

	}
	
	/**
	 * Canadian postal code validation
	 * Borrowed from: http://roshanbh.com.np/2008/03/canda-postal-code-validation-php.html
	 * @param  string  $postal Canadian postal code
	 * @return boolean         Whethr postal code is valid
	 */
	function is_valid_CA_zipcode($postal)
	{

		$postal = preg_replace('/\s+/', '', $postal);
		
		return (boolean) preg_match("/^([a-ceghj-npr-tv-z]){1}[0-9]{1}[a-ceghj-npr-tv-z]{1}[0-9]{1}[a-ceghj-npr-tv-z]{1}[0-9]{1}$/i",$postal);

	}

	/**
	 * Checks if country exists and if we can do business there
	 * @param  string  $country Country
	 * @return boolean          Whether country exists and is usable
	 */
	function is_valid_country($country)
	{

		$countries = $this->_ci->config->item('addr_countries');

		return ($country && array_key_exists($country, $countries));

	}

	/**
	 * Validates that the given state belongs to the given country
	 * @param  string  $state   State or Province ISO code
	 * @param  string  $country Country ISO code
	 * @return boolean          Whether state exists in given country
	 */
	function is_valid_state($state, $country = 'US')
	{

		if ( ! $this->is_valid_country($country)):

			return FALSE;

		endif;

		$state_req_countries = $this->_ci->config->item('addr_req_state_countries');


		if ( ! array_key_exists($country, $state_req_countries)):
			
			return TRUE;
			
		endif;


		$country_states = $this->_ci->config->item('addr_states');

		if ( ! array_key_exists($country, $country_states)):

			return FALSE;

		endif;

		$accepted_states = $country_states[$country];

		if ($country == 'GB'):
			
			$accepted_states = array();
			
			foreach ($country_states[$country] as $territory => $states):
				
				$accepted_states = array_merge($accepted_states, $country_states[$country][$territory]);
				
			endforeach;
			
		endif;

		return array_key_exists($state, $accepted_states);

	}



	/**
	 * Clean submitted phone numbers.
	 * Numeric. Allow a beginning plus sign (+) for international numbers.
	 * Allow one 'x' character to designate extension
	 *
	 * @param string $phone
	 * @return string $phone
	 */
	function clean_phone_number($phone) {
		
		if ( ! $phone):

			return $phone;

		endif;

		
		# Strip all non-numeric, plus sign, or "x" characters
		$phone = preg_replace('/[^0-9\+x]/','',$phone);

		if ( ! $phone):

			return $phone;

		endif;
		
		# Remove every plus sign unless it begins the number
		$phone = (string) $phone[0] . str_replace('+','', substr($phone,1));
		
		# Get the location of the first "x" that occurs in the phone number
		$x_location = strpos($phone,'x');
		
		# Test if "x" characters are present
		if ($x_location !== FALSE) :
			
			# Remove all "x" characters except the first occurrence
			$phone = substr($phone,0,$x_location+1) . str_replace('x','', substr($phone,$x_location));
			
		endif;
		
		
		return $phone;
	 }



}