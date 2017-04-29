<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Examples Ubersmith Payment Method
 * 
 * This class shows examples of how to use the Ubersmith Module's Payment Method API
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	August 1,2012
 * 
 * @package Examples Ubersmith Payment Method
 * 
 * @method json	update_payment_method(int $client_id, int $billing_info_id)	This method updates a clients payment method
 * 
 */
class Payment_method extends MX_Controller 
{

	/*
	 * The URL of the Platform to use for API calls
	 * 
	 * @var string
	 */
	var $platform;
	
    public function __construct() 
    {
        parent::__construct();
        
        // grab default platform URL from config
        $this->platform	= $this->config->item('platform_url');
	}
	
	public function update_payment_method($client_id,$billing_info_id)
	{
		
		// create API post array
		$post	= array(
			'cc_num'			=> '4111111111111111',
			'cc_cvv2'			=> '111',
			'fname'				=> 'testcc',
			'lname'				=> 'testingcc',
			'company'			=> 'Brain Host',
			'address'			=> '123 test ave',
			'city'				=> 'canton',
			'state'				=> 'OH',
			'zip'				=> '44714',
			'country'			=> 'USA',
			'phone'				=> '3305556666',
			'email'				=> 'thompson2091@gmail.com'
		);
		
		// make the API call and debug the response
		$this->debug->show($this->curl->post($this->platform.'ubersmith/payment_method/update/'.$client_id.'/'.$billing_info_id.'/',$post),true);
	}
	
}

/* End of file payment_method.php */
/* Location: ./application/modules/examples/conrollers/ubersmith/payment_method.php */