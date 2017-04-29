<?php

/*
 * Payroll Entries Model
 * 
 * This class handles payroll entries
 * 
 * @author	Jakob Ward	<jakob.ward@brainhost.com>
 * @version	1.0	September 26,2012
 * 
 * @payroll Payroll Entry
 * 
 * @method	array	add()	This method saves a new payroll entry
 * 
 */
class Entries extends CI_Model 
{
		
    function __construct() 
    {
        parent::__construct();
	}
	
	/*
	 * Add a Payroll Entry
	 * 
	 * This method adds a payroll entry 
	 * 
 * @author	Jakob Ward	<jakob.ward@brainhost.com>
	 * 
	 * @access	public
	 * 
	 * @example	add($payroll)
	 * 
	 * @param	array	$vars		Payroll Entry Post Variables
	 * 
	 * @return	int
	 */
	 
	public function add($payroll=array())
	{
	
		// load DB
			$this->newdb	= $this->load->database('brainhost',true);
				
		// error handling
			if ( ! $payroll['date_start'])	return $this->lang->line('invalid_start_date').$this->error->code($this, __DIR__,__LINE__);
			if ( ! $payroll['date_end'])	return $this->lang->line('invalid_end_date').$this->error->code($this, __DIR__,__LINE__);	
		
		// save query
						
			$sql = "
			
				INSERT INTO payroll_entries (
					date_start,
					date_end,
					date_created,
					department,
					expense_gross_payroll,
					expense_benefit,
					expense_tax
				) VALUES ('"
					.$payroll['date_start']."','"
					.$payroll['date_end']."',
					NOW(),
					'".$payroll['department']."',"
					.$payroll['expense_gross_payroll'].","
					.$payroll['expense_benefit'].","
					.$payroll['expense_tax']."
					)";
				
		// save
			$results = $this->newdb->query($sql);
						
		return $results;
		
	}

	
}