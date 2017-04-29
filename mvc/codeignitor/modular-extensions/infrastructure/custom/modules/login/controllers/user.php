<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->template->set_theme('brainhost');
	}

	public function index()
	{
		return $this->check();
	}
	
	public function check()
	{
		if
		(
			$this->session->userdata('uber_id')==$this->_check
				(
					$this->session->userdata('user'),
					$this->session->userdata('pass')
				)
			&& $this->session->userdata('uber_id')
		)
		{
			return true;
		}
		
		return $this->login();
	}
	
	private function _check($user, $pass)
	{
		$params=array
		(
			'user'=>$user,
			'pass'=>$pass
		);
		$response = $this->platform->post('ubersmith/client/login', $params);
		
		if ($response['success'])
		{
			return $response['data']['id'];
		}
		
		return false;
	}
	
	public function login()
	{
		$this->_logout();
		
		$header='Login';
		$template='login';
		
		if ($this->input->post())
		{
			if ($this->_login_submit())
			{
				if ($this->session->userdata('login_redirect'))
				{
					$redirect=$this->session->userdata('login_redirect');
					$this->session->unset_userdata('login_redirect');
			
					if (empty($redirect))	$redirect = '/';

					redirect($redirect);
					exit;
				}

				redirect('/');
				exit;
			}
			else
			{
				$header='Login - Incorrect';
			}
		}
		
		// initialize variables
		$data			= array();
		$data['header']	= $header;
		
		// set the layout to use
		$this->template->set_layout('bare');
		
		// set the page title
		//$this->template->title($this->lang->line('login_title'));
		$this->template->title($header);
		
		// display view/content
		$this->template->build($template, $data);
		
		return false;
	}
	
	private function _login_submit()
	{
		$id=$this->_check
		(
			$this->input->post('user'), $this->input->post('pass')
		);
		
		if ($id)
		{
			$this->session->set_userdata
			(
				array
				(
					'uber_id'	=>$id,
					'uber_user'	=>$this->input->post('user'),
					'uber_pass'	=>$this->input->post('pass')
				)
			);
			
			return true;
		}
		
		return false;
	}
	
	public function logout()
	{
		$this->_logout();
		
		return $this->login();
	}
	
	private function _logout()
	{
		$this->session->unset_userdata('uber_id');
		$this->session->unset_userdata('uber_user');
		$this->session->unset_userdata('uber_pass');
	}
}