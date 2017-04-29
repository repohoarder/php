<?php

class Refunds extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('pageauth');
		$this->load->library('menu');
		$this->load->library('tablebuild');
		$this->load->library('ajaxsave');
		$this->load->library('modalsbuild');
	}
	function index(){
		// needs called in every function will redirect to login
		$tablename = "denyaccess";
		$this->pageauth->checkprivileges($tablename);
	}
	function refundbypartner()
	{
		if($this->input->get()) :
			$get = $this->input->get();
			$_POST = $get;
		endif;
		
		$get = http_build_query($_POST);
		
		$export = "<a href='?export=true&$get'><i class=\"splashy-document_small_download\"></i></a>";
		// check privileges
		$target = 'refundreportr';
		$method = 'refund_items_date';
		
		$this->pageauth->checkprivileges($target);
		$startdate  = $this->input->post('startdate');
        $enddate    = $this->input->post('enddate');
		$partner_id = $this->input->post('partner_id') ? $this->input->post('partner_id') : 1 ;
		
		$partners = $this->platform->post(
				'partner/statistics/admin_reports/mostsales',
				array(
					'login_id'=> $this->session->userdata('login_id')
				)
			);
		
		// change the method if date and checkboxes are checked
		if ( ! $startdate && ! $enddate) :
			$method = 'refund_items_by_partner';
		else:
			if( $this->input->post('refund_date')) :
				$method = "refund_items_by_refund_date";
			endif;
		endif;
		$partners = $partners['data'];
		
		
		// get report
		$report = $this->platform->post(
				'partner/statistics/admin_reports/refundreport',
				array(
					'login_id'=> $this->session->userdata('login_id'),
					'method' => $method,
					'partner_id' => $partner_id,
					'start_date' => $startdate,
					'end_date'  => $enddate
				)
			);
		
		
		
		$reports = $report['data'];
		
		// array keys of return data;
		$tableFields = array_keys($reports[0]);
		
		
		// create main config Array
		$configArr = array(
				'build'		=>	array(),
				"post"		=>	array(),
				"required"	=>	array(),
				'assoc'		=>	array(),
				'records'	=>	$reports,
				'tableheaders'=>$tableFields
		);
		if($this->input->get('export')) :
			$this->export($configArr,'refundreport');
			exit();
		endif;
		$breadcrumb = array("System Roles"=>"");
		//set data to load into view
		$data = array();
		$data['table'] = $this->_build_search_form($partners,$partner_id);
		$data['pagetitle'] = "Roles";
		$data['breadcrumb'] = $this->menu->createBreadCrumb($breadcrumb);
		$data['table'] .= $this->tablebuild->generateTable($target,$configArr,array(),$export);
		$data['javascriptsave'] = '';	
		$data['addmodal'] = '';		
		// set template layout to use
		$this->template->set_layout('default');
		
		// load view
		$this->template->build('administration/users', $data);
	}
	
	private function _build_search_form($partners,$partner_id=1) {
		
		$select='';
		foreach( $partners as $record ) :
			
			$c = $partner_id == $record['partner_id'] ? ' selected="selected"' : '';
			$select .= "<option value='{$record['partner_id']}'$c>{$record['first_name']} {$record['last_name']}</option>";
			
		endforeach;
		$form = "<form method='post' action=''>";
		
		$form .="<div class=\"formSep\">
					<label for=\"v_username\" class=\"control-label\">Start Date:</label>
						<div class=\"controls\">
						<input type=\"text\" name=\"startdate\" id=\"startdate\" class=\"datepicker\" value=\"\"/>
						
					</div>
			    </div>
				<div class=\"formSep \">
					<label for=\"v_username\" class=\"control-label\">End Date:</label>
						<div class=\"controls\">
						<input type=\"text\" name=\"enddate\" id=\"enddate\" class=\"datepicker\" value=\"\"/>
					</div>
			    </div>
				<div class=\"formSep \">
					<label for=\"v_username\" class=\"control-label\">Partnerid:</label>
						<div class=\"controls\">
						<select name='partner_id'>
						<option value='1'>Select Partner</option>
						$select
						</select>
					</div>
			    </div>
				<div class=\"formSep \">
					<div class=\"controls\">
						<button class='btn' type='submit'>Search</button> <input type='checkbox' name='refund_date'>By Refund Date
					</div>
			    </div>
				</form>";
		return $form;
	}
	public function export($config,$method){
		
		$headers = implode(',',$config['tableheaders']). "\n";
		
		foreach($config['records'] as $record) :
			$headers .= implode(',',$record). "\n";
		endforeach;
		
		header("Content-type: application/csv");
		header("Content-Disposition: filename=$method.csv"); 
		echo $headers;
	}
}
?>