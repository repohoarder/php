<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Examples Ubersmith Package
 * 
 * This class shows examples of how to use the Ubersmith Module's Package API
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @package Examples Ubersmith Package
 * 
 * @method json	get_client_by_id(int $id)		This method gets client data based on client id	
 * 
 */
class Package extends MX_Controller 
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
	
	public function add()
	{
		
	}
	
	public function deactivate($client_id,$pack_id)
	{
		// make the API call and debug the response
		$this->debug->show($this->curl->post($this->platform.'ubersmith/package/deactivate/'.$client_id.'/'.$pack_id,array()),true);
	}
	
	public function get_pack_by_client_id($client_id)
	{
		// make the API call and debug the response
		$this->debug->show($this->curl->post($this->platform.'ubersmith/package/get/client_id/'.$client_id,array()),true);		
	}
	
	public function get_pack_by_pack_id($pack_id)
	{
		// make the API call and debug the response
		$this->debug->show($this->curl->post($this->platform.'ubersmith/package/get/pack_id/'.$pack_id,array()),true);
	}
}