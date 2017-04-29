<?php 

## TODO: Update this class to pull partner, affiliate and offer id from the order, and not the session

class Completed extends MX_Controller
{
	/**
	 * Test Invoice Variables
	 * 
	 * @var _test_invoice_id
	 * @var _test_invoice_amount
	 * @var _test_invoice_items
	 */
	var $_test_invoice_id;
	var $_test_invoice_items;
	var $_test_invoice_credits;
	var $_test_client_id;

	var $_partner_id;
	var $_funnel_version;
	var $_affiliate_id;
	var $_offer_id;
	var $_type;
	var $_id;
	var $_partner_info;
	var $_partner;
	
	public function __construct()
	{
		parent::__construct();
		
		// auto load billing config
		$this->config->load('billing');
		
		// load language file
		$this->lang->load('billing',$this->session->userdata('_language'));
		$this->lang->load('billing_paypal',$this->session->userdata('_language'));
		$this->lang->load('footer',$this->session->userdata('_language'));
		
		// set test invoice variables (for developers to view the page)
		$this->_test_invoice_id      = $this->config->item('test_invoice_id');
		$this->_test_invoice_items   = $this->config->item('test_invoice_items');
		$this->_test_invoice_credits = $this->config->item('test_invoice_credits');
		$this->_test_client_id       = $this->config->item('test_client_id');
		
		
		
		// set funnel version
		$this->_funnel_version       = $this->version->get(); 
		
		// set partner id
		$this->_partner_id           = $this->session->userdata('partner_id');
		$this->_affiliate_id         = $this->session->userdata('affiliate_id');
		$this->_offer_id             = $this->session->userdata('offer_id');
		$this->_partner_info		 = $this->session->userdata('partner_info');
	}
	
