<?php

class Api_auth_model extends CI_Model {


	function get_api_user_salt($user) {
		
		$sql = 'SELECT password_salt FROM staff WHERE internal_alias = ? LIMIT 1';
		
		$result = $this->db->query($sql,array($user));
		
		if ($result->num_rows() < 1):
			
			return FALSE;
			
		endif;
		
		$row = $result->row();
		
		return $row->password_salt;
	
	}
	
	function validate_ip($ip) {
		
		$sql = 'SELECT ip_address FROM api_whitelisted_ips WHERE ip_address = INET_ATON(?) AND active = 1 LIMIT 1';
		
		$result = $this->db->query($sql,array($ip));
		
		if ($result->num_rows() < 1):
			
			return FALSE;
			
		endif;
		
		return TRUE;
		
	}
	

}