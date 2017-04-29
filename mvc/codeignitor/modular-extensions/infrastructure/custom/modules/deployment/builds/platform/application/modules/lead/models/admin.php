<?php

class Admin extends CI_Model
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
	
	public function insert_site($post){
		
		$insert = array(
			'url' => $post['url'],
			'key' => $post['key'],
			'key_salt' => $post['key_salt']
		);
		
		$this->db->insert('leads.site',$insert);
		$id = $this->db->insert_id();
		return $id;
		
	}
	public function edit_site($post){
		
		$insert = array(
			'url' => $post['url'],
			'key' => $post['key'],
			'key_salt' => $post['key_salt']
		);
		
		$this->db->update('leads.site',$insert,array('id'=>$post['site_id']));
		$id = $this->db->insert_id();
		return $id;
		
	}
	
	
	public function insert_sites_verticals($post){
		
		$insert = array(
			'site_id' => $post['site_id'],
			'vertical_id' => $post['vertical_id'],
			'date_added' => 'NOW()'
			
		);
		
		$query  = $this->db->insert_string('leads.site_vertical',$insert);
		$insert_query = str_replace('INSERT INTO ','INSERT IGNORE INTO ',$query);
		$this->db->query($insert_query);
		$id = $this->db->insert_id();
		return $id;
	}
	
	public function insert_vertical($post){
		
		$insert = array(
			'name' => $post['name'],
			'slug' => $post['slug'],
			'description' => $post['description'],
			'date_added' => 'NOW()'
		);
		
		$this->db->insert('leads.vertical',$insert);
		$id = $this->db->insert_id();
		return $id;
	}
	
	public function edit_vertical($post){
		
		$insert = array(
			'name' => $post['name'],
			'slug' => $post['slug'],
			'description' => $post['description'],
			'date_added' => 'NOW()'
		);
		
		return $this->db->update('leads.vertical',$insert,array('id'=>$post['vertical_id']));
		
	}
	public function getsites($id=false){
		$where='';
		if($id) :
			$where = " WHere id=$id";
		endif;
		return 	$this->db->query('select * from leads.site '.$where.' order by url ASC')->result_array();
	}
	public function getvertical($id=false){
		$where='';
		if($id) :
			$where = " WHere id=$id";
		endif;
		return 	$this->db->query('select * from leads.vertical '.$where.' order by name ASC')->result_array();
	}
	
	public function sitetoverticals($id) {
		$sql = "SELECT r.id, r.name, IFNULL(l2r.site_id, '0') AS member FROM leads.vertical r
			LEFT JOIN leads.site_vertical l2r ON l2r.site_id = $id AND l2r.vertical_id = r.id";
		return 	$this->db->query($sql)->result_array();
	}
}
