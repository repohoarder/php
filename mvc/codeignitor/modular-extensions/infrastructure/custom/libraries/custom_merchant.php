<?php

class Custom_merchant
{
            
	public function __construct()
	{
		// load codeignitor instance
		$this->CI = &get_instance();
     
				
	}
	public function process(){
		
			$order_id = $this->CI->session->userdata('_id');
			
			$order = $this->CI->platform->post(
				'ubersmith/order/get',
				array(
					'order_id' => $order_id
				)
			);
			$order_info = $order['data'];
			
			//echo $order_info['client_id'];
			// generate an invoice
			$invoice = $this->CI->platform->post('ubersmith/invoice/generate/'.$order_info['client_id']);
			
		
			// return if the invoice is not successful
			if( ! $invoice['success'] ) :
				return false;
			endif;
			
			// if invoice id is not set return false
			if( ! isset($invoice['data']['invid'])) :
				return false;
			endif;
			
			// set invoice id
			$invoice_id = $invoice['data']['invid'];
			
			// get processing info from session
			$process_info = $this->CI->session->all_userdata();
			
			// build the processing array
			$postArray = $this->build_akatus_post($process_info,$order_info,$order_id);
			
			
			$proc = $this->CI->platform->post('custom_merchant/process/transaction',$postArray);
			
			
		
			
			if($proc['success']) :
				
				// disrecard invoice
				$invoice =  $this->CI->platform->post('ubersmith/invoice/disregard/'.$order_info['client_id']."/".$invoice_id);
				
				# generate the invoice not skipping. fingers crossed that this doesnt charge the card on a disregaurded invoice
				# do this shit
				//$order_process = $this->CI->platform->post('ubersmith/order/process/verify_payment/'.$order_id);
				# now we should mark packs to auto_bill = 0 
				# loop thru the packids which have already been made in the build_akatus post
				if(isset($order_info['order_id'])) :
					for($i=0;$i<=100;$i++) 
					{
						if(isset($order_info['info']["pack$i"])):
							$package_id = $order_info['info']["pack$i"]['packid'];
							$update = $this->CI->platform->post(
								'ubersmith/package/update/'.$package_id,
									 array(
									'auto_bill'=> '0')
								);
						endif;
					}
					
				$api  = 'ubersmith/order/process/generate_invoice/'.$order_id;
			
				// generate 0 dollar invoice
				$resp = $this->CI->platform->post($api);

				#### check verify payment and complete order process
				$api  = 'ubersmith/order/process/verify_payment/'.$order_id;

				$resp = $this->CI->platform->post($api);
				endif;
				
				else:
					$response = $this->CI->platform->post(
					'ubersmith/order/cancel/'.$order_id
				);
				
			endif;
			# set return variables
			$response['success']					= $proc['success'];
			$response['error']						= isset($proc['data']['response_text'][0]) ? $proc['data']['response_text'][0] : $proc['data']['response_text'] ;
			$response['data']						= $proc['data'];
			$response['data']['submit']['success']	= $proc['success'];
			return $response;
	}
	public function build_akatus_post($post,$order,$orderid)
	{
	
		$return = array();
		//decrypt credit card detauls
		$ccdetails = array();
		$key = "GoPittsburghSteelers6";
		$ccdetails['cc_number']			= rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($post["cc_number"]), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		$ccdetails['cc_cvv2_code']		= rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($post["cvv"]), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		$ccdetails['cpf']				= rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($post["cpf"]), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		$ccdetails['ccexp']				= rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($post["ccexp"]), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		
		$return['cc_info']					= $ccdetails;
		$post['signup']['orderid']			= $orderid;
		$post['signup']['ip']				= isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR'];
		$post['signup']['product_name']		= "Web Hosting";
		$post['signup']['partner_id']		= $post['partner_id'];
		$return['signup']					= $post['signup'];
		
		$packs = array();
		if(isset($order['order_id'])) :
			$return['initial_sale']['amount'] = $order['total'];
			for($i=0;$i<=100;$i++) 
			{
				if(isset($order['info']["pack$i"])):
					$packs[] = $order['info']["pack$i"];
				endif;
			}
		endif;
		$return['plans']['recurring'] = $packs;
		return $return;
	}
}