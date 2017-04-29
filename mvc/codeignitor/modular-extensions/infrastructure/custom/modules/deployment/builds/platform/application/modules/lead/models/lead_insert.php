<?php

class Lead_insert extends CI_Model
{
	/**
	 * Partner Global Variables
	 * 
	 * @var array
	 */
	
	
	 function __construct() 
    {
        parent::__construct();
		$this->load->config('leads');
		$this->load->database();
    }

  
	
	public function insert_lead_data($post) {
		
		$lead_site_vertical_id = $post['lead_site_vertical_id'];
		
		if( ! $lead_site_vertical_id) :
			return false;
			exit(0);
		endif;
		
		$this->insert_address_data($post, $lead_site_vertical_id);
		$this->insert_alias($post, $lead_site_vertical_id);
		$this->insert_email_address($post, $lead_site_vertical_id);
		$this->insert_ip($post, $lead_site_vertical_id);
		$this->insert_phone($post, $lead_site_vertical_id);
		
		return $post;
	}
	
	/**
	 * insert into address table
	 * @param type $post
	 * @param type $lead_site_vertical_id
	 * @return boolean
	 */
	
	public function insert_address_data($post,$lead_site_vertical_id) {
		
		// check to see if address array exists
		if(isset($post['address'])) {
			
			// set address array to assoc
			$address = $post['address'];
			
			// create insert array
			$addArr = array(
				'address'	=> isset($address['address1'])	? $address['address1']: '',
				'address_2'	=> isset($address['address2'])	? $address['address2']: '',
				'city'		=> isset($address['city'])		? $address['city']: '',
				'zip'		=> isset($address['zip'])		? $address['zip']: '',
				'country'	=> isset($address['country'])	? $address['country']: '',
				'region'	=> isset($address['state'])	? $address['state'] : '',
				'lead_site_vertical_id'	=> $lead_site_vertical_id,
				'hash'=> isset($address['hash'])	? $address['hash'] : '',
				'date_added' => "NOW()"
			);
			
			$query  = $this->db->insert_string('leads.address',$addArr);
			$insert_query = str_replace('INSERT INTO ','INSERT IGNORE INTO ',$query);
			$this->db->query($insert_query);
		}
		return false;
	}
	
	/**
	 * Insert into email table
	 * @param type $post
	 * @param type $lead_site_vertical_id
	 * @return boolean
	 */
	public function insert_email_address($post,$lead_site_vertical_id) {
		
		
		// check to see if address array exists
		if(isset($post['email'])) {
			
			// set address array to assoc
			$email = $post['email'];
			
			// create insert array
			$addArr = array(
				'local'		=> isset($email['local'])	? $email['local']: '',
				'email'		=> isset($email['email'])	? $email['email']: '',
				'domain'	=> isset($email['domain'])	? $email['domain']: '',
				'tld'		=> isset($email['tld'])		? $email['tld']: '',
				'lead_site_vertical_id'	=> $lead_site_vertical_id,
				'date_added' => "NOW()"
			);
			
			$query = $this->db->insert_string('leads.email',$addArr);
			$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$query);
			$this->db->query($insert_query);
		}
		return false;
	}
	
	/**
	 * Insert into ip table
	 * @param type $post
	 * @param type $lead_vertical_id
	 * @return boolean
	 */
	public function insert_ip($post,$lead_site_vertical_id) {
		// check to see if address array exists
		if(isset($post['ip'])) {
			
			// set address array to assoc
			$ip = $post['ip'];
			
			// create insert array
			$addArr = array(
				'ip'			=> isset($ip['ip'])				? $ip['ip']: '',
				'country_code'	=> isset($ip['country_code'])	? $ip['country_code']: '',
				'country_code3'	=> isset($ip['country_code3'])	? $ip['country_code3']: '',
				'country_name'	=> isset($ip['country_name'])	? $ip['country_name']: '',
				'latitude'		=> isset($ip['latitude'])		? $ip['latitude']: '',
				'longitude'		=> isset($ip['longitude'])		? $ip['longitude']: '',
				'area_code'		=> isset($ip['area_code'])		? $ip['area_code'] : '',
				'dma_code'		=> isset($ip['dma_code'])		? $ip['dma_code'] : '',
				'metro_code'	=> isset($ip['metro_code'])		? $ip['metro_code'] : '',
				'region'		=> isset($ip['region'])			? $ip['region'] : '',
				'city'			=> isset($ip['city'])			? $ip['city'] : '',
				'continent_code'=> isset($ip['continent_code'])	? $ip['continent_code'] : '',
				'lead_site_vertical_id'	=> $lead_site_vertical_id,
				'date_added' => "NOW()"
			);
			
			$query = $this->db->insert_string('leads.ip',$addArr);
			$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$query);
			$this->db->query($insert_query);
		}
		return false;
	}
	
	/**
	 * Insert into phone table
	 * @param type $post
	 * @param type $lead_vertical_id
	 * @return boolean
	 */
	public function insert_phone($post,$lead_site_vertical_id){
		
		// check to see if phone array exists
		if(isset($post['phone'])) {
			
			// set address array to assoc
			$phone = $post['phone'];
			
			// create insert array
			$addArr = array(
				'phone'		=> isset($phone['input'])	? $phone['input']: '',
				'hash'		=> isset($phone['number'])	? $phone['number']: '',
				'lead_site_vertical_id'	=> $lead_site_vertical_id,
				'date_added' => "NOW()"
			);
			
			$query = $this->db->insert_string('leads.phone',$addArr);
			$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$query);
			$this->db->query($insert_query);
		}
		return false;
	}
	
	/**
	 * Insert into alias table
	 * @param type $post
	 * @param type $lead_site_vertical_id
	 * @return boolean
	 */ 
	public function insert_alias($post,$lead_site_vertical_id) {
		
		// check to see if alias array exists
		if(isset($post['name'])) {
			
			// set address array to assoc
			$alias = $post['name'];
			
			// create insert array
			$addArr = array(
				'prefix'			=> isset($alias['prefix'])		? $alias['prefix']: '',
				'first_name'		=> isset($alias['first'])		? $alias['first']: '',
				'middle_name'		=> isset($alias['middle'])		? $alias['middle']: '',
				'last_name'			=> isset($alias['last'])		? $alias['last']: '',
				'suffix'			=> isset($alias['suffix'])		? $alias['suffix']: '',
				'gender'			=> isset($alias['gender'])		? $alias['gender']: '',
				'hash'				=> isset($alias['hash'])		? $alias['hash'] : '',
				'lead_site_vertical_id'	=> $lead_site_vertical_id,
				'date_added'		=> "NOW()"
			);
			
			$query= $this->db->insert_string('leads.alias',$addArr);
			$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$query);
			$this->db->query($insert_query);
		}
		return false;
	}

        
        
        
        

}


