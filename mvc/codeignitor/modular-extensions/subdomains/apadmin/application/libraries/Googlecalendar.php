<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library will create a 
 * The configArr must consist
 * Author : Jamie Rohr
 * Date : 10-02-2012
 * 
 *
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/assets/googleapi/apiClient.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/assets/googleapi/contrib/apiCalendarService.php';
class Googlecalendar {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
	/**
	 * Create new google client to connect to google calendar ( this library use the google SDK above )
	 * This function will  look for a code from google and if it is set, set a session and and return access token
	 * It will also check a users database table and set thier individual tokens for use.
	 * Author: Jamie Rohr
	 * Date 10-11-2012
	 * @return object
	 */
	public function checkGoogleSetup(){
		$this->CI->load->model('loginsdata');
		$login_id = $this->CI->session->userdata('login_id');
		$has_access = $this->CI->loginsdata->getGoogleStuff($login_id);
		return $has_access;
	}
	public function googleClient(){
		$client = new apiClient();
		$client->setApplicationName("CRM Calendar");
		$this->CI->load->library('securityit');
		// Visit https://code.google.com/apis/console?api=calendar to generate your
		// client id, client secret, and to register your redirect uri.
		 $client->setClientId($this->CI->securityit->DECRYPTIT($this->CI->session->userdata('oauth2_client_id')));
		 $client->setClientSecret($this->CI->securityit->DECRYPTIT($this->CI->session->userdata('oauth2_client_secret')));
		 $client->setRedirectUri('https://www.dmgcrm.com/staff/profile/google');
		 $client->setDeveloperKey($this->CI->securityit->DECRYPTIT($this->CI->session->userdata('developer_key')));
		

		if (isset($_GET['code'])) {
		  $client->authenticate();
		  $clienttoken = $client->getAccessToken();
		  $this->CI->session->set_userdata('google_token',$clienttoken);
		  $this->CI->load->model('loginsdata');
		  $this->CI->loginsdata->updateGoogleCode($clienttoken);
		  header('Location: https://www.dmgcrm.com/staff/profile/google');
		}
		if ($this->CI->session->userdata('google_token') != '') {
		  $client->setAccessToken($this->CI->session->userdata('google_token'));
		}
		return $client;
	}
	/**
	 * Validate Access Token
	 *
	 * @param object $client
	 * @return booleon
	 */
	public function validateToken($client){
		if ($client->getAccessToken()) {
			return true;
		}
		else{
			return false;
		}
	}
	/**
	 * This function creates an authorization linke using the sdk functions
	 *
	 * @param object $client
	 * @return string
	 */
	public function createAuthLink($client){
		return $client->createAuthUrl();
	}
	/**
	 * This function returns an error class with a link to link your google account
	 *
	 * @param string $authurl
	 * @return html string
	 */
	public function createAuthError($authUrl){
		return "<div class=\"alert alert-error\">
						<a data-dismiss=\"alert\" class=\"close\">X</a>
						<strong>Google Account Not Authorized!</strong>  To authorize your google account to use the google calendar features <a class='login' href='$authUrl'>Click Here </a>
					</div>";
	}
	public function checkDefaultCalendar(){
		$calendarid = $this->CI->session->userdata('default_calendar');
		if(empty($calendarid)){
			return "<div class=\"alert alert-error\">
						<a data-dismiss=\"alert\" class=\"close\">X</a>
						<strong>Default Calendar!</strong>  not set,  <a class='login' href='/staff/profile/google'>Click Here </a> to set your default calendar to post meetings to.
					</div>";
		}
	}
	/**
	 * Creates a google calendar service object
	 *
	 * @param object $client
	 * @return object of calendar service
	 */
	public function googleCal($client){
		$cal = new apiCalendarService($client);
		return $cal;
	}
	/**
	 * This function retrieves a list of calendars linked to your account
	 *
	 * @param object $cal
	 * @return array
	 */
	public function retrieveCalendarList($cal){
		$calList =  $cal->calendarList->listCalendarList();
		$html = "<div class=\"row-fluid\"><div class=\"span8\">
								<div class=\"w-box\" id=\"w_sort02\">
									<div class=\"w-box-header\">
										My Calendars
									</div>
									<div class=\"w-box-content\">
										<table class=\"table table-striped\">
											<thead>
												<tr>
													<th  style=\"width:16px;cursor:pointer;\"><a role=\"button\"  data-toggle=\"modal\" data-target=\"#googlecal_dialog\" id=\"addgooglecal\" class=\"clearfields\"><i class=\"splashy-add clearfields\"></i></a></th>
													<th>Calendar Name</th>
													<th>Description</th>
													<th>&nbsp;</th>
												</tr>
											</thead>
											<tbody>";
		$count = count($calList['items']);
		  	$string='';
		  	for($i=0 ;$i<$count; $i++ )
		  	{
		  		$desc = isset($calList['items'][$i]['description']) ? $calList['items'][$i]['description'] : "&nbsp;";
			$html .="							<tr>
													<td><a href='javascript:void(0);' title='Set as default calendar' onClick=\"setDefaultCalendar('{$calList['items'][$i]['id']}');\"><i class=\"splashy-calendar_day_up\"></i></a></td>
													<td><a href='/staff/profile/googleevents?calendarid={$calList['items'][$i]['id']}'>{$calList['items'][$i]['summary']}</a></td>
													<td>$desc</td>
													<td><i title=\"Delete Record\" class=\"splashy-remove img\" id=\"delete_{$calList['items'][$i]['id']}\" onClick=\"doDelete(this);\"></i></td>
												</tr>";
		  	}									
			$html .="							</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>";
			$arr['html'] = $html;
			$arr['calList'] = $calList;
			return $arr;
	}
	public function retrieveEventsList($calid,$cal){
		$eventlist =  $cal->events->listEvents($calid);
		
		$html = "<div class=\"row-fluid\"><div class=\"span12\">
								<div class=\"w-box\" id=\"w_sort02\">
									<div class=\"w-box-header\">
										{$eventlist['summary']}
									</div>
									<div class=\"w-box-content\">
										<table class=\"table table-striped dTableR dataTable\">
											<thead>
												<tr>
													<th  style=\"width:16px;cursor:pointer;\"><a role=\"button\"  data-toggle=\"modal\" data-target=\"#addevent_dialog\" id=\"addeventcal\" class=\"clearfields\"><i class=\"splashy-add clearfields\"></i></a></th>
													<th>Summary</th>
													<th>Description</th>
													<th>Location</th>
													<th>Duration</th>
												</tr>
											</thead>
											<tbody>";
			if(isset($eventlist['items']))
			{								
				$count = count($eventlist['items']);
			  	$string='';
			  	for($i=0 ;$i<$count; $i++ )
			  	{
			  		$summary = isset($eventlist['items'][$i]['summary']) ? $eventlist['items'][$i]['summary'] : "&nbsp;";
			  		$desc = isset($eventlist['items'][$i]['description']) ? $eventlist['items'][$i]['description'] : "&nbsp;";
			  		$location = isset($eventlist['items'][$i]['location']) ? $eventlist['items'][$i]['location'] : "&nbsp;";
			  		$date = isset($eventlist['items'][$i]['start']['dateTime']) ? "Start:".date("Y-m-d h:i:s", strtotime($eventlist['items'][$i]['start']['dateTime'])) ."":'';
					$date .= isset($eventlist['items'][$i]['end']['dateTime']) ? "<br>End: ".date("Y-m-d h:i:s", strtotime($eventlist['items'][$i]['end']['dateTime'])) ."":'';	
			  		$html .="	<tr id='cal{$eventlist['items'][$i]['id']}'>
										<td></td>
										<td><a href='/staff/profile/googleevents?calendarid={$eventlist['items'][$i]['id']}'>$summary</a></td>
										<td>$desc</td>
										<td>$location</td>
										<td>$date</td>
									</tr>";
			  	}		
			}							
			$html .="		</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>";
			$arr['html'] = $html;
			$arr['calList'] = $eventlist;
			return $arr;
	}
	/**
	 * This function just creates a form for updating your google keys in the database
	 *
	 * @return string
	 */
	public function retrieveGoogleForm(){
		return "
		
		<form method=\"post\" action=\"/staff/profile/google\">
				<div class=\"row-fluid\">
				<div class=\"alert alert-block alert-error\">
									<a data-dismiss=\"alert\" class=\"close\">X</a>
									<strong>Link your google account to sync with your calendar on your phone!</strong>
									<ol>
									<li>Login to y our google account and go to <a href='https://code.google.com/apis/console?api=calendar' target='_blank'>https://code.google.com/apis/console/</a></li>
									<li>Signup for OAuth 2.0 access</li>
									<li>Use https://www.dmgcrm.com/staff/profile/google  as your client ID Redirect URL when setting this up.</li>
									<li>Place your Key for browser apps (with referers)  API key into form below, along with your Client ID and Secret Key</li>
									<li>Reference <a href='https://developers.google.com/console/help/#creatingdeletingprojects' target='_blank'>https://developers.google.com/console/help/#creatingdeletingprojects</a></li>
							  </ul>
								</div>
							<div class=\"span4\">
								<div class=\"w-box\" id=\"w_sort05\">    
									<div class=\"w-box-header\">
										 Google Account Settings
									</div>
									<div class=\"w-box-content cnt_a\">
									<div class=\"sepH_b\">
											<label for=\"w_name\"></label>
										</div>
										<div class=\"sepH_b\">
											<label for=\"w_name\">Client ID:</label>
											<input type=\"text\" name=\"oauth2_client_id\" id=\"oauth2_client_id\" class=\"span12\" />
										</div>
										<div class=\"sepH_b\">
											<label for=\"w_name\">Client secret:</label>
											<input type=\"text\" name=\"oauth2_client_secret\" id=\"oauth2_client_secret\" class=\"span12\" />
										</div>
										<div class=\"formSep\">
											<label for=\"wg_message\">API key:</label>
											<input type=\"text\" name=\"developer_key\" id=\"developer_key\" class=\"span12\" />
										</div>
										<div class=\"clearfix\">
										<button class=\"btn btn-info\" type=\"submit\"  name=\"gsetting\">Save</button>
										</div>
									</div>
								</div>
							</div>
					</div>
				</form>";
	}
	public function addCalendar($summary,$desc,$cal){
			// create calendar object
		  	$newcal = new Calendar();
		  	$newcal->summary = $summary;
		  	$newcal->description = $desc;
		  	//insert the new calendar
		  	$caladd = $cal->calendars->insert($newcal);
		  	//return calendar object
		  	return $caladd;
	}
	public function removeCalendar($calendarid,$cal){
		return $cal->calendars->delete($calendarid);
	}
	public function newEvent($post,$cal,$calenderid){
		// create event object
		$event = $this->eventObject($post);
		// add event
		$eventAdd = $cal->events->insert($calenderid,$event);
		return $eventAdd;
	}
	public function updateEvent($post,$cal,$calendarid){
		// create event object
		$event = $this->eventObject($post);
		// add event		
		$eventAdd = $cal->events->update($calendarid, $post['eventid'], $event);
		return $eventAdd;
	}
	private function eventObject($post){
		// format start and end date DATETIME RFC3339 format
		$enddate = $this->dateSetRFC($post['enddate'],$post['endtime']);
		$startdate = $this->dateSetRFC($post['startdate'],$post['starttime']);
		
		$start = new EventDateTime();
		//$start->date = date("Y-m-d",strtotime($post['startdate']));
		$start->dateTime = $startdate;
		
		$end = new EventDateTime();
		//$end->date = date("Y-m-d",strtotime($post['startdate']));
		$end->dateTime = $enddate;
		
		$event = new Event();
		$event->description = $post['description'];
		$event->summary = $post['event'];
		$event->end = $end;
		$event->start = $start;
		return $event;
	}
	public function dateSetRFC($date,$time){
		$d = $date." ". $time;
		$sdate = new DateTime($d);
		$formateddate = $sdate->format(DATE_RFC3339);
		return $formateddate;
	}
}