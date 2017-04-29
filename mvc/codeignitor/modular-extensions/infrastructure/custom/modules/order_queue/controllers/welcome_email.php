<?php

class Welcome_email extends MX_Controller {
		
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
	
	function test($order_id)
	{

		$this->_response['errors'] = array();
		$this->_set_vars($order_id);
		return $this->send();

	}

	function send()
	{

		if (count($this->_response['errors'])):

			return $this->index();

		endif;
		
		if (isset($this->_order_info['info']['client_partner_id']) && $this->_order_info['info']['client_partner_id'] > 0)
		{
			$list	= 'clients';
			$name	= $this->_order_info['info']['first'];
			$email	= $this->_order_info['info']['email']; 
			$meta	= array(
				'company'		=> $this->_order_info['info']['client_partner_company'],
				'phone'			=> $this->_order_info['info']['client_partner_phone'],
				'domain'		=> $this->_order_info['info']['core_domain_name'],
				'uber_user'		=> $this->_order_info['info']['uber_login'],
				'uber_pass'		=> $this->_order_info['info']['uber_pass'],
				'cpanel_user'	=> $this->_order_info['info']['user_login'],
				'cpanel_pass'	=> $this->_order_info['info']['user_pass']
			);

			// error handling
			if ( ! $list OR empty($list))
				return $this->api->error_handling($this,$this->lang->line('invalid_list_name').$this->error->code($this, __DIR__,__LINE__));

			if ( ! $email OR empty($email))
				return $this->api->error_handling($this,$this->lang->line('invalid_email').$this->error->code($this, __DIR__,__LINE__));

			// default name to Friend if not set	
			$name	= ( ! $name OR empty($name))
				? 'Friend'
				: $name;

			// add contact
			//$this->_response	= $this->getresponse->add_contact($name,$email,$list,$meta);
			$this->_response = $this->platform->post(
				'esp/add',
				array(
					'list'	=> $list,
					'name'	=> $name,
					'email'	=> $email,
					'meta'	=> $meta
				)
			);
			
			// create email message
			$message 	= 				
"Hi $name,

Welcome to ".$meta['company']."! Please find your account details and login information below.";

if ($this->_order_info['info']['client_partner_id'] == "251" OR $this->_order_info['info']['client_partner_id'] == "225" OR $this->_order_info['info']['client_partner_id'] == "169"):
	$message .= "

Simple Click Profits VIP Members area Login:
http://simpleclickprofits.com/vip-members-home-page
";

endif;


$message .= "

Your Domain: ".$meta['domain']."

BILLING LOGIN: (Where you can update and view your account with us):
Billing: http://my.hostingaccountsetup.com
Username: ".$meta['uber_user']."
Password: ".$meta['uber_pass']."
	
SEVER LOGIN: (Where you can access your hosting server with us):
URL: http://".$meta['domain']."/cpanel
Username: ".$meta['cpanel_user']."
Password: ".$meta['cpanel_pass']."

FTP INFORMATION:
Host: ".$meta['domain']."
Username: ".$meta['cpanel_user']."
Password: ".$meta['cpanel_pass']."

If you have transferred your domain to us from another host, you will need to change your domain to point to our nameservers.
NAMESERVER INFORMATION:
NS1: ns1.hostingaccountsetup.com
NS2: ns2.hostingaccountsetup.com

If you are not sure how to do this yourself, simply contact your previous host and ask them to change your nameservers to the ones listed above.

If you need assistance at any time, please contact support@hostingaccountsetup.com.

Thank you again for choosing ".$meta['company']."!

Sincerely,
The Hosting Support Team";

			$headies = 
				"From: support@hostingaccountsetup.com\r\n"
				."Reply-To: support@hostingaccountsetup.com";


			//temp do a send mail until we get an getresponse account for AP
			mail
			(
				$email,
				'Important Login Information',
				$message,
				$headies
			);
			
		}
		else
		{
			$this->_response   = array(
				'success' => 1,
				'errors'  => array(),
				'data'    => array('No welcome email yet')
			);
		}

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