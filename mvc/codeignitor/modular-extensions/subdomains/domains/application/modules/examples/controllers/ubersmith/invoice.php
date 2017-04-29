<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Examples Ubersmith Invoice
 * 
 * This class shows examples of how to use the Ubersmith Module's Invoice API
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @package Examples Ubersmith Invoice
 * 
 * @method json	get_client_by_id(int $id)		This method gets client data based on client id	
 * 
 */
class Invoice extends MX_Controller 
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
	
	
}