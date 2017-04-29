<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Special
 * 
 * This class handles the functionality for MCSD Special Offer Page
 * 
 * 
 */
class Entry extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		// load config
		//$this->load->config('example');
	}

	public function index()
	{
		// show the default page
		$this->defaultEntry();
		
	}
	
	public function defaultEntry() {
	
		// determine if user is submitting the form
		if ($this->input->post()) return $this->_entry_submit();
		
		// set the layout to use
		$this->template->set_layout('bare');
		
		// append the CSS files
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/payroll/assets/css/main.css">');
		$this->template->append_metadata('<link href="/resources/modules/payroll/assets/css/ui-lightness/jquery-ui-1.8.23.custom.css" rel="stylesheet" type="text/css" />');
			
		// append the JS files	
		$this->template->append_metadata('<script src="/resources/modules/payroll/assets/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>');
			    			
		// display view/content
		// $this->template->build('payroll', $data);
		$this->template->build('entry');
		
	}
	
	private function _entry_submit()
	{
	
		$this->load->model('payroll/entries');
	
		// CONVERT POST DATA INTO PLATFORM FRIENDLY REQUEST
		
		$start_date = explode("/",$this->input->post('dateStart'));
		$end_date = explode("/",$this->input->post('dateEnd'));
		
		$start_date = $start_date[2]."-".$start_date[0]."-".$start_date[1];
		$end_date = $end_date[2]."-".$end_date[0]."-".$end_date[1];
						
		$payroll['date_start']		= $start_date;
		$payroll['date_end']		= $end_date;
		$payroll['department']		= $this->input->post('department');
		$payroll['expense_gross_payroll']	= $this->input->post('expenseGrossPayroll');
		$payroll['expense_benefit']	= $this->input->post('expenseCompanyBenefit');
		$payroll['expense_tax']		= $this->input->post('expenseCompanyTax');
			
		// add a ticket
		$result	= $this->entries->add($payroll);		
						
		// success page	
		redirect("payroll/entry/success");
			
	}
	
	public function success() {
		
	// set the layout to use
		$this->template->set_layout('bare');
		
	// append the CSS files
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/payroll/assets/css/main.css">');
		
	// display view/content
		$this->template->build('success');
	}
	
}