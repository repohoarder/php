<?php 
ini_set("display_errors",'on');
class Loadhtml extends My_Controller
{ 
	function index(){
		//load empty page 
		
		$data['response'] = 'hello';
		$this->load->view('ajax_display',$data);
	}
	function event(){
		$this->load->model('clienteventsdata');
		$where = " WHERE t1.id=".addslashes($_POST['id']);
		$events = $this->clienteventsdata->fetchEventsObjectDisplay($where);
		$response ='';
		foreach ($events as $k=>$v){
			$response .="<li>
							<span class=\"item-key\">$k</span>
							<div class=\"vcard-item\">&nbsp;$v</div>
						</li>";
		}
		$data['response'] = '<div class="vcard"><ul style="margin:0;">
											<li class="v-heading">
												Meeting Info
											</li>'.
											$response.'
											</ul></div>';
		$this->load->view('ajax_display',$data);
	}
}