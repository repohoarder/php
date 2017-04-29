<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * The
 */
class Ubersmith_upgrade {
	
	protected $_ci;

	function __construct()
	{

		$this->_ci = &get_instance();

	}
	
	public function addplan($planid,$clientid){
		
		return $this->_ci->platform->post("ubersmith/package/add/$clientid/$planid",array());

	}
	
	public function generateInvoice($clientid)
			{
		return $this->_ci->platform->post("ubersmith/invoice/generate/$clientid",array());
	}
	public function chargeCard($invoiceid){
		return $this->_ci->platform->post("ubersmith/invoice/charge/$invoiceid",array());
	}
	public function deactivatePack($packid,$clientid){
		//deactivate
		return $this->_ci->platform->post("ubersmith/package/deactivate/$clientid/$packid",array());
	}
	public function disregardInvoice($clientid,$invoiceid){
		//disregard
		return $this->_ci->platform->post("ubersmith/invoice/disregard/$clientid/$invoiceid",array());
	}
}
