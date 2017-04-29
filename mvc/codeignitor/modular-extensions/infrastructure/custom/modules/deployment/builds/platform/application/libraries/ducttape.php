<?php 

class Ducctape
{
	var $CI;
	
	function __construct() 
	{	
		// load codeignitor instance
		$this->CI = &get_instance();
	}
	
	public function csv_of_nmi_tranasactions($transactions)
	{
		$this->CI->csv->create();
	}
	
	
	
	
}

?>