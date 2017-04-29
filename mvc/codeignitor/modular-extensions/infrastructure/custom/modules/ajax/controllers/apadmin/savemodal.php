<?php 
//ini_set("display_errors",'on');
class Savemodal extends MX_Controller
{
	
	function index(){
		
		$data['response'] = $this->json->recordload();
		$this->load->view('ajax_display',$data);
	}
	function loginssave(){
		$json = array();
		// check for page access
		$this->pageauth->setPrivileges('logins');
		if (!$this->pageauth->loginHasPrivileges('add') && !$this->pageauth->loginHasPrivileges('edit')) 
		{ 
			$json['error'] = "You do not  have access to add or edit these records";
			echo   json_encode($json);
			exit();
			
		}
		else{
				$required_fields = array('first_name','last_name','username','email');
				$post = array();
				foreach ($_POST as $key => $value) 
				{
					$post[$key] = ($value);
				}
				
				$id = isset($post['id']) && is_numeric($post['id']) ? $post['id'] : '';
				
				$errors = array();
				
				foreach ($required_fields as $field) 
				{
					if ($post[$field] == '') 
					{
						$errors[] = "{$field} is required";
					}
				}
				if(empty($errors))
				{
					$data = $this->platform->post('apadmin/users/save',$post);
					if( $data['success']) :
						$json['id'] = $data['data'];
					endif;
					
				}
				else{
					$json['error'] = implode(',', $errors) ;
					echo json_encode($json);
					exit(0);
				}
				echo  json_encode($json);
				
			}
	}
	function rolessave()
	{
		
		// check for page access
		$json['initiated'] = true;
		$this->pageauth->setPrivileges('roles');
		if (!$this->pageauth->loginHasPrivileges('add') && !$this->pageauth->loginHasPrivileges('edit')) :
		
			$json['error'] = "You do not  have access to add or edit these records";
			echo  json_encode($json);
			exit();
			
		else:
				if( ! $this->input->post('name')) :
				
				$json['error'] = "no name selected";
				echo json_encode($json);
				exit(0);
				
			endif;
				
				$data = $this->platform->post('apadmin/role/update',$this->input->post(null,true));
				
				if($data['success']) :
					
					$json['id'] = $data['data'];
				
					else:
						
					$json['error'] = $data['error'];
					
				endif;
		endif;
		echo json_encode($json);
	}
	function privilegessave()
	{
		$json = array();
		// check for page access
		$this->pageauth->setPrivileges('privileges');
		if (!$this->pageauth->loginHasPrivileges('add') && !$this->pageauth->loginHasPrivileges('edit')) 
		{ 
			$json['error'] = "You do not  have access to add or edit these records";
			$data['response'] = json_encode($json);
			$this->load->view('ajax_display',$data);
			
		}
		else{
				$required_fields = array("name","tablename","action");
				$post = array();
				foreach ($_POST as $key => $value) 
				{
					$post[$key] = $value;
				}
				
				$id = isset($post['id']) && is_numeric($post['id']) ? $post['id'] : '';
				
				$errors = array();
				
				foreach ($required_fields as $field) 
				{
					if ($post[$field] == '') 
					{
						$errors[] = "{$field} is required";
					}
				}
				if(empty($errors))
				{
					
					$data = $this->platform->post('apadmin/privilege/update',$post);
					
					if($data['success']) :
						$json['id'] = $data['data'];
					endif;
						
				}
				else{
					$json['error'] = implode(',', $errors) ;
				}
				echo  json_encode($json);
				
		}
	}
	function menussave(){
		$json= array();
		// check for page access
		$this->pageauth->setPrivileges('menus');
		if (!$this->pageauth->loginHasPrivileges('add') && !$this->pageauth->loginHasPrivileges('edit')) 
		{ 
			$json['error'] = "You do not  have access to add or edit these records";
			echo  json_encode($json);
			exit();
			
		}
		else{
				$required_fields = array("link_text");
				$post = array();
				foreach ($_POST as $key => $value) 
				{
					$post[$key] = $value;
				}
				
				$id = isset($post['id']) && is_numeric($post['id']) ? $post['id'] : '';
				
				$errors = array();
				
				foreach ($required_fields as $field) 
				{
					if ($post[$field] == '') 
					{
						$errors[] = "{$field} is required";
					}
				}
				if(empty($errors))
				{
					
					$data = $this->platform->post('apadmin/menu/menusave',$post);
					if($data['success']) :
					$json['id'] = $data['data'];
					else:
						$json['error'] = $data['error'];
					endif;
					
				}
				else{
					$json['error'] = implode(',', $errors) ;
					echo json_encode($json);
					exit(0);
				}
				echo json_encode($json);
				exit(0);
		}
	}
	function menuicon(){
		
		$this->pageauth->setPrivileges('menus');
		
		if (!$this->pageauth->loginHasPrivileges('add') && !$this->pageauth->loginHasPrivileges('edit')) :
			$json['error'] = "You do not  have access to add or edit these records";
			echo  json_encode($json);
		else:
				
			if( ! $this->input->post('iconid')) :
				
				$json['error'] = "no icon selected";
				echo json_encode($json);
				exit(0);
				
			endif;

			$config = array(
				'id'=>$this->input->post('id'),
				'iconid' => $this->input->post('iconid')
			);

			$response = $this->platform->post('apadmin/menu/updatemenuicon',$config);
			$json['id'] = $response['data'];
			echo json_encode($json);
			exit(0);	
		endif;
	}
	function clientssave(){
		$json = array();
	$this->pageauth->setPrivileges('clients');
		if (!$this->pageauth->loginHasPrivileges('add') && !$this->pageauth->loginHasPrivileges('edit')) 
		{ 
			$json['error'] = "You do not  have access to add or edit these records";
			$data['response'] = json_encode($json);
			$this->load->view('ajax_display',$data);
			
		}
		else{
				$required_fields = array("companyname");
				$post = array();
				foreach ($_REQUEST as $key => $value) 
				{
					$post[$key] = mysql_real_escape_string(strip_tags(html_entity_decode($value)));
				}
				
				$id = isset($post['id']) && is_numeric($post['id']) ? $post['id'] : '';
				
				$errors = array();
				
				foreach ($required_fields as $field) 
				{
					if ($post[$field] == '') 
					{
						$errors[] = "{$field} is required";
					}
				}
				if(empty($errors))
				{
					$this->load->model('clientsdata');
					$json['id'] = $this->clientsdata->updateClients($post,$id);
				}
				else{
					$json['error'] = implode(',', $errors) ;
				}
				$data['response'] = json_encode($json);
				$this->load->view('ajax_display',$data);
		}
	}
	function googlecalendaradd(){
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
				$summary 	 = $_GET['summary'];
				$description = $_GET['description'];
				$newcal = $this->googlecalendar->addCalendar($summary,$description,$cal);
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
	function googlecalendaraddevent(){
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
				foreach ($_GET as $key=>$value)
				{
					$post[$key] = mysql_real_escape_string(strip_tags(html_entity_decode(urldecode($value))));
				}
				$d = strtotime($post['startdate']." ". $post['starttime']);
				$d2 = strtotime($post['enddate']." ". $post['endtime']);
				if($d > $d2):
					$json['error'] = "End date should not be before your start date";
				else:
				$calendarid = urldecode($_GET['calendarid']);
				$eventAdded = $this->googlecalendar->newEvent($post,$cal,$calendarid);
				$post['eventid'] = isset($eventAdded['id']) ? $eventAdded['id'] :'';
				$post['calendarid'] = $calendarid;
				// add to our calendar now
				$this->load->model('clienteventsdata');
				$this->clienteventsdata->insertEvent($post);
				endif;
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
?>