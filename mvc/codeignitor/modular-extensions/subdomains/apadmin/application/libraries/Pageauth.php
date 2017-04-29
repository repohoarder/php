<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Pageauth {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
	/**
	 * Sets user privileges for the current page (identified by $tablename)
	 * @param string $tablename : the table to which privileges apply
	 */
	public function checkPrivileges($tablename){
		
		$this->setPrivileges($tablename);
		
		if(!$this->loginHasPrivileges('view')):
			
			$this->loginFailedPrivileges();
		
		endif;
	}
	public function setPrivileges($tablename) {
		
		// initialize userid and unset privileges
		$login_id = $this->CI->session->userdata('login_id');
		$this->CI->session->unset_userdata('privileges');
		
		// set all default privileges to false
		$privileges = array();
		$privileges['view'] = false;
		$privileges['add'] = false;
		$privileges['edit'] = false;
		$privileges['delete'] = false;
		
		$privs = $this->CI->platform->post('apadmin/pageauth/setprivileges', array('tablename'=>$tablename,'login_id' =>$login_id));
		
		if( $privs['success']) :
			$privileges = $privs['data'];
		endif;
		// set session privileges
		$this->CI->session->set_userdata('privileges', $privileges);
		
		
	}
	
	/**
	 * Does login have privileges for a particular action (add, edit, view, delete) for the
	 * current page?
	 * @param string $action
	 * @return boolean
	 */
	public function loginHasPrivileges($action = '') {
		
		$valid = true;
		
		$valid &= $this->CI->session->userdata('login_state');
		
		if (!empty($action)) :
			
			$privileges = $this->CI->session->userdata('privileges');
			$valid &= $privileges[$action];
			
		endif;
		
		return $valid;	
	}
	
	/**
	 * Login has failed view privileges - redirect to home page or login as applicable
	 */
	private function loginFailedPrivileges() {
		
    	//redirect( "/accessdenied" );
	}
}