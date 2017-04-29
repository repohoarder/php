<?php 
ini_set("display_errors",'on');
class Assoc extends MX_Controller
{
	
	function index(){
		echo "Hey man! Got any grapes?";
	}
	function rolestologins(){
		
		$id = $this->input->get('id');
		
		$data = $this->platform->post('apadmin/assoc/rolestologins',array('id'=>$id));
		
		if($data['success']):
			
			$json = $data['data'];
			echo json_encode($json);
		else :
			echo $json['error'] = "no records found";
			
		endif;
		
	}
	function loginstoroles(){
		
		$id = $this->input->get('id');
		
		$data = $this->platform->post('apadmin/assoc/loginstoroles',array('id'=>$id));
		
		if($data['success']):
			
			$json = $data['data'];
			echo json_encode($json);
		else :
			echo $json['error'] = "no records found";
			
		endif;
	}
	function privilegestoroles(){
		$id = $this->input->get('id');
		
		$data = $this->platform->post('apadmin/assoc/privilegestoroles',array('id'=>$id));
		
		if($data['success']):
			
			$json = $data['data'];
			echo json_encode($json);
		else :
			echo $json['error'] = "no records found";
			
		endif;
	}
	function menutoroles(){
		
		$login_id = $this->session->userdata('login_id');
		$role_id = $this->input->get('id');
		
		$menu = $this->platform->post("apadmin/menu/getmodaldata", array('role_id'=>$role_id,'login_id' => $login_id));
		$menulist = $menu['data'];
		
		$html = "<table width:100%;'>
					<tr>
						<th style='text-align:left;font-size:1.2em;;'>Top Menu</th>
						<th style='text-align:left;font-size:1.2em;'>Sidebar Menu</th>
					</tr>
						<td valign='top'>";
		$html .= $this->menu->startTopCheck($menulist['TOP']);
		$html .="		</td>
						<td valign='top'>";
		$html .= $this->menu->startTopCheck($menulist['SIDEBAR']);
		$html .= "		</td>
					</tr>
				</table>";
		
		echo $html;
		
	}
	function rolestoprivileges(){
		$id = $this->input->get('id');
		
		$data = $this->platform->post('apadmin/assoc/rolestoprivileges',array('id'=>$id));
		
		if($data['success']):
			
			$json = $data['data'];
			echo json_encode($json);
		else :
			echo $json['error'] = "no records found";
			
		endif;
	}
	function menuitemtorole(){
		$id = $this->input->get('id');
		
		$data = $this->platform->post('apadmin/assoc/menuitemtorole',array('id'=>$id));
		
		if($data['success']):
			
			$json = $data['data'];
			echo json_encode($json);
		else :
			echo $json['error'] = "no records found";
			
		endif;
	}
}
?>