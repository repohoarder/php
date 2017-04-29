<?php 

/**
 * This library will update a shopping cart . remove items / update hosting plan  and send it to ubersmith
 * 
 * retrieve the order
 */
class Confirm_order
{
	/**
	 * This variable holds the Codeignitor Object
	 * 
	 * @var object
	 */
	var $CI;
	var $_affiliate_id;
	var $_funnel_version;
	var	$_partner_id;
	var $_partner_info ;
	var	$_offer_id;
	var	$_terms    = array(
			'1'  => 'monthly',
			'6'  => 'biannual',
			'12' => 'annual',
			'24' => 'biennial',
			'36' => 'triennial',
			'48' => 'quadrennial',
		);
	var	$_hosting_prices ;
	var	$_conversion_amt ;
	
	function __construct()
	{
		// load codeignitor instance
		$this->CI = &get_instance();
		$this->_funnel_version = $this->CI->session->userdata('funnel_id');
		$this->_partner_id     = $this->CI->session->userdata('partner_id');
		$this->_partner_info   = $this->CI->session->userdata('partner_info');
		
		$this->_affiliate_id   = $this->CI->session->userdata('affiliate_id');
		$this->_offer_id       = $this->CI->session->userdata('offer_id');
	}
	
	public function update_hosting_plan($post = array(),$order_id)
	{
		$return = array();
		$period = $post['num_months'];
		
		// validate pack coming in
		$resp = $this->CI->platform->post(
			'ubersmith/order/get_hosting_pack', 
			array(
				'order_id' => $order_id
			)
		);
		
		// if not found exit;
		if ( ! $resp['success']):
			// need to return error
			$return['error'] = 'Hosting Pack not found.';
			$return['success'] = false;
			$return['data'] = $post;
			return $return;
		endif;


		$hpack  = $resp['data']['hosting_pack'];

		if($hpack['pack_num'] != $post['pack']) :
			// need to return error
			$return['error'] = 'Hosting Pack not found (NMA).';
			$return['success'] = false;
			$return['data'] = $post;
			$return['hpack'] = $hpack['pack_num'];
			return $return;
			
		endif;
		
		$params 	= array(
			'funnel_id'		=> $this->_funnel_version,
			'partner_id'	=> $this->_partner_id,
			'uber_plan_id'	=> $post['plan_id'],
			'variant'		=> 'default',
		);

		if (isset($post['affiliate_id'])):
			$params['affiliate_id'] = $this->_affiliate_id;
		endif;

		if (isset($post['offer_id'])):
			$params['offer_id'] = $this->_offer_id;
		endif;

		// get prices details
		$resp =  $this->CI->platform->post('partner/pricing/get',$params);

		if ( ! $resp['success']):
			return $post;
		endif;

		$all_terms = $resp['data'];
		$terms     = array();

		foreach ($all_terms as $term):

			$terms[$term['num_months']] = $term;

		endforeach;

		ksort($terms);


		$next  = NULL;
		$found = FALSE;
		$usedterm = array();
		
		// check to make sure form term matches terms in the database
		foreach ($terms as $num_months => $term):

			if ($num_months == $period):
				// set terms for update
				$usedterm = $terms[$period];
				$found = TRUE;
				break;
			endif;

		endforeach;
		
	
		
		if ( ! $found ):
			
			// need to return error
			$return['error'] = 'Hosting Recurrance not found.';
			$return['success'] = false;
			$return['data'] = $post;
			return $return;
			
		endif;


		$pack = $hpack;
		unset($pack['cost']);
		unset($pack['prorated_total']);
		unset($pack['total_setup']);
		unset($pack['pack_num']);

		$params = array(
			'info' => array(
				'pack'.$hpack['pack_num'] => array_merge(
					$pack,
					array(
						'price'  => $usedterm['price'],
						'setup'  => $usedterm['setup_fee'],
						'period' => $usedterm['num_months']
					)
				)
			)
		);
		
		$resp = $this->CI->platform->post(
			'ubersmith/order/update/'.$order_id,
			$params
		);
		if($resp['success']) :
		$total = $this->_get_total($order_id);
		$resp = 	array(
						'success'=>true,
						'price'  => $usedterm['price'],
						'setup'  => $usedterm['setup_fee'],
						'total' => $total
					);
		else:
			$resp['error'] = 'Unable to save';
		endif;
		return $resp;
	}
	private function _get_total($order_id) {
		
		$items = $this->get_items_by_order($order_id);
		$total= 0;
		foreach($items as $key=>$value) :
			$total	+= $value['cost'];
			$total  += $value['setup'];
		endforeach;
		return number_format($total,2);
	}
	/**
	 * get hosting plans and set into public class function from the controller
	 * @return type
	 */
	public function get_hosting(){
		
		// this gets the affiliates  hosting planids
		$hosting_plan_id = $this->_get_hosting_plan_id();
		
		// return false if not found
		if ( ! $hosting_plan_id):

			return;

		endif;

		// get affiliates pricing
		$pricing = $this->_get_prices($hosting_plan_id);

		if ( ! $pricing):

			return;

		endif;


		foreach ($pricing as $key => $price_info):

			$term_name = $this->_get_term_name($price_info['num_months']);
			
			$this->_hosting_prices[$term_name] = array(
				'price'          => $price_info['price'],
				'setup_fee'      => $price_info['setup_fee'],
				'num_months'     => $price_info['num_months'],
				'trial_discount' => $price_info['trial_discount'],
				'plan_id'        => $hosting_plan_id
			);

		endforeach;


		$this->_aasort($this->_hosting_prices,'num_months');
		$this->_hosting_prices  = array_reverse($this->_hosting_prices, TRUE);
		return $this->_hosting_prices;
		
	}
	/**
	 * Get affiliates hosting plan ids
	 * @return boolean
	 */
	function _get_hosting_plan_id()
	{

		// update this function to grab the hosting uber plan id from brands_services table
		$response = $this->CI->platform->post(
			'ubersmith/package/get_hosting_plan_id',
			array(
				'affiliate_id' => $this->_affiliate_id
			)
		);
		
		if ( ! $response['success']):

			return FALSE;

		endif;

		return $response['data']['plan_id'];
	}
	/**
	 * 
	 * @param type $plan_id
	 * @param type $variant
	 * @return boolean
	 */
	private function  _get_prices($plan_id, $variant = 'default')
	{

		$response = $this->CI->platform->post(
			'partner/pricing/get',
			array(
				'partner_id'   	=> $this->_partner_id,
				'funnel_id'		=> $this->_funnel_version,
				'affiliate_id'	=> $this->_affiliate_id,
				'offer_id'     	=> $this->_offer_id,
				'uber_plan_id' 	=> $plan_id,
				'variant'      	=> $variant
			)
		);
		
		if ( ! $response['success']):

			return FALSE;

		endif;

		return $response['data'];
	}
	
