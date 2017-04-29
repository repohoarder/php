<?php 
ini_set("display_errors",'on');
class Recordload extends MX_Controller
{
	
	function index(){
		
		$config = array(
				"id" => $this->input->get('id'),
				"target" =>$this->input->get('target') 
				);
		
		$data = $this->platform->post('apadmin/recordload/load',$config);
		
		if( ! $data['success']) :
			echo json_encode($data['error']);
			exit();
		endif;
		// return json object for jquery
		echo json_encode($data['data']);
	}
	
	function rolestologins(){
		$this->load->model("json");
		$data['response'] = $this->json->rolestologins();
		$this->load->view('ajax_display',$data);
	}
	
}
?>