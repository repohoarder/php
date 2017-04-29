<?php 
ini_set("display_errors",'on');
class Fulfillment extends MX_Controller
{ 
	
	function index(){
		//load empty page 
		
		$data['response'] = 'hello';
		$this->load->view('ajax_display',$data);
	}
	function notes(){
		
		
		$packid = $this->input->post('id');
		
		$post = array(
			'pack_id'=>$packid
		);
		$data = $this->platform->post('fulfillment/notes/get',$post);
		
		if($data['success']):
			
			$notes['notes'] = $data['data'];
			$this->load->view('ajax/apadmin/notes',$notes);
		
		else:
			
			echo "No notes on file";
		
		endif;
		
	}
	
	function addnote(){
		
		
		$packid = $this->input->post('id');
		$notes = $this->input->post('notes');
		$loginid = $this->session->userdata('login_id');
		$post = array(
			'pack_id'=>$packid,
			'notes' => $notes,
			'login_id' =>$loginid
		);
		
		$data = $this->platform->post('fulfillment/notes/add',$post);
		
		if($data['success']):
			
			echo "Note saved";
		
		else:
			
			echo $data['error'];
		
		endif;
		
	}
	function revokenotes(){
		
		
		$packid = $this->input->post('id');
		
		$post = array(
			'pack_id'=>$packid
		);
		$data = $this->platform->post('fulfillment/notes/getrevoke',$post);
		
		if($data['success']):
			
			$notes['notes'] = $data['data'];
			$this->load->view('ajax/apadmin/notes',$notes);
		
		else:
			
			echo "No notes on file";
		
		endif;
		
	}
	
	function revokeaddnote(){
		
		
		$packid = $this->input->post('id');
		$notes = $this->input->post('notes');
		$loginid = $this->session->userdata('login_id');
		$post = array(
			'pack_id'=>$packid,
			'notes' => $notes,
			'login_id' =>$loginid
		);
		
		$data = $this->platform->post('fulfillment/notes/addrevoke',$post);
		
		if($data['success']):
			
			echo "Note saved";
		
		else:
			
			echo $data['error'];
		
		endif;
		
	}
	// fullfill individual order
	public function fulfillorder(){
		
			$packid = $this->input->post('pack_id');
			
			if(! $packid) :
				echo "No package id passed";
				exit(0);
			endif;
			
			// fulfill call
			$fulfill = $this->platform->post('fulfillment/fulfill/item/service/'.$packid."/");
			
			
			// set record as revoked in database
			if($fulfill['success']) :
				
				$this->platform->post('fulfillment/cron/package/markfulfilled', array('pack_id'=>$packid));
				
				echo "Package fulfilled";
				
				else : 
					echo "Error :"; var_dump($fulfill['error']);
			endif;
			
		
	}
	// fullfill individual order
	public function revokeorder(){
		
			$packid = $this->input->post('pack_id');
			
			if(! $packid) :
				echo "No package id passed";
				exit(0);
			endif;
			
			// fulfill call
			$fulfill = $this->platform->post('fulfillment/cron/package/addtorevoketable', array('pack_id'=>$packid));
			
			// set record as revoked in database
			if($fulfill['success']) :
				
				echo "Package added for revoke processing.";
				
				else : 
					//var_dump($fulfill);
					echo "Error : an error occured ". $fulfill['error'];
			endif;
			
		
	}
	public function dorevoke(){
		
			$packid = $this->input->post('pack_id');
			
			if(! $packid) :
				echo "No package id passed";
				exit(0);
			endif;
			// revoke call
			$revoke = $this->platform->post('revoke/ended/item/service/'.$packid."/", array());
		
			// set record as revoked in database
			if($revoke['success']) :
				$this->platform->post('fulfillment/cron/package/markrevoked', array('pack_id'=>$packid));
			else:
				echo "Failed to revoke: ".$revoke['error'];
			endif;
			
			;
	}
}