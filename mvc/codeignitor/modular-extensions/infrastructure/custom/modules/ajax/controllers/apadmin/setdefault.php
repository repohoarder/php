<?php 
ini_set("display_errors",'on');
class Setdefault extends My_Controller
{ 
	
	function index(){
		//load empty page 
		
		$data['response'] = '';
		$this->load->view('ajax_display',$data);
	}
	function calendar(){
		$id = addslashes($_REQUEST['id']);
		$login_id = $this->session->userdata('login_id');
		$this->load->model('loginsdata');
		$this->loginsdata->updateDefaultCalendar($login_id,$id);
	}
}