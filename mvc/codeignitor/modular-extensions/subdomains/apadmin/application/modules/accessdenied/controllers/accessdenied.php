<?php 
ini_set("display_errors",'on');
class Accessdenied extends MX_Controller
{
	function index(){
		// needs called in every function will redirect to login
		// set template layout to use
		$this->template->set_layout('default');
		
		// load view
		$this->template->build('accessdenied/accessdenied', $data);
	}
}
?>
