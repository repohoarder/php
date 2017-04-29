<?php

class Prices
{
	/**
	 * This variable holds the Codeignitor Object
	 * 
	 * @var object
	 */
	var $CI;
	
	function __construct()
	{
		// load codeignitor instance
		$this->CI = &get_instance();
	}

	/**
	 * This method gets a services price
	 * @param  string  $funnel_id    [description]
	 * @param  string  $partner_id   [description]
	 * @param  boolean $affiliate_id [description]
	 * @param  boolean $offer_id     [description]
	 * @param  boolean $uber_plan_id [description]
	 * @param  boolean $variant      [description]
	 * @param  boolean $term         [description]
	 * @return [type]                [description]
	 */
	public function get($funnel_id='1',$partner_id='1',$affiliate_id=FALSE,$offer_id=FALSE,$uber_plan_id=FALSE,$variant=FALSE,$term=FALSE)
	{
		// error handling
		if ( ! $partner_id OR ! is_numeric($partner_id))		return FALSE;	// can't get prices if no partner is passed
		if ( ! $uber_plan_id OR ! is_numeric($uber_plan_id))	return FALSE;	// can't get price of a service if a plan isn't apssed

		// initialize variables
		$affiliate_id	= ( ! $affiliate_id OR ! is_numeric($affiliate_id))? 	0: $affiliate_id;		// default affiliate id to 0 if not set
		$offer_id		= ( ! $offer_id OR ! is_numeric($offer_id))?			0: $offer_id;			// default offer id to 0 if not passed
		$variant 		= ( ! $variant OR empty($variant))?						'default': $variant;	// defualt variant to 'default'
		$term			= ( ! $term OR ! is_numeric($term))?					0: $term;				// default term to 0 if not passed

		// set post array
		$post 	= array(
			'funnel_id'		=> $funnel_id,
			'partner_id'	=> $partner_id,
			'affiliate_id'	=> $affiliate_id,
			'offer_id'		=> $offer_id,
			'uber_plan_id'	=> $uber_plan_id,
			'variant'		=> $variant,
			'num_months'	=> $term
		);

		// get prices details
		$prices 	= $this->CI->platform->post('partner/pricing/get',$post);
		
		// if nto successful, return FALSE
		if ( ! $prices['success'])	return FALSE;

		// if unable to grab prices, then return false
		if ( ! $prices['data'][0]['price'] OR ! $prices['data'][0]['setup_fee'])	return FALSE;

		// return total price
		return $prices['data'][0]['price'] * 1;
	}
}