<?php 
ini_set("display_errors",'on');
class Eventsload extends My_Controller
{ 
	function index(){
		//load empty page 
		
		$data['response'] = 'hello';
		$this->load->view('ajax_display',$data);
	}
	function mymeetings(){
		$startDate = date('Y-m-d', $_GET['start']);
		$endDate = date('Y-m-d', $_GET['end']);
		$this->load->model('clienteventsdata');
		$where = " AND created_by=".$this->session->userdata('login_id');
		$events = $this->clienteventsdata->fetchEvents($startDate,$endDate,$where);
		$data['response'] = json_encode($events);
		$this->load->view('ajax_display',$data);
	}
}