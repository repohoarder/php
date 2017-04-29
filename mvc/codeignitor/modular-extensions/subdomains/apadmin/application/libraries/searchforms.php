<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library will create a 
 * The configArr must consist
 * Author : Jamie Rohr
 * Date : 10-02-2012
 * 
 *
 */
class Searchforms {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
		$this->CI->load->library('wizardforms');
		
	}
	
	
	public function start_form(){
		
		return '<form method="post" action="">';
	}
	public function search_partner($array){
		
		
	}
	
	public function date_range($start,$end){
		
		
		$range = $this->CI->createWizardClassInput(array('title'=>'Start Date','fieldname'=>'start_date','helpblock'=>'','default'=>$start),'datepicker');
		$range .= $this->CI->createWizardClassInput(array('title'=>'End Date','fieldname'=>'end_date','helpblock'=>'','default'=>$end),'datepicker');
		
		return $range;
	}
	
	public function end_form(){
		return "</form>";
	}
	
}
