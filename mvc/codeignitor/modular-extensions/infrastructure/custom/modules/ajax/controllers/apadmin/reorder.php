<?php 
ini_set("display_errors",'on');
class Reorder extends MX_Controller
{ 
	
	function index(){
		//load empty page 
		
		$data['response'] = 'hello';
		$this->load->view('ajax_display',$data);
	}
	function menus(){
		
		
		$parent_id = $this->input->post('parent_id');
		
		$children = str_replace('"', '', $this->input->post('children'));
		$children = str_replace('\\', '', strip_tags(html_entity_decode($children)));
		$children = str_replace('record_', '', $children);
		$children = explode(',', $children);
		
		$post = array("parent_id"=>$parent_id,
					   'children'=>$children
					 );
		var_dump($post);
		$data = $this->platform->post('apadmin/menu/reorder',$post);
		
		
		
	}
}