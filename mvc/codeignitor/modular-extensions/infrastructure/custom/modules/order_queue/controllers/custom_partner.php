<?php

class Custom_Partner extends MX_Controller {
		
	private 
		$_order_id   = FALSE,
		$_order_info = FALSE,
		$_response   = array(
			'success' => 0,
			'errors'  => array(),
			'data'    => array()
		);

	public function __construct()
	{
		parent::__construct();

		error_reporting(E_ALL);

		$key	= $this->input->post('api_key');
		$id		= $this->input->post('order_id');
		
		$this->_verify_key($key);
		$this->_set_vars($id);
	}

	private function _verify_key($key)
	{
		if ($key !== 'CE2_stUswutrAbTrawrE9A86teD')
		{
			$this->_response['success']  = FALSE;
			$this->_response['errors'][] = 'Invalid API Key';
			return;

		}
	}

	public function index()
	{

		$data['response'] = $this->_response;

		$this->load->view('json', $data);

		return;

	}
	
	public function test($order_id)
	{

		$this->_response['errors'] = array();
		$this->_set_vars($order_id);
		return $this->send();

	}

	public function init()
	{
		if (count($this->_response['errors']))
		{
			return $this->index();
		}
		
		if (isset($this->_order_info['info']['client_partner_id']) && $this->_order_info['info']['client_partner_id'] > 0)
		{
			// initialize variables
			$partner_id	= $this->_order_info['info']['client_partner_id'];
			$email		= $this->_order_info['info']['email'];
			$name		= $this->_order_info['info']['first'].' '.$this->_order_info['info']['last'];

			## LANTY GETRESPONSE HACK
			$lanty 		= array(
				'251',
				'225',
				'169'
			);

			// if this is lanty's partner id, then run custom functionality
			if (in_array($partner_id,$lanty)):

				// initialize variables
				$email 		= $this->_order_info['info']['email'];
				$name 		= $this->_order_info['info']['first'];
				$domain 	= $this->_order_info['info']['core_domain_name'];
				$username 	= $this->_order_info['info']['user_login'];
				$password 	= $this->_order_info['info']['user_pass'];

				// create meta array
				$meta 		= array(
					'domain' 	=> $domain,
					'username' 	=> $username,
					'password'	=> $password
				);

				$meta 	= $this->_create_custom_meta_array($meta);

				// create array to submit to getresponse
				$data	= array(
					'method'	=> 'add_contact',
					'params'	=> array(
							'campaign'	=> 'T0p2',	// smpclikmems
							'action'	=> 'standard',
							'name'		=> $name,
							'email'		=> $email,
							'cycle_day'	=> 0,
							'ip'		=> $_SERVER['REMOTE_ADDR'],
							'customs'	=> $meta
					)
				);
				
				// grab response
				$response = $this->_getresponse('cf49cb1bedae6a264bee1eee6f7f04f6',$data);

			endif;

			## HACK
			if ($partner_id == 218):

				// generate email body
				$message 	= '
				Name: '.$name.' <br>
				Domain: '.$this->_order_info['info']['core_domain_name'].'
				FTP Host: ftp.'.$this->_order_info['info']['core_domain_name'].'
				FTP Username: '.$this->_order_info['info']['user_login'].'
				FTP Password: '.$this->_order_info['info']['user_pass'].'
				Date: '.date('Y-m-d H:i:s').'
				';

				// send mail
				mail('pentechnologyllc@gmail.com','New All Phase Hosting Sale: '.date('m/d/Y H:i'),$message);

			endif;
			## END HACK

			## HACK
			/*
			if ($partner_id == 239):

				// initialize variables
				$url 	= 'http://clickbetter.com/vipaddapi.php';

				// create post array
				$post 	= array(
					'campid'	=> '103514399',
					'secretkey'	=> 'sdfa423sdg89sku',
					'affiliate'	=> '0',
					'fname'		=> $this->_order_info['info']['first'],
					'lname'		=> $this->_order_info['info']['last'],
					'cemail'	=> $this->_order_info['info']['email'],
					'phone'		=> $this->_order_info['info']['phone']
				);

				// submit data
				$this->curl->post($url,$post);

			endif;
			*/
			## End Hack
			
			// error handling
			//if ( ! $email OR empty($email))
				//return $this->api->error_handling($this,$this->lang->line('invalid_email').$this->error->code($this, __DIR__,__LINE__));

			$this->_response = $this->platform->post(
				'partner/queue/get_all',
				array(
					'partner_id'	=> $partner_id
				)
			);
			
			foreach ($this->_response['data'] as $custom_call)
			{
				$params	= array();
				foreach ($custom_call['meta'] as $meta)
				{
					if (substr($meta['value'], 0, 1)=='%' && substr(strrev($meta['value']), 0, 1)=='%')
					{
						$params[$meta['name']]	= ${str_replace(array('%', '$'), '', $meta['value'])};
					}
					else
					{
						$params[$meta['name']]	= $meta['value'];
					}
				}
				
				$this->load->library('order_queue/'.$custom_call['method']);
				$this->_response	= $this->{$custom_call['method']}->{$custom_call['function']}($params);
			}
		}
		else
		{
			$this->_response   = array(
				'success' => 1,
				'errors'  => array(),
				'data'    => array('Not a partner')
			);
		}
		
		return $this->index();
	}
	
	public function _set_vars($id)
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

	private function _getresponse($api_key, $data = null, $method = "POST")
	{
		// require json rpc library
		require_once('jsonRPCClient.php');

		$code='';
		$url = 'http://api2.getresponse.com';//$this->vars['url'];

                // create get response client
                $client = new jsonRPCClient($url);
                // set method
                $method = $data['method'];
               
                $response 	= FALSE;

                try {

	                $response = $client->$method(
	                $api_key,
	                   $data['params']
	                );

                } catch(Exception $e) {
                	$response 	= array(
                		'success' 	=> FALSE,
                		'error'		=> 'Unable to add contact'
                	);
                }
		
		// return error if no response
		if ( ! $response)
			return array(
				'success'	=> FALSE,
				'error'		=> $code
			);
		
		
		// return success data
		return array(
			'success'	=> TRUE,
			'data' 		=> $response,
		);
		
	}

	private function _create_custom_meta_array($meta=array())
	{
		
		if(empty($meta)) return array();
		
		// initialize variables
		$newmeta = array();
		
		// iterate through meta key and values
		foreach($meta AS $key => $value):
			if(empty($value)) :
				$value= 'not specified';
			endif;
			$newmeta[]	= array(
				'name'		=> $key,
				'content'	=> $value
			);
		endforeach;
		
		return $newmeta;
	}

}