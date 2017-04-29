<?php 

class Gateway extends MX_Controller 
{

	function __construct()
	{
		parent::__construct();
	}

	public function differences($start=false,$end=false,$type=false)
	{
		// grab all transactions in ubersmith
		$ubersmith	= $this->platform->post('crm/');
		
		// grab all transactions in gateway
		
		// compare ubersmith transactions and gateway transactions
		
		// display transactions in Ubersmith but not in Gateway
	}
	
}

?>