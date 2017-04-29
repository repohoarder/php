<?php

class Support_tickets extends MX_Controller {
		
	private 
		$_order_id   = FALSE,
		$_order_info = FALSE,
		$_response   = array(
			'success' => 0,
			'errors'  => array(),
			'data'    => array()
		);

	function __construct()
	{

		parent::__construct();

		$key = $this->input->post('api_key');
		$this->_verify_key($key);

		$id = $this->input->post('order_id');
		$this->_set_vars($id);

	}

	function _verify_key($key)
	{

		if ($key !== 'CE2_stUswutrAbTrawrE9A86teD'):

			$this->_response['success']  = FALSE;
			$this->_response['errors'][] = 'Invalid API Key';
			return;

		endif;
	}

	function index()
	{

		$data['response'] = $this->_response;

		$this->load->view('json', $data);

		return;

	}

	function test($order_id, $type = 'build')
	{

		$this->_response['errors'] = array();
		$this->_set_vars($order_id);

		if ($type=='build'):

			return $this->add_build_ticket();

		endif;

		return $this->add_fulfillment_ticket();

	}


	function add_build_ticket()
	{

		if (count($this->_response['errors'])):

			return $this->index();

		endif;

		$queue = $this->_order_info['order_queue_id'];
		
		$resp  = $this->platform->post(
			'ubersmith/order_queue/is_partner_queue',
			array(
				'queue_id' => $queue
			)
		);

		$is_partner = ($resp['success'] && $resp['data']['is_partner']);

		if ($is_partner):

			return $this->_add_partner_build_ticket();

		endif;

		return $this->_add_client_build_ticket();

	}

	function _add_partner_build_ticket()
	{

		$client_id = $this->_order_info['client_id'];		
		$domains   = $this->_order_info['info']['domains'];
		$core      = array_shift($domains);
		
		$order_id  = $this->_order_id;
		
		$domain    = $core['domain'];
		$username  = $core['username'];
		$password  = $core['password'];

		$body      = "
Partner build needed.

Client: $client_id (http://my.hostingaccountsetup.com/admin/clientmgr/client_view.php?clientid=$client_id)
Order: $order_id (http://my.hostingaccountsetup.com/admin/ordermgr/order_view.php?order_id=$order_id)

Domain: $domain
Username: $username
Password: $password
		";

		$params = array(
			'subject' => 'Partner website needed (C#'.$client_id.', O#'.$this->_order_id.')',
			'queue'   => '2',
			'body'    => $body
		);

		$resp = $this->platform->post(
			'ubersmith/ticket/add',
			$params
		);

		if ($resp['success']):

			$this->_response   = array(
				'success' => 1,
				'errors'  => array(),
				'data'    => array('ticket_id' => $resp['data'])
			);

			return $this->index();

		endif;

		$this->_response   = array(
			'success' => 0,
			'errors'  => array('Unable to add ticket. Response: '.json_encode($resp)),
			'data'    => array()
		);

		return $this->index();

	}

	function _add_client_build_ticket()
	{

		$this->_response   = array(
			'success' => 1,
			'errors'  => array(),
			'data'    => array('No tickets configured')
		);

		return $this->index();

	}

	function add_fulfillment_ticket()
	{

		if (count($this->_response['errors'])):

			return $this->index();

		endif;

		$client_id = $this->_order_info['client_id'];		
		$domains   = $this->_order_info['info']['domains'];
		$core      = array_shift($domains);
		
		$order_id  = $this->_order_id;
		
		$domain    = $core['domain'];
		$username  = $core['username'];
		$password  = $core['password'];


		$has_fulfillment = array(
			'traffic'       => 49,
			'addon_domains' => 48,
			'weblock'       => 13,
		);

		$upsells         = array();

		for($i=0;$i<30;$i++):

			if ( ! isset($this->_order_info['info']['pack'.$i])):

				break;

			endif;

			$pack = $this->_order_info['info']['pack'.$i];

			if (in_array($pack['plan_id'], $has_fulfillment)):

				$upsells[] = $pack['title'].' (http://my.hostingaccountsetup.com/admin/clientmgr/client_service_details.php?packid='.$pack['packid'].')';

			endif;

		endfor;

		if (empty($upsells)):

			$this->_response   = array(
				'success' => 1,
				'errors'  => array(),
				'data'    => array('No fulfillment')
			);
			
			return $this->index();

		endif;

		$body      = "
Upsell fulfillment needed.

Client: $client_id (http://my.hostingaccountsetup.com/admin/clientmgr/client_view.php?clientid=$client_id)
Order: $order_id (http://my.hostingaccountsetup.com/admin/ordermgr/order_view.php?order_id=$order_id)

Upsells:
	".implode(" \n\t",$upsells)."
";

		$params = array(
			'subject' => 'Fulfillment needed (C#'.$client_id.', O#'.$this->_order_id.')',
			'queue'   => '3',
			'body'    => $body
		);

		


		$resp = $this->platform->post(
			'ubersmith/ticket/add',
			$params
		);

		if ($resp['success']):

			$this->_response   = array(
				'success' => 1,
				'errors'  => array(),
				'data'    => array('ticket_id' => $resp['data'])
			);

			return $this->index();

		endif;

		$this->_response   = array(
			'success' => 0,
			'errors'  => array('Unable to add ticket. Response: '.json_encode($resp)),
			'data'    => array()
		);

		return $this->index();

	}


	function _set_vars($id)
	{

		if ($id):

			$this->_order_id = $id;

		endif;

		if ( ! $this->_order_id):

			$this->_response['success']  = FALSE;
			$this->_response['errors'][] = 'Invalid Order ID';
			return;

		endif;

		$o_info = $this->platform->post(
			'ubersmith/order/get',
			array(
				'order_id' => $this->_order_id
			)
		);

		if ( ! $o_info['success']):

			$this->_response['success']  = FALSE;
			$this->_response['errors'][] = 'Unable to retrieve order info';
			return;

		endif;

		$this->_order_info = $o_info['data'];

	}


}