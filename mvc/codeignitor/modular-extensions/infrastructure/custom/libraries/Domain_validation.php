<?php

class Domain_validation {
	
	protected $_ci;

	public $errors = array();

	function __construct()
	{

		$this->_ci = &get_instance();
		$this->_ci->load->config('domain_validation');

	}

	/**
	 * This emthod checks domain availability
	 * @param  [type] $sld [description]
	 * @param  [type] $tld [description]
	 * @return boolean
	 */
	public function available($sld,$tld)
	{
		// check domain availability
		$available 	= $this->_ci->platform->post('registrars/domain/is_available/'.$sld.'/'.$tld);

		// return availability response
		return ( ! $available['success'] OR ! $available['data']['availability'])
			? FALSE
			: TRUE;
	}

	/**
	 * This method grabs an array of domain name suggestions
	 * @param  [type] $sld [description]
	 * @param  [type] $tld [description]
	 * @return array
	 */
	public function suggestions($sld,$tld,$num=7)
	{
		// grab domain suggestions
		$suggestions	= $this->_ci->platform->post('registrars/domain/get_suggestions/'.$sld.'/'.$tld.'/'.$num);

		// return suggestions
		return (isset($suggestions['data']['suggestions']) AND is_array($suggestions['data']['suggestions']))
			? $suggestions['data']['suggestions']
			: FALSE;
	}

	/**
	* Remove protocol, path, and "www." from a url to get domain name
	* Partially borrowed from: http://stackoverflow.com/a/10002701
	* @param string $domain
	* @return string $domain
	*/
	function clean_domain_name($domain) {
		

		preg_match('%[^/]+\.[^/:]{2,4}%m', $domain, $matches); 

		if (empty($matches)):

			return '';

		endif;

		$domain = $matches[0];

		# Check if string begins with "www."
		if ('www.' == substr($domain,0,4)):
			
			# Remove "www."
			return substr($domain,4);
			
		endif;
		
		return $domain;
		
	}


	/**
	* Check that the type of transfer for transfer domain is valid and hasn't been tampered with
	*
	* @param string $type
	* @return boolean
	*/
	function is_valid_transfer_type($type) {
	
		return (in_array($type, $this->_ci->config->item('dom_transfer_types')));

	}




	/**
	 * Determine if domain name is valid
	 * Domain must contain ONLY alphanumeric characters and dashes
	 *
	 * @param string $sld
	 * @return boolean
	 */
	 function is_valid_domain_sld($sld) {
		
		# May only contain alphanumeric characters and hyphens
		# Domain name may not begin or end with a hyphen
		# Minimum length: 1 character
		# Regex Breakdown: 
		# One or more alphanumeric characters, followed by optional hyphens (which if included, must be followed by one or more alphanumeric characters)
		return (preg_match('/^[a-z0-9]+([\-]*[a-z0-9]+)*$/i', $sld));
		
	 }


	 /**
	  * Check if domain name is forbidden
	  * @param  string  $domain Domain name
	  * @return boolean         Whether domain is forbidden
	  */
	 function is_domain_forbidden($domain)
	 {
		
		return (in_array($domain, $this->_ci->config->item('dom_forbidden_domains')));

	 }


	 /**
	 * Currently ensures submitted domain transfer TLD is 2 or more characters
	 * Must be alphanumeric with periods; cannot begin or end with a period; cannot have more than one period in a row
	 *
	 * @param str $ext
	 * @return boolean
	 */
	 function is_valid_domain_tld($tld) {
		
		# Currently only checks if TLD is alphanumeric and that any periods are followed with more alphanumeric characters
		
		# Could compare TLD against list of current TLDs provided by IANA (http://www.iana.org/) at: http://data.iana.org/TLD/tlds-alpha-by-domain.txt
		# Idea for IANA TLD checking from: http://code.google.com/p/blogchuck/wiki/DomainsClass
		
		# All TLDs are 2 or more characters
		if (strlen($tld) < 2) :
			
			return TRUE;
			
		endif;
		
		# Stop people from entering ".com.com" domains
		$segments = explode('.',$tld);

		if (count(array_unique($segments)) < count($segments)):	

			return FALSE;		

		endif;
		
		
		return preg_match('/^[a-z0-9]+([\.][a-z0-9]+)*$/i',$tld);
		
	 }

	 function is_valid_register_domain($sld, $tld)
	 {

	 	# Ensure both domain and extension have a value
		if ($sld && $tld) :

			$valid_sld   = $this->is_valid_domain_sld($sld);
			$purchasable = $this->is_purchasable_tld($tld);

			if ( ! $valid_sld):

				$this->errors[] = 'Please enter a valid domain name';

			endif;

			if ( ! $purchasable):

				$this->errors[] = 'Please choose a domain that ends in .com, .net, .info, .biz, or .info.';

			endif;
			
			# Validate domain and extension
			return ($valid_sld && $purchasable);
			
		endif;
		
		return FALSE;	

	 }


	 /**
	  * Check that transfer domain is a valid domain
	  * @param  string  $domain Domain name
	  * @return boolean         Whether domain is valid
	  */
	 function is_valid_transfer_domain($sld, $tld)
	 {
		
		# Ensure both domain and extension have a value
		if ($sld && $tld) :
			

			$valid_sld = $this->is_valid_domain_sld($sld);
			$valid_tld = $this->is_valid_domain_tld($tld);

			if ( ! $valid_sld):

				$this->errors[] = 'Please enter a valid domain name';
			
			endif;

			if ( ! $valid_tld):

				$this->errors[] = 'Please choose a domain that ends in .com, .net, .info, .biz, or .info.';

			endif;
			

			# Validate domain and extension
			return ($valid_sld && $valid_tld);
			
		endif;
		
		return FALSE;	 
	 }

	 /**
	  * Whether domain TLD is purchasable
	  * @param  string  $tld Top-level domain name (ex., com, org, co.uk)
	  * @return boolean      Whether TLD is purchasable
	  */
	 function is_purchasable_tld($tld)
	 {

	 	if (substr($tld,0,1)=='.'):

	 		$tld = substr($tld,1);

	 	endif;

	 	return (in_array($tld, $this->_ci->config->item('dom_purchasable_tlds')));

	 }



}