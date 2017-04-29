<?php

class Recurring extends MX_Controller
{
	/**
	 * The array of PArtner Information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}

	public function index($brand='allphase',$error=false)
	{
		$data = array();
		$data['plans'] = array();
		$merged_data = array();
		//initialize variables
		$brand  = $this->input->post('brand') ? $this->input->post('brand') : $brand;
		$default_plan = $this->input->post('plan_id') ? $this->input->post('plan_id') : 'Web Hosting';
		$month = $this->input->post('month') ?  $this->input->post('month'): date('m',time());
		$year = $this->input->post('year') ?  $this->input->post('year'): date('Y',time());
		
		// set start and end dates
		$start = "$year-$month-01";
		$end = date('Y-m-t',strtotime($start)); // this is a neat feature i just learned. gets the last day of the month...
		
		$params = array(
			'start_date' => $start,
			'end_date'   => $end,
			'plan' => $default_plan,
			'partner_id' => $this->_partner['id']
		);
		
		
		$sel_brand = 'allphase';
		
		// get plans
		$plans = $this->platform->post('crm/reports/getplans/get/'.$sel_brand,array());
		
		// set plans
		if($plans['success']) :
			$data['plans'] = $plans['data'][$sel_brand];
		endif;
		
		// get inital sales
		$initial  = $this->platform->post('crm/reports/renewals/revenue_initial/'.$sel_brand, $params);
		
		if($initial['success']) :
			$merged_data = $initial['data'][$sel_brand];
			//echo"<pre>";print_r($merged_data[24]['ids']);echo"</pre>";
		endif;
		
		$data['skipfilters'] = true;
		// set template variables
		$data['sel_brand'] = $sel_brand;
		$data['default_plan'] = $default_plan;
		$data['month'] = $month;
		$data['year'] = $year;
		$data['report'] = $merged_data;
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Recurring Revenue');

		// set data variables
		$data['error']       = urldecode($error);
		
		$data['partner_id']  = $this->_partner['id'];
		
       

		$this->template->build('statistics/recurring', $data);
	}

	/**
	 * This method logs in a user and sets needed sessions
	 * @return boolean
	 */
	private function _submit()
	{
		$this->debug->show($this->input->post(),true);
		// initialize variables
		
		redirect('statistics/calls');
	}
}
