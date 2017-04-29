<?php

class Domain extends MX_Controller
{
	/**
	 * The array that holds the response of the API
	 * @var array
	 */
	var $_response	= array(
		'success'	=> FALSE,
		'error'		=> '',
		'data'		=> ''
	);

	public function __construct()
	{
		parent::__construct();

		// load domain validation library
		$this->load->library('domain_validation');
	}

	/**
	 * This method returns the response json encoded
	 * @return json
	 */
	public function index()
	{
		echo json_encode($this->_response);
		return;
	}

	/**
	 * This method checks a domain's availability
	 * @return json
	 */
	public function availability()
	{
		// initialize variables
		$type 	= $this->input->post('type');	// register, transfer or dns
		$sld 	= $this->input->post('sld');
		$tld 	= $this->input->post('tld');

		// make sure sld is valid
		if ( ! $this->domain_validation->is_valid_domain_sld($sld)):

			// set error
			$this->_response['error']	= 'Invalid Domain SLD.';

			// show response
			return $this->index();

		endif;

		// make sure tld is valid
		if ( ! $this->domain_validation->is_valid_domain_tld($tld)):

			// set error
			$this->_response['error']	= 'Invalid Domain TLD.';

			// show response
			return $this->index();

		endif;

		// if user is trying to register domain, make sure is purchasable tld
		if ($type == 'register'):

			// make sure TLD is purchasable
			if ( ! $this->domain_validation->is_purchasable_tld($tld)):

				// set error
				$this->_response['error']	= 'Not able to purchase this TLD.';

				// show response
				return $this->index();

			endif;

		endif;

		// set full domain
		$domain = $sld.'.'.$tld;

		// clean domain name
		$domain 	= $this->domain_validation->clean_domain_name($domain);

		// make sure domain isn't forbidden
		if ($this->domain_validation->is_domain_forbidden($domain)):

			// set error
			$this->_response['error']	= 'Domain Forbidden.';

			// show response
			return $this->index();

		endif;

		// check availability
		if ( ! $this->domain_validation->available($sld,$tld)):

			// set error
			$this->_response['error']	= 'Domain Not Available.';

			// show response
			return $this->index();

		endif;

		// set successful response
		$this->_response['success']	= TRUE;

		// if user made it here, then domain is available
		return $this->index();
	}

	public function suggestions()
	{
		// initialize variables
		$sld 	= $this->input->post('sld');
		$tld 	= $this->input->post('tld');

		// grab suggestions
		$suggestions	= $this->domain_validation->suggestions($sld,$tld);

		// set response
		$this->_response	= ( ! is_array($suggestions) OR empty($suggestions))
			? array('success' => FALSE, 'error'	=> $suggestions)
			: array('success' => TRUE, 	'data'	=> $suggestions);

		// show response
		return $this->index();
	}


}