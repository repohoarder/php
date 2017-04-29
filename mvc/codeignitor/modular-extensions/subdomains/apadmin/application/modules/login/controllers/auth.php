<?php 
ini_set("display_errors",'on');

class Auth extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
    	// set message in login form
		if(  $this->input->get('logout') && ! $this->input->post('username')):
			$data['loggedin'] = 'You are now logged out.';
		endif;
		if( ! $this->input->post('username')):
			$data['loggedin'] = 'Welcome Back!';
		endif;
		
		// if login
		if($this->input->post()) :
			$auth = $this->platform->post('apadmin/users/login',$this->input->post());
			
			if( $auth['success']) :
				$this->_set_sessions($auth['data']);
				else:
					var_dump($auth);
					$data['loggedin'] = "Login failed";
			endif;
			
		endif;
		
		// if logged in redirect
		if ( $this->session->userdata('login_state') == TRUE ) 
    	{
    		// see if redirect session is set
    		$redirect 	= ($this->session->userdata('_redirect'))? $this->session->userdata('_redirect'): $this->config->item('subdir').'/home';

    		// redirect user
      		redirect($redirect);
    	}
		
		// set template layout to use
		$this->template->set_layout('logins');
		
		$data['title'] = "Administration  Login";
		$data['header'] = "Account Login";
		$data['postback'] = $this->config->item('subdir')."/login/auth";
		
		// load view
		$this->template->build('login/loginform', $data);
		
	}
	
	private function _set_sessions($userdata){
		
		$this->session->set_userdata('login_state', TRUE);
		
		foreach($userdata as $row) :
			
			$this->session->set_userdata('login_id', $row['id']);
			$this->session->set_userdata('name', $row['first_name'] .' ' . $row['last_name']);
			$this->session->set_userdata('email',$row['email']);
			if(!empty($row->google_auth)){
				$this->session->set_userdata('google_token',$row['google_auth']);
			}
			$this->load->library('menu');
			$menu['navigation'] = $this->menu->gettopmenu();
			$menu['navigationaccess'] =  $this->menu->getmenuaccess($row['id']);
			//$this->session->set_userdata($menu);
			//var_dump($this->session->all_userdata());
		endforeach;
	}
}
?>
