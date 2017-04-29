<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Examples Ubersmith Cleint
 * 
 * This class shows examples of how to use the Ubersmith Module's Client API
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @package Examples Ubersmith Client
 * 
 * @method json	get_client_by_id(int $id)		This method gets client data based on client id
 * @method json	get_client_by_email()			This method gets client data based on client email
 * @method json	update_client(int $client_id)	This method updates client details in Ubersmith		
 * 
 */
class Client extends MX_Controller 
{
	
    public function __construct() 
    {
        parent::__construct();
	}
	
	public function add()
	{	
		// create API post array
		$post	= array(
			'first'					=> 'Bert',
			'last'					=> 'Ernie',
			//'company',
			'email'					=> 'thompson2091+999@gmail.com',
			//'address',
			//'city',
			//'state',
			//'zip',
			//'country',
			//'phone',
			//'fax',
			'uber_login'			=> 'mytestusername',
			'uber_pass'				=> 'mytestpass',
			//'grace_due',
			//'charge_days',
			//'datesend',
			//'datedue',
			//'datepay',
			//'referred_by',
			//'discount',
			//'discount_type',
			//'referred',
			'active'				=> 1,	// 1 = Client 2 = Lead
			//'late_fee_scheme_id',
			//'brand_id',
			//'retry_every',
			//'priority',
			'strict'				=> 1,	// This field says to make the email on file unique
			//'meta_'			
		);
		
		// make the API call and debug the response
		$this->debug->show($this->platform->post('ubersmith/client/add',$post),TRUE);
	}
	
	public function get_client_by_id($id='1')
	{

		// make the API call and debug the response
		$this->debug->show($this->platform->post('ubersmith/client/get/id',array('client_id' => $id)),TRUE);
	}
	
	public function get_client_by_email()
	{
		
		// intiialize variables
		$email	= 'thompson2091@gmail.com';
		
		// make the API call and debug the response
		$this->debug->show($this->platform->post('ubersmith/client/get/email',array('email' => $email)),TRUE);
	}
	
	public function update($client_id)
	{		
		// create API post array
		$post	= array(
			'first'			=> 'John',
			'last'			=> 'Doe',
			//'company',
			//'email',
			//'address',
			//'city',
			//'state',
			//'zip',
			//'country',
			//'phone',
			//'fax',
			//'uber_login',
			//'grace_due',
			//'charge_days',
			//'datesend',
			//'datedue',
			//'datepay',
			//'referred_by',
			//'discount',
			//'discount_type',
			//'referred',
			//'active',
			//'late_fee_scheme_id',
			//'brand_id',
			//'retry_every'
		);
		
		// make the API call and debug the response
		$this->debug->show($this->platform->post('ubersmith/client/update/'.$client_id,$post),TRUE);
	}
	
}

/* End of file client.php */
/* Location: ./application/modules/examples/conrollers/ubersmith/client.php */