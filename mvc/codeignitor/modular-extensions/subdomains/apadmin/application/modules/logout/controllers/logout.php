<?php 
ini_set("display_errors",'on');
class Logout extends MX_Controller
{
	
	function index(){
		$this->session->sess_destroy();
		redirect( "/login/auth?logout" );
	}
	
}
?>
