<?php 
ini_set("display_errors",'on');
class Wizard extends My_Controller
{ 
	
	function index(){
		//load empty page 
		
		$data['response'] = 'hello';
		$this->load->view('ajax_display',$data);
	}
	function prospectsave(){
		$this->load->model('clientsdata');
		$data['response'] = '';
		$post = array();
		foreach ($_POST as $key => $value) {
			$post[$key] = mysql_real_escape_string(strip_tags(html_entity_decode($value)));
			//$data['response'] .= "\"$key\"=>".'$post'."['$key'],";
		}
		$d = strtotime($post['startdate']." ". $post['starttime']);
		$d2 = strtotime($post['enddate']." ". $post['endtime']);
		if($d > $d2):
			$data['response'] = "End date should not be before your start date";
		else:
		$insertArr = array("admin_id"=>$this->session->userdata('login_id'),"companyname"=>$post['companyname'],"firstname"=>$post['firstname'],"lastname"=>$post['lastname'],"email"=>$post['email'],"phone"=>$post['phone'],"address"=>$post['address'],"city"=>$post['city'],"state"=>$post['state'],"zip"=>$post['zip'],"client_add_date"=>"NOW()","prospect"=>"Prospect");
		$post['client_id'] = $this->clientsdata->insertClient($insertArr);
		$post['eventid'] ='';
		$post['calendarid'] = $this->session->userdata("default_calendar");
		
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
				if(!empty($post['calendarid'])):
					$eventAdded = $this->googlecalendar->newEvent($post,$cal,$post['calendarid']);
					$post['eventid'] = isset($eventAdded['id']) ? $eventAdded['id'] :'';
				endif;
			endif;
		endif;
		
		
		// add to our calendar now
		$this->load->model('clienteventsdata');
		$this->clienteventsdata->insertEvent($post);
		
		$data['response'] =  "Success";
		endif;
		$this->load->view('ajax_display',$data);
	}
	function scheduleappointment(){
		$data['response'] = '';
		$post['eventid'] ='';
		$post = array();
		foreach ($_POST as $key => $value) {
			$post[$key] = mysql_real_escape_string(strip_tags(html_entity_decode($value)));
			//$data['response'] .= "\"$key\"=>".'$post'."['$key'],";
		}
		$d = strtotime($post['startdate']." ". $post['starttime']);
		$d2 = strtotime($post['enddate']." ". $post['endtime']);
		if($d > $d2):
			$data['response'] = "End date should not be before your start date";
		else:
		
		$post['calendarid'] = $this->session->userdata("default_calendar");
		
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
				if(!empty($post['calendarid'])):
					if(isset($post['event_id']) && !empty($post['eventid'])) :
						$eventUpdated = $this->googlecalendar->updateEvent($post,$cal,$post['calendarid']);
					else:
						$eventAdded = $this->googlecalendar->newEvent($post,$cal,$post['calendarid']);
						$post['eventid'] = isset($eventAdded['id']) ? $eventAdded['id'] :'';
					endif;
				endif;
			endif;
		endif;
		// add to our calendar now
		$this->load->model('clienteventsdata');
		if(isset($post['event_id'])):
			$this->clienteventsdata->updateEvent($post);
		else :
			$this->clienteventsdata->insertEvent($post);
		endif;
		$data['response'] =  "Success";
		endif;
		$this->load->view('ajax_display',$data);
	}
}