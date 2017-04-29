<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Examples Ubersmith Order
 * 
 * This class shows examples of how to use the Ubersmith Module's Order API
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @package Examples Ubersmith
 * 
 * @method json	create(int $order_queue_id)	This method creates an order in Ubersmith	
 * 
 */
class Order extends MX_Controller 
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
	
	public function create($order_queue_id)
	{
		// create API post array
		$post	= array(
			'client_id',
			'ts',					// Current Time
			'order_form_id',
			'order_status',			// Leads Step ID
			'priority',
			'owner',
			'client_id',
			'listed_company',
			'total',
			'hash',
			'activity',
			'progress',
			'info',
			'signature'
		);
		
		// make the API call and debug the response
		$this->debug->show($this->curl->post($this->platform.'ubersmith/order/create/'.$order_queue_id,$post),true);
	}
}

/* End of file order.php */
/* Location: ./application/modules/examples/conrollers/ubersmith/order.php */