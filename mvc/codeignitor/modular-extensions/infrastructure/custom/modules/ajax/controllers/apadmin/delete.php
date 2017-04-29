<?php 
ini_set("display_errors",'on');
class Delete extends MX_Controller
{
	function index(){
		//load empty page
		$data['response'] = '';
		$this->load->view('ajax_display',$data);
	}
	
	function roles(){
		
		// check for page privileges
		$this->pageauth->setPrivileges('roles');
		
		// see if user has delete privileges
		if (!$this->pageauth->loginHasPrivileges('delete')) :
		
			$json['error'] = "You do not  have access to add or edit these records";
			echo json_encode($json);
			exit();
		
		else:
			
			$id = $this->input->post('id');
		
			$response = $this->platform->post('apadmin/role/delete',array('id'=>$id));
			
			if($response['success']) :
				
				$json['success'] = $response['success'];
				echo json_encode($json);
				
				else:
				
				$json['error'] = $response['error'];
				echo json_encode($json);
				
			endif;
		endif;
	}
	function privileges(){
		
		// set page privileges
		$this->pageauth->setPrivileges('privileges');
		// check to see if has delete privileges
		if (!$this->pageauth->loginHasPrivileges('delete')) :
			
			$json['error'] = "You do not  have access to add or edit these records";
			echo json_encode($json);
			exit();
			
		else:
			
			$id = $this->input->post('id');
			$response = $this->platform->post('apadmin/privilege/delete',array('id'=>$id));
			
			if($response['success']) :
				$json['success'] = $response['success'];
				echo json_encode($json);
			else:
				$json['error'] = $response['error'];
				echo json_encode($json);
			endif;
			
		endif;
	}
	function logins(){
		
		// set user privileges
		$this->pageauth->setPrivileges('logins');
		
		// check to see if has delete privileges
		if (!$this->pageauth->loginHasPrivileges('delete')) :
		
			$json['error'] = "You do not  have access to add or edit these records";
			echo json_encode($json);
			exit();
		
		else:
			
			$id = $this->input->post('id');
			$response = $this->platform->post('apadmin/users/delete',array('id'=>$id));
			
			if($response['success']) :
				$json['success'] = $response['success'];
				echo json_encode($json);
			else:
				$json['error'] = $response['error'];
				echo json_encode($json);
			endif;
			
			
		endif;
	}
	function menus(){
		
		// set page privileges
		$this->pageauth->setPrivileges('menus');
		
		// check to see if has delete privileges
		if (!$this->pageauth->loginHasPrivileges('delete')) :
		
			$json['error'] = "You do not  have access to add or edit these records";
			echo json_encode($json);
			exit();
		
		else:
			
			$id = $this->input->post('id');
			$response = $this->platform->post('apadmin/menu/delete',array('id'=>$id));
			
			if($response['success']) :
				$json['success'] = $response['success'];
				echo json_encode($json);
			else:
				$json['error'] = $response['error'];
				echo json_encode($json);
			endif;
			
			
		endif;
		
	}
	function googlecal(){
		
		$json = array();
			// load my google calendar library ( includes the sdk )
		$this->load->library('googlecalendar');
		// check to see if this user has google account linked
		$has_access = $this->googlecalendar->checkGoogleSetup();
		// if the user has access grab calendars.
		
		if($has_access) :
			//create client
			$client = $this->googlecalendar->googleClient();
			// create calendar
			$cal = $this->googlecalendar->googleCal($client);
			// validate token
			if($this->googlecalendar->validateToken($client)):
				$id = urldecode($_POST['id']);
				$newcal = $this->googlecalendar->removeCalendar($id,$cal);
				$json['id'] = '';
			
			else:
			$json['error'] ='Token Invalid';
			endif;
			else: 
			$json['error'] ='You are not linked.';
		endif;
		$data['response'] = json_encode($json);
		$this->load->view('ajax_display',$data);
	}
}