	public function sale($type='',$id=false,$slug='completed')
	{
		
		$this->_type = $type;
		$this->_id   = $id;

		// initialize variables
		$data	= array();
		$items	= array();					// This variable will hold all items to display on the page
		$method	= $type == 'demo' ? '_get_items_by_' :'_get_items_by_'.$type;	// This variable is the method that will need ran depending on the $type
		
		
		$this->tracking->page_hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => $slug
			)
		);
		$brand_id  = is_null($this->_partner_info['brand']) ? '1' : $this->_partner_info['brand'];


		// grab custom content
		$data['content_custom']	= $this->_custom_content($brand_id);
		/*
		// track hit
		$this->tracking->hit(
			$this->session->userdata('session_id'),			// unique session id
			$this->_partner_id,								// partner id
			$this->_funnel_version,							// funnel version id
			$slug,
			$this->_affiliate_id,
			$this->_offer_id									// page slug
		);
		*/

		// grab items based on order type
		$items		= $this->$method($id);
		
		// grab email address
		$email		= $this->_grab_email_from_client_id($items['clientid']);
		
		// determine if invoice has a build type
		$build_type	= $this->_client_has_build_type($items['clientid']);
		
		// determine if user has charity - so we can display image/text on page
		$charity 	= $this->_client_has_charity($items['clientid']);

		// get client info
		$client 	= $this->_get_client_info($items['clientid']);

		/*
		$allowed_sessions = array(
			'session_id',
			'last_activity',
			'ip_address',
			'user_agent',
			'visitor_id'
		);

		$current_sessions = $this->session->all_userdata();

		if (is_array($current_sessions) && ! empty($current_sessions)):

			$current_sessions = array_diff_key($current_sessions, array_flip($allowed_sessions));

			foreach ($current_sessions as $key => $value):

				$this->session->unset_userdata($key);

			endforeach;

		endif;
		*/
		

		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title($this->lang->line('billing_pp_title'));
		
		// append the CSS file
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/completed.css" type="text/css" />');
		
		// set needed data variables
		$data['items']             = $items;
		$data['email']             = $email;
		$data['build_type']        = $build_type;
		$data['period']            = $this->config->item('periods');
		$data['charity']           = $charity;
		$data['desktop_lightning'] = ($this->_partner_id == 1)? TRUE: FALSE;		
		$data['pixel_data']        = $this->_get_pixels();
		$data['partner_id']			= $this->_partner_id;
		$data['client']				= $client;
		
		$data['show_button']       = TRUE;

		## BRADLEY WILL HACK!!!!!!!!!!!!!!!!!!!!!!!!!!!
		if ($this->_affiliate_id == '99627')
		{
			$data['show_button'] = FALSE;
		}


		if ($this->session->userdata('oneclick_source')):

			$data['show_button'] = FALSE;
		
		endif;



		$this->load->config('sales_funnel');
		$data['descriptor'] = $this->config->item('descriptor');
		
		// load view
		$this->template->build('completed/'.$slug, $data);
	}

	function test_pixels()
	{

		$this->_id           = 349861;
		$this->_type         = 'order';
		$this->_affiliate_id = 102476;
		$this->_offer_id     = 3444;
		$this->_partner_id   = 1;

		echo '<textarea>'.json_encode($this->_get_pixels()).'</textarea>';
			
	}

	function _get_pixels()
	{	
		if($this->_type != 'demo') :
			if ($this->_type != 'order' || ! $this->_id):

				return array();

			endif;
		endif;

		$params = array(
			'type'         => 'thank_you',
			'partner_id'   => $this->_partner_id,
			'affiliate_id' => $this->_affiliate_id,
			'offer_id'     => $this->_offer_id
		);

		$resp = $this->platform->post(
			'partner/pixel/get',
			$params
		);

		$pixels = FALSE;

		if ($resp['success']):

			$pixels = $resp['data']['pixels'];

		endif;

		
		if ( ! $pixels):

			return array();

		endif;

		// this demo type is for the initialize demo to return and fire pixels for jessie sabica
		if( $this->_type == 'demo') :
			
			$return = array();
			foreach ($pixels as $key => $val):

				$return[$key] = $val['pixel'];

			endforeach;	
			return $return;
		endif;
		
		$resp = $this->platform->post(
			'ubersmith/order/get',
			array(
				'order_id' => $this->_id
			)
		);

		if ( ! $resp['success']):

			return array();

		endif;

		$host_resp = $this->platform->post(
			'ubersmith/order/get_hosting_pack',
			array(
				'order_id' => $this->_id
			)
		);

		if ( ! $host_resp['success']):

			return;

		endif;

		$hosting_pack = $host_resp['data']['hosting_pack'];
		
		$price        = $hosting_pack['price'] + $hosting_pack['setup'];
		
		$order        = $resp['data'];

		$find_replace = array(
			'[first_name]'           	=> $order['info']['first'],
			'[last_name]'            	=> $order['info']['last'],
			'[phone]'                	=> $order['info']['phone'],
			'[email]'                	=> $order['info']['email'],
			'[order_id]'             	=> $this->_id,
			'[passback]'             	=> $this->input->cookie('passback'),
			'[total]'                	=> $price,
			'[commissionable_total]' 	=> $price * .75,
			'[commission_junction]'		=> $this->_commission_junction($order)
		);

		$return = array();

		foreach ($pixels as $key => $val):

			$return[$key] = str_replace(array_keys($find_replace),$find_replace,trim($val['pixel']));

		endforeach;

		return $return;

	}

	private function _commission_junction($order=FALSE)
	{
		// initialize variables
		$pixel 			= '';
		$domain_packs	= array();	// this array will hold packid's for all domains on this order
		$counter 		= 1;

		// get domain packs
		$domains 	= $this->platform->post('ubersmith/client/domains',array('client_id' => $order['client_id']));

		// if unable to grab domains from order, set to empty array
		if ( ! $domains['success'])	$domains['data']	= array();

		// iterate all domains
		foreach ($domains['data'] AS $key => $value):

			// add packid to domain_packs array
			$domain_packs[] 	= $value['plan_id'];

		endforeach;

		// add all packs (except domain) to this pixel
		for ($i=0; $i<100; $i++):

			// make sure this pack exists on this order - if not, then we have completed iterating through all packs on order
			if ( ! isset($order['info']['pack'.$i])) break;

			// initialize variables
			$plan_id 	= $order['info']['pack'.$i]['plan_id'];
			$amount 	= $order['info']['pack'.$i]['cost'];

			// make sure this pack isn't a domain pack
			if ( ! in_array($plan_id,$domain_packs)):

				// append to pixel
				$pixel 	.= '&ITEM'.$counter.'='.$plan_id.'&AMT'.$counter.'='.$amount.'&QTY'.$counter.'=1';
				
				// increment counter
				$counter++;

			else:	// this is a domain pack

				// append to pixel - make $ amount == 0.00
				$pixel 	.= '&ITEM'.$counter.'='.$plan_id.'&AMT'.$counter.'=0.00&QTY'.$counter.'=1';
				
				// increment counter
				$counter++;

			endif;	// end making sure this isn't a domain pack

			// if this pack has a setup fee, add another item
			if (isset($order['info']['pack'.$i]['setup']) AND $order['info']['pack'.$i]['setup'] > 0.00):

				// append to pixel - make $ amount == 0.00
				$pixel 	.= '&ITEM'.$counter.'=999'.'&AMT'.$counter.'=0.00&QTY'.$counter.'=1';
				
				// increment counter
				$counter++;

			endif;

		endfor;

		// return the pixel
		return $pixel;
	}
	
	/**
	 * Get Items By
	 * 
	 * This method is used when we are just testing the thank you page.  It will return an array of dummy content for display.
	 * 
	 * 
	 */
	private function _get_items_by_($id=false)
	{
		// create dummy items array
		$items	= array(
			'invid'				=> $this->_test_invoice_id,
			'current_packs'		=> $this->_test_invoice_items,
			'credits'			=> $this->_test_invoice_credits,
			'clientid'			=> $this->_test_client_id
		);
		
		return $items;
	}
	
	/**
	 * Get Items By Order ID
	 * 
	 * This method will grab all items on an order and return them in an array.
	 */
	private function _get_items_by_order($id=false)
	{
		// if no id is passed, them show dummy data
		if ( ! $id) return $this->_get_items_by_();
		
		// get order info
		$order		= $this->platform->post('ubersmith/order/get',array('order_id' => $id));
		
		// if we were unable to grab order details, then show dummy data
		if ( ! $order['success'])	return $this->_get_items_by_();
		
		// get invoice id from order
		$invoice_id	= $order['data']['info']['invid'];
		
		// grab items from invoice
		return $this->_get_items_by_invoice($invoice_id);
	}
	
	/**
	 * Gets Items by Invoice ID
	 * 
	 * This method will grab all items associated with an invoice
	 */
	private function _get_items_by_invoice($id=false)
	{
		// if no id is passed, then show dummy data
		if ( ! $id)	return $this->get_items_by_();
		
		// get invoice info
		$invoice	= $this->platform->post('ubersmith/invoice/get/invoice_id/'.$id,array());
		
		// if we were unable to grab invoice details, then show dummy data
		if ( ! $invoice['success'])	return $this->_get_items_by_();
		
		// if no credits array exists, create an empty one so page doesn't error
		if ( ! isset($invoice['data']['credits']))	$invoice['data']['credits']	= array();
		
		// return the invoice items
		return $invoice['data'];
	}
	
	/**
	 * Get Email By Invoice
	 * 
	 * This method gets the email address associated with an invoice 
	 */
	private function _grab_email_from_client_id($client_id=FALSE)
	{
		// error handling
		if ( ! $client_id OR ! is_numeric($client_id) OR $client_id == $this->_test_client_id) return 'test@test.com';
		
		// initialize variables
		$client_info	= $this->platform->post('ubersmith/client/get/',array('name' => 'id', 'client_id' => $client_id));
		
		// make sure we got valid data back
		if ( ! $client_info['success']) return 'test@test.com';
		
		// set email
		$email			= $client_info['data']['email'];
		
		return $email;
	}
	
	/**
	 * Has Build Type
	 * 
	 * This method determines if an invoice has a build type associated with the client
	 */
	private function _client_has_build_type($client_id=FALSE)
	{
		// initialize variables
		$mcsd	= TRUE;
		
		// error handling
		if ( ! $client_id OR ! is_numeric($client_id) OR $client_id == $this->_test_client_id) return $mcsd;
		
		// get client info
		$client_info	= $this->platform->post('ubersmith/client/get/',array('name' => 'id', 'client_id' => $client_id));
		
		// make sure we got valid data back
		if ( ! $client_info['success']) return FALSE;
		
		// see if user has a build - if so, we need to return TRUE
		if (empty($client_info['data']['metadata']['wordpress_installation']))	$mcsd	= FALSE;
		
		return $mcsd;
	}
	
	/**
	 * This method determines if client has charity package
	 * @param  boolean $client_id [description]
	 * @return [type]             [description]
	 */
	private function _client_has_charity($client_id=FALSE)
	{
		// initialize variables
		$charity 	= FALSE;

		// error handling
		if ( ! $client_id)
			return FALSE;

		// see if this is just the test user (if so, show charity)
		if ($client_id == $this->_test_client_id) 
			return TRUE;

		// determine if this client has charity package
		$has 		= $this->platform->post('charity/has/dollar',array('client_id' => $client_id));

		// return boolean
		return ($has['success'] AND $has['data']);
	}

	/**
	 * Format Invoice
	 * 
	 * This function formats the invoice array into a format that the thank you pages will be looking for
	 * 
	 * @deprecated
	 */
	private function _format_invoice($invoice)
	{
		$this->debug->show($invoice,true);
		
		// initialize variables
		$items	= array();
		
		// iterate current packs
		foreach ($invoice['current_packs'] AS $service):
		
		endforeach;
	}

	private function _custom_content($brand_id)
	{
		$cthy = $this->platform->post('aff_custom_content/content/get',
				array(
					'partner_id' => $this->_partner_id,
					'slug'   => 'completed',
					'brand_id' => $brand_id,
					'affiliate_id'	=> $this->_affiliate_id
				)
				);
		$data['content_custom'] = '';
		if($cthy['success']) :
			if( isset($cthy['data']['content'])) :
				$data['content_custom'] = $cthy['data']['content'];
			endif;
		endif;

		return $data['content_custom'];
	}
	
	private function _get_client_info($client_id)
	{
		// get client info from platform
		$client 	= $this->platform->post('ubersmith/client/get',array('name' => 'id', 'client_id' => $client_id));

		// return client info
		return $client['data'];
	}
}


