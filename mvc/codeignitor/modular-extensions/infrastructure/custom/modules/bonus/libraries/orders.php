<?php

class Orders
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
	 * This method gets an ubersmith plan id from service slug
	 * @param  boolean $slug [description]
	 * @return [type]        [description]
	 */
	public function get_plan_id($slug=FALSE)
	{
		// if no slug, return false
		if ( ! $slug)
			return FALSE;

		// grab plan id from slug
		$plan_id 		= $this->CI->platform->post('ubersmith/package/get_plan_id',array('slug' => $slug));

		// if we were unable to get plan id, then skip adding this upsell
		if ( ! $plan_id['success'] OR ! $plan_id['data'] OR ! is_numeric($plan_id['data']))	return FALSE;

		// if we made it here then we grabbed the plan id, return it
		return $plan_id['data'];
	}

	public function get_hosting_plan($affiliate_id=FALSE)
	{
		// get order info
		$pack_count	= $this->CI->platform->post('/ubersmith/package/get_hosting_plan_id',array('affiliate_id' => $affiliate_id));
		
		// make sure we got a valid response
		if ( ! $pack_count['success'] OR ! isset($pack_count['data']['plan_id']) OR ! is_numeric($pack_count['data']['plan_id'])) return FALSE;
		
		// return the pack count
		return $pack_count['data']['plan_id'];
	}

	/**
	 * This method gets the pack count for an order id
	 * @param  boolean $order_id [description]
	 * @return [type]            [description]
	 */
	public function get_order_pack_count($order_id=FALSE)
	{
		// error handling
		if ( ! $order_id OR ! is_numeric($order_id)) return FALSE;
		
		// get order info
		$pack_count	= $this->CI->platform->post('ubersmith/order/pack_count',array('order_id' => $order_id));
		
		// make sure we got a valid response
		if ( ! $pack_count['success'] OR ! is_numeric($pack_count['data'])) return FALSE;
		
		// return the pack count
		return $pack_count['data'];
	}

	/**
	 * This method gts a specific pack details from an order
	 * @param  boolean $order_id [description]
	 * @param  boolean $slug     [description]
	 * @return [type]            [description]
	 */
	public function get_order_pack($order_id=FALSE,$slug=FALSE)
	{
		// error handling
		if ( ! $order_id OR ! is_numeric($order_id))	return FALSE;
		if ( ! $slug)									return FALSE;

		// get uber plan id of the slug
		$plan_id 	= $this->get_plan_id($slug);

		// if unable to grab plan id, return FALSE
		if ( ! $plan_id)								return FALSE;

		// get order packs
		$order 		= $this->get_order_info($order_id);

		// make sure we were able to grab order info
		if ( ! $order)									return FALSE;

		// iterate through order info packs to find the one we are looking for
		for ($i=0;$i<100;$i++):

			// make sure pack exists
			if (isset($order['info']['pack'.$i]) AND ! empty($order['info']['pack'.$i])):

				// see if this is the plan_id we are looking for
				if ($order['info']['pack'.$i]['plan_id'] == $plan_id):

					// set pack count variable to use
					$order['info']['pack'.$i]['pack_count']	= $i;

					// return this pack information
					return $order['info']['pack'.$i];

				endif;

			endif;;

		endfor;

		// if we made it here, then we were unable to find the pack we were looking for
		return FALSE;
	}

	/**
	 * This method gets a specific pack's details from an order and plan id
	 * @param  boolean $order_id [description]
	 * @param  boolean $plan_id  [description]
	 * @return [type]            [description]
	 */
	public function get_order_pack_by_plan($order_id=FALSE,$plan_id=FALSE)
	{
		// error handling
		if ( ! $order_id)	return FALSE;
		if ( ! $plan_id)	return FALSE;

		// get order packs
		$order 		= $this->get_order_info($order_id);

		// make sure we were able to grab order info
		if ( ! $order)		return FALSE;

		// iterate through order info packs to find the one we are looking for
		for ($i=0;$i<100;$i++):

			// make sure pack exists
			if (isset($order['info']['pack'.$i]) AND ! empty($order['info']['pack'.$i])):

				// see if this is the plan_id we are looking for
				if ($order['info']['pack'.$i]['plan_id'] == $plan_id):

					// set pack count variable to use
					$order['info']['pack'.$i]['pack_count']	= $i;

					// return this pack information
					return $order['info']['pack'.$i];

				endif;

			endif;

		endfor;

		// if we made it here, then we were unable to find the pack we were looking for
		return FALSE;
	}

	/**
	 * This method grabs order information
	 * @param  boolean $order_id [description]
	 * @return [type]            [description]
	 */
	public function get_order_info($order_id=FALSE)
	{
		// error handling
		if ( ! $order_id OR ! is_numeric($order_id))	return FALSE;

		// get order info
		$order 		= $this->CI->platform->post('ubersmith/order/get',array('order_id' => $order_id));

		// make sure we were able to grab the order
		if ( ! $order['success'] OR empty($order['data']))	return FALSE;

		return $order['data'];
	}

	/**
	 * This method gets a hosting pack count from an order
	 * @param  [type] $post [description]
	 * @return [type]       [description]
	 */
	public function get_hosting_pack_count($post)
	{
		// grab hosting pack id
		$hosting_plan_id 	= $this->CI->orders->get_hosting_plan($post['affiliate_id']);

		// determine if 'client' or 'order' funnel
		if ($post['funnel_type'] == 'order'):

			// grab the hosting package by plan
			$package 	= $this->CI->orders->get_order_pack_by_plan($post['_id'],$hosting_plan_id);

			// make sure we were able to grab the pack count
			if (is_array($package) AND isset($package['pack_count'])):

				return $package['pack_count'];

			endif;

		endif;

		return FALSE;
	}
}