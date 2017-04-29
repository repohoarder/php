<?php 
ini_set("display_errors",'on');
class Search extends My_Controller
{
	
	function index(){
		//load empty page
		$data['response'] = '';
		$this->load->view('ajax_display',$data);
	}
	function companyname(){
		$this->load->model('clientsdata');
		$search  = addslashes($_POST['search']);
		if(!empty($search))
		{
			$results = $this->clientsdata->searchClients($search);
			$str='';
			foreach ($results as $row):
				$str .= "<a href='/clients/schedule?client={$row->id}'>Schedule Meeting</a>".stripslashes($row->companyname)."<br>";
			endforeach;
			$data['response'] = $str;
			$this->load->view('ajax_display',$data);
		}
	}
}