	/**
	 * This function will remove packages from an order
	 * @param type $packages
	 * @param type $order_id
	 * @return boolean
	 */
	public function remove_packs($packages,$order_id) {
		
		
		if( ! $packages ) :
			return false;
		endif;
		
		$info = array();
		
		// build packages array to remove;
		
		foreach($packages as $pack) :
			$info['pack'.$pack]['plan_id'] = '';
		endforeach;
		
		if(empty($info)) :
			return false;
		endif;
		
		$post = array('info' => $info);
		
		$success = $this->CI->platform->post('ubersmith/order/update/'.$order_id,$post) ;
		//var_dump($success);
		return $success['success'];
	}
	
	/**
	 * The function name claims what it does
	 * @param type $id
	 * @return type array()
	 */
	public function get_items_by_order($id=false)
	{
		// if no id is passed, them show dummy data
		if ( ! $id) return array();
		
		// get order info
		$resp		= $this->CI->platform->post(
			'ubersmith/order/get',
			array(
				'order_id' => $id
			)
		);

		// if we were unable to grab order details, then show dummy data
		if ( ! $resp['success']):
			return array();
		endif;
		
		$order = $resp['data'];
		$items = array();

		if ( ! isset($order['info'])):
			return array();
		endif;
		
		
		$i = 0;
		
		while (TRUE):

			if ( ! isset($order['info']['pack'.$i])):
				break;
			endif;
			
			if( ! empty( $order['info']['pack'.$i]['plan_id'] )) :
				$items[$i] = $order['info']['pack'.$i];
			endif;

			$i++;

		endwhile;

		return $items;
	}
	
	/**
	 * return order info by invoice id
	 * @param type $id
	 * @return array
	 */
	public function get_items_by_invoice($id=false)
	{
		// if no id is passed, then show dummy data
		if ( ! $id)	return array();
		
		// get invoice info
		$invoice	= $this->CI->platform->post('ubersmith/invoice/get/invoice_id/'.$id,array());

		//var_dump($invoice);exit();
		
		// if we were unable to grab invoice details, then show dummy data
		if ( ! $invoice['success'])	return array();
		
		// if no credits array exists, create an empty one so page doesn't error
		if ( ! isset($invoice['data']['credits']))	$invoice['data']['credits']	= array();
		
		// return the invoice items
		return $invoice['data'];
	}
	
	/* Stolen from http://stackoverflow.com/a/2699110  not sure what it does - looks like some kind of array sorting function */

	private function _aasort (&$array, $key) {

		$sorter = array();
		$ret    = array();

	    reset($array);

	    foreach ($array as $ii => $va):
	        $sorter[$ii]=$va[$key];
	    endforeach;

	    asort($sorter);

	    foreach ($sorter as $ii => $va):
	        $ret[$ii]=$array[$ii];
	    endforeach;

	    $array = $ret;
	}
	function _get_term_name($num_months)
	{
		return $this->_terms[$num_months];
	}
}
