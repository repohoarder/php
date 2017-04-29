<?php 
ini_set("display_errors",'on');
class Partner extends MX_Controller
{ 
	
	function index(){
		//load empty page 
		
		$data['response'] = 'hello';
		$this->load->view('ajax_display',$data);
	}
	function pixels(){
		
		
		$id = $this->input->post('id');
		$partner_id = $this->input->post('partner_id');
		$post = array(
			'pixel_id'=>$id,
			'partner_id' =>$partner_id
		);
		$data = $this->platform->post('partner/pixel/get',$post);
		
		
		if($data['success']):
			//var_dump($data);
			$pixel = stripslashes($data['data']['pixels'][0]['pixel']);
			
			echo  "<textarea id=\"pixel\" name=\"pixel\" class='span8' rows=\"30\" style='width:500px;'>$pixel</textarea>";
			
		else:
			
			echo "No pixel found";
		
		endif;
		
	}
	
}
