<?php 
ini_set("display_errors",'on');
class Assocsave extends MX_Controller
{
	
	function index(){
		
	}
	/**
	 * This controller recieves comma delimited roles and a user id. and then associates 
	 * them in the database
	 * Author : Jamie Rohr
	 * Date: 10-2-2012
	 *
	 */
	function rolestologins(){

		$id = $this->input->post('id');
		
		if (!empty($id)) :
			$post = array();
			foreach ($_POST as $key => $value) {
				$post[$key] = $value;
			}
			$members = explode(',', $post['members']);
			
			$config = array(
				'id'=>$id,
				'members'=>$members,
				'model' => 'roles',
				'method' => 'rolesToLogins'
			);
			
			$data = $this->platform->post("apadmin/assocsave/saveit",$config);
		
			echo json_encode($data);
			
		endif;
		
	}
	/**
	 * This controller recieves comma delimited users and a role id. and then associates 
	 * them in the database
	 * Author : Jamie Rohr
	 * Date: 10-2-2012
	 *
	 */
	function loginstoroles(){
		
		$id = $this->input->post('id');
		
		if (!empty($id)) :
			$post = array();
			foreach ($_POST as $key => $value) {
				$post[$key] = $value;
			}
			$members = explode(',', $post['members']);
			
			$config = array(
				'id'=>$id,
				'members'=>$members,
				'model' => 'roles',
				'method' => 'loginsToRoles'
			);
			
			$data = $this->platform->post("apadmin/assocsave/saveit",$config);
		
			echo json_encode($data);
			
		endif;
	}
	function privilegestoroles(){
		
		$id = $this->input->post('id');
		
		if (!empty($id)) :
			$post = array();
			foreach ($_POST as $key => $value) {
				$post[$key] = $value;
			}
			$members = explode(',', $post['members']);
			
			$config = array(
				'id'=>$id,
				'members'=>$members,
				'model' => 'roles',
				'method' => 'privilegesToRoles'
			);
			
			$data = $this->platform->post("apadmin/assocsave/saveit",$config);
		
			echo json_encode($data);
			
		endif;
	}
	function menutoroles(){
		
		$id = $this->input->post('id');
		
		if (!empty($id)) :
			$post = array();
			foreach ($_POST as $key => $value) {
				$post[$key] = $value;
			}
			$members = explode(',', $post['members']);
			
			$config = array(
				'id'=>$id,
				'members'=>$members,
				'model' => 'roles',
				'method' => 'menuToRoles'
			);
			
			$data = $this->platform->post("apadmin/assocsave/saveit",$config);
		
			echo json_encode($data);
			
		endif;
		
	}
	function rolestoprivileges(){
		
		$id = $this->input->post('id');
		
		if (!empty($id)) :
			$post = array();
			foreach ($_POST as $key => $value) {
				$post[$key] = $value;
			}
			$members = explode(',', $post['members']);
			
			$config = array(
				'id'=>$id,
				'members'=>$members,
				'model'	=> 'roles',
				'method' => 'rolesToPrivileges'
			);
			
			$data = $this->platform->post("apadmin/assocsave/saveit",$config);
		
			echo json_encode($data);
			
		endif;
		
	}
	function menuitemtorole(){
		
		$id = $this->input->post('id');
		
		if (!empty($id)) :
			$post = array();
			foreach ($_POST as $key => $value) {
				$post[$key] = $value;
			}
			$members = explode(',', $post['members']);
			
			$config = array(
				'id'=>$id,
				'members'=>$members,
				'model'	=> 'menudata',
				'method'=> 'menuItemToRoles'
			);
			
			$data = $this->platform->post("apadmin/assocsave/saveit",$config);
		
			echo json_encode($data);
			
		endif;
	}
}
?>