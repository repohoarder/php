<?php

class General extends MX_Controller
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
	
	function index()
	{
		// check privileges
		$target = 'generalreport';
		$this->pageauth->checkprivileges($target);
		
		$lastday = $this->input->post('lastday') ;
		$lastday = $this->input->get('lastday') ? $this->input->get('lastday') : $lastday;

		$firstday = 0;

		if ($this->input->post('start_range') && $this->input->post('end_range')):

			$start_range = $this->input->post('start_range') .' 00:00:01';
			$end_range   = $this->input->post('end_range')   .' 23:59:59';

			$lastday     = ceil(abs(time() - strtotime($start_range)) / 86400);
			$firstday    = ceil(abs(time() - strtotime($end_range)) / 86400);

		endif;
		
		$methods = array('mostsales'=>'Most Sales','mostattempts'=>'Most Attempts','mostvisitors'=>'Most Visitors','novisitors'=>'No Visitors','nosalesattempts'=>'No Sales Attempts');
		$partner_id = $this->input->post('partner_id') ? $this->input->post('partner_id') : 1 ;
		
		foreach($methods as $method=>$title):
			$configArr[$method] = $this->_get_report($method,$lastday, $firstday);
		endforeach;
		
		$breadcrumb = array("Sales Report"=>"");
		//set data to load into view
		$data = array();
		$data['table'] = $this->_form();
		$data['pagetitle'] = "Roles";
		$data['breadcrumb'] = $this->menu->createBreadCrumb($breadcrumb);
		
		$x=1;
		
		foreach($configArr as $method=>$config) :
			
			$export = "<a href='?export=true&method=$method&lastday=$lastday'><i class=\"splashy-document_small_download\"></i></a>";
			$target = 'report'.$x;
			$x++;
			$data['table'] .= "<br><div class=\"alert alert-info\">".$methods[$method]."</div>". $this->tablebuild->generateTable($target,$config,array(),$export);
			
			// export the code
			if($this->input->get('export')) :
					if($this->input->get('method') == $method) :
						$this->export($config,$method);
						exit();
					endif;
			endif;
		endforeach;
		$data['javascriptsave'] = '$(document).ready(function() {';	
		$data['addmodal'] = '';		
		// set template layout to use
		$this->template->set_layout('default');
		
		// load view
		$this->template->build('administration/users', $data);
	}

	public function parnter_sales_count()
	{

		$start = date('Y-m-d',strtotime('-1 month'));
		$end   = date('Y-m-d');

		if ($this->input->post('start_date')):

			$start = $this->input->post('start_date');
			$end   = $this->input->post('end_date');

		endif;

		$resp = $this->platform->post(
			'partner/statistics/admin_reports/sales_counts',
			array(
				'start_date' => $start,
				'end_date'   => $end
			)
		);

		$data['rows']  = $resp['data']['rows'];
		$data['start'] = $start;
		$data['end']   = $end;

		$this->template->set_layout('default');
		$this->template->build('reports/sales_count', $data);


	}
	
	private function _get_report($method,$lastday, $firstday = 0){
		
		// get report
		
		$report = $this->platform->post(
				'partner/statistics/admin_reports/'.$method,
				array(
					'login_id' => $this->session->userdata('login_id'),
					'lastday'  => $lastday,
					'firstday' => $firstday
				)
			);
		
		
		$reports = $report['data'];
		// array keys of return data;
		if(!isset($reports[0])) :
			//echo $method;
			return;
		endif;
		
		$tableFields = array_keys($reports[0]);
		
		// create main config Array
		$configArr = array(
				'build'		=>	array(),
				"post"		=>	array(),
				"required"	=>	array(),
				'assoc'		=>	array(),
				'records'	=>	$reports,
				'tableheaders'=>$tableFields,
				'datatable' =>'dTableR'
		);
		
		return $configArr;
	}
	
	
	public function _form(){
		
		$a='';
		$b='';
		$c='';
		$d='';
		
		switch($this->input->post('lastday')) {
			
			case '1' :
				$a =' checked';
			break;
			case '7' :
					$b =' checked';
				break;
			case '30' :
					$c =' checked';
				break;
			case '' :
					$d =' checked';
				break;
		}

		$start_range = ($this->input->post('start_range')) ? $this->input->post('start_range') : date('Y-m-d',strtotime('-6 weeks'));
		$end_range   = ($this->input->post('end_range')) ? $this->input->post('end_range') : date('Y-m-d',strtotime('-2 weeks'));

		$form = "<form method='post' action='' style='display:block;float:left;width:30%'>
			<input type='radio' name='lastday' value='1'$a> Last 24 hrs<br>
			<input type='radio' name='lastday' value='7'$b> Last 7 Days<br>
			<input type='radio' name='lastday' value='30'$c> Last 30 Days<br>
			<input type='radio' name='lastday' value=''$d> All Time<br>
			<input type='submit' value='search'>
			</form>


			<form method='post' action='' style='display:block;float:left;width:30%'>
				<input type='text' name='start_range' value='".$start_range."'><br>
				<input type='text' name='end_range' value='".$end_range."'><br>
				<input type='submit' value='search'>
			</form>

			<div style='clear:both;'></div>
			";
		
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
