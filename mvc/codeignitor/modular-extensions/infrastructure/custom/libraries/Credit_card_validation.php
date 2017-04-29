<?php

class Credit_card_validation {

	protected $_ci;

	function __construct()
	{

		$this->_ci = &get_instance();
		$this->_ci->load->config('credit_card_validation');

	}

	function get_cc_vendors()
	{

		return array_keys($this->_ci->config->item('cc_accepted_cards'));
	}

	/**
	 * Check if credit card belongs to accepted card vendor (Visa, MasterCard, etc.)
	 * @param  string  $cc Credit card number to check
	 * @return boolean     Whether card can be accepted
	 */
	function is_valid_type($cc)
	{

		$accepted_cards = $this->_ci->config->item('cc_accepted_cards');

		# Loop accepted credit cards
		foreach ($accepted_cards as $vendor => $prefixes):
			
			# Loop identifier numbers for current credit card company
			foreach ($prefixes as $prefix):
				
				# Match beginning of credit card number to card company's identifier
				if (substr($cc,0,strlen($prefix)) == $prefix):
					
					# If credit card number matches a company identifier, return true
					return TRUE;
					
				endif;
				
			endforeach;
		
		endforeach;
		
		# No valid identifier found
		return FALSE;
	}


	/**
	 * Check Credit Card number against Luhn's Algorithm.
	 * Luhn's described here: http://en.wikipedia.org/wiki/Luhn_algorithm
	 * PHP Implementation from: http://www.codediesel.com/php/luhn-algorithm-for-validating-credit-cards/
	 *
	 * @param  string $cc
	 * @return  boolean Whether number is a valid credit card number
	 */
	 function is_valid_number($cc) {
		
		# Begin Luhn's algorithm
		$sum = 0;
		$alt = false;

		for($i = strlen($cc) - 1; $i >= 0; $i--):

			if($alt):

				$temp = $cc[$i];
				$temp *= 2;
				$cc[$i] = ($temp > 9) ? $temp = $temp - 9 : $temp;

			endif;

			$sum += $cc[$i];
			$alt = !$alt;

		endfor;
		
		return $sum % 10 == 0;	
	}


	/**
	 * Whether credit card expiration is valid MM/YY combo and in the future
	 * @param  string $exp_mo Credit card expiration month
	 * @param  string $exp_yr Credit card expiration year
	 * @return boolean      Whether expiration is valid and in the future
	 */
	function is_valid_expiry($exp_mo, $exp_yr) {
		
		# Format expiration month/year to timestamp
		$expiration_date = mktime(0, 0, 0, $exp_mo, 1, $exp_yr);
		
		# For comparison, format the current month and year to timestamp
		$now_date = mktime(0, 0, 0, date('m'), 1, date('Y'));
		
		# Expiration month must be in the future; cannot be the current month or already expired
		return ($expiration_date > $now_date);
		
	}


}