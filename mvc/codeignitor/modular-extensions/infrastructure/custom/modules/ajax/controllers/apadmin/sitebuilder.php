<?php 
ini_set("display_errors",'on');
class Sitebuilder extends MX_Controller
{ 
	
	function index(){
		//load empty page 
		
		$data['response'] = 'hello';
		$this->load->view('ajax_display',$data);
	}
	function notes(){
		
		
		$id = $this->input->post('id');
		
		$post = array(
			'queue_id'=>$id
		);
		$data = $this->platform->post('builder/notes/get',$post);
		
		if($data['success']):
			
			$notes['notes'] = $data['data'];
			$this->load->view('ajax/apadmin/notes',$notes);
		
		else:
			
			echo "No notes on file";
		
		endif;
		
	}
	
	function addnote(){
		
		
		$id = $this->input->post('id');
		$notes = $this->input->post('notes');
		$loginid = $this->session->userdata('login_id');
		$post = array(
			'queue_id'=>$id,
			'notes' => $notes,
			'login_id' =>$loginid
		);
		
		$data = $this->platform->post('builder/notes/add',$post);
		
		
		if($data['success']):
			
			echo "Note saved";
		
		else:
			
			echo $data['error'];
		
		endif;
		
	}
	function markspun(){
		
		
		$id = $this->input->post('id');
		
		$post = array(
			'id'=>$id,
			
		);
		
		$data = $this->platform->post('builder/queue/markspun',$post);
		
		
		if($data['success']):
			
			echo " Updated Successfully";
		
		else:
			
			var_dump($data);
		
		endif;
		
	}
	function build(){
		
		$id = $this->input->post('id');
		// if no id passed, show error
		if ( ! $id) :
			echo 'You must pass a valid id in order to build a site.';
			exit();
		endif;

		// grab build ticket info
		$ticket 	= $this->platform->post('builder/queue/get',array('id' => $id));

		// if unable to grab ticket info, show error
		if ( ! $ticket['success']):
			echo 'Unable to find ticket information.';
			exit();
		endif;

		// set ticket info
		$ticket 	= $ticket['data'][0];

		// build site
		$build	= $this->curl->post('http://sitebully.brainhost.com/builder/install/'.$ticket['name'],array('domain' => $ticket['domain'], 'client_id' => $ticket['client_id'], 'partner_id' => $ticket['partner_id']));
		
		$build = json_decode($build,true);
		
		// see if there was an error
		if ( ! $build['success']):	

			// insert error into DB
			$error 	= $this->platform->post('builder/errors/insert',array('queue_id' => $id, 'error_code' => $build['error'], 'error_message' => $build['data']));
			$err = $build['data'];
			echo "Error Code : ".$build['error']. "<br>";
			if(is_array($err)) :
				var_dump($err);
			else :
				echo "Error : " .$build['data'];
			endif;
			
			
		
		endif;	// end checking for errors

		// mark status as completed (status_id = 3)
		$completed 	= $this->platform->post('builder/queue/insertstatus',array('login_id' => $this->session->userdata('login_id'), 'queue_id' => $id, 'status_id' => 3));
		
		echo "Build finished successfully.<br>" . $build['data'];
		
	}
	
}
