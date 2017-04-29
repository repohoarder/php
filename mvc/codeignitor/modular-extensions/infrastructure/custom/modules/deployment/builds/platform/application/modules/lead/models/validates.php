<?php

class Validates extends CI_Model
{
	/**
	 * Partner Global Variables
	 * 
	 * @var array
	 */

	
    function __construct() 
    {
        parent::__construct();
		$this->load->database();
    }

   
	
	public function search_for_lead($post) {
		
		if( isset($post['email'])) :
			
			$email = $post['email']['email'];
		
			$sql = "SELECT t2.lead_id,t1.lead_site_vertical_id 
				FROM leads.email AS t1 
				INNER JOIN 
					leads.lead_site_vertical as t2 on t1.lead_site_vertical_id=t2.id
				WHERE t1.email='$email'";
		
			$result = $this->db->query($sql);
			if($result->num_rows() > 0) :

				$row = $result->row_array();
				return $row['lead_id'];
				
			endif;
			
		endif;
		if(isset($post['name']['hash']) && isset($post['phone']['number'])) :
			
		endif;
		
		if(isset($post['name']['hash']) && isset($post['address']['hash'])) :
			
		endif;
		
		return false;
	}
	/**
	 * run some checks if the lead exists already
	 * @param type $post
	 * @return array();
	 */
	public function existing_lead($post=array()){
		
		
		$lead_id = $post['lead_id'];
		$vertical_id = $post['site_vertical_id'];
		
		
		// if lead doesnt exist generate a new one
		if ( ! $this->does_lead_exist($lead_id) ) :
		
			$lead_id = $this->insert();
			$post['lead_id'] = $lead_id;
			
		endif;
		
		$post['lead_site_vertical_id'] = $this->insert_leads_sites_verticals($lead_id,$vertical_id);
		return $post;
	}
	
	/**
	 * see if lead exists
	 * @param type $lead_id
	 * @return boolean
	 */
	public function does_lead_exist($lead_id){
		
		// check to see if this lead exists
		$result = $this->db->query("SELECT * FROM leads.lead WHERE id='$lead_id'");
		
		if($result->num_rows() > 0) :
			return true;
		else:
			return false;
		endif;
	}
	
	public function does_lead_vertical_exist($id){
		
		// check to see if this lead vertical id exists along with the actual lead id . 
		$result = $this->db->query("SELECT t1.lead_id FROM leads.lead_site_vertical as t1 INNER JOIN leads.lead as t2 on t1.lead_id=t2.id WHERE t1.id='$id'");
		
		if($result->num_rows() > 0) :
			
			$row = $result->row();
			return $row->lead_id;
			
		else:
			return false;
		endif;
	}
	public function existing_vertical_id($post){
		
		$lead_v_id  = $post['lead_site_vertical_id'];
		
		$lead_id = $this->does_lead_vertical_exist($lead_v_id);
		
		if( ! $lead_id ) :
			
			$lead_id = $this->insert();
		
			$post['lead_id'] = $lead_id;
			
			$vertical_id = $post['site_vertical_id'];
			
			$post['lead_site_vertical_id'] = $this->insert_leads_sites_verticals($lead_id,$vertical_id);
			
		endif;
		
		return $post;
	}
	  /**
     * insert new lead and retrieve LEAD ID
     * Author: Jamie Rohr
     * Date : 12/20/2012
     * @return type array
     */
	public function insert()
	{
        // insert new lead
		$post['date_added'] = "NOW()";
		$this->db->insert('leads.lead',$post);
		$id = $this->db->insert_id();
		return $id;
             
	}
   
	/**
	 * generate a new lead vertical id
	 * @param type $lead_id
	 * @param type $vertical_id
	 * @return type
	 */
	public function insert_leads_sites_verticals($lead_id,$vertical_id){
		
		$result = $this->db->query("SELECT * FROM leads.lead_site_vertical WHERE lead_id=$lead_id AND site_vertical_id=$vertical_id");
		if($result->num_rows() > 0) :
			
			$row = $result->row();
			return $row->id;
			
		else:
			// insert new lead
			$post = array( 
				'date_added'=>"NOW()",
				'lead_id' => $lead_id,
				'site_vertical_id'=> $vertical_id
			 );
			$this->db->insert('leads.lead_site_vertical',$post);
			$id = $this->db->insert_id();
			// return the primary key
			return $id;
	   endif;
	}
	
	public function auth_key($key){
		
		// check for key
		$result = $this->db->query("SELECT * FROM leads.site WHERE `key`='$key'");
		
		if($result->num_rows() > 0 ):
			$row = $result->row_array();
			return $row['id'];
		else:
			return false;
		endif;
	}
	
	public function validate_vertical_id($id,$sid) 
	{
		
		// check to see if vertical id is linked to site id
		$result = $this->db->query("SELECT * FROM leads.site_vertical WHERE vertical_id='$id' AND site_id='$sid'");
		
		if($result->num_rows() > 0 ):
			$row = $result->row_array();
			return $row['id'];
		else:
			return false;
		endif;
	}
	
	public function get_vertical_id_by_slug($post){
		
		$slug = $post['vertical_slug'];
		// check to see if vertical id is linked to site id
		$result = $this->db->query("SELECT * FROM leads.vertical WHERE slug='$slug'");
		
		if($result->num_rows() > 0 ):
			$row = $result->row_array();
			return $row['id'];
		else:
			return false;
		endif;
	}
}



