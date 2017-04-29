<?php

class Update_client_credit_ccexp extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index(){
		
	}
	public function update_card_numbers() {
		
			$get = $this->platform->post('ubersmith/credit_cards/get_expiring',array('update_cards'=>true));
			var_dump($get);
			
			echo "completed";
	}
}
?>
