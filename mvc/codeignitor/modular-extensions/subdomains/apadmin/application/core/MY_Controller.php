<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This class extends the controller and handles all session activity
 *
 */
class MY_Controller extends MX_Controller 
{
	public function __construct()
	{
		parent::__construct();
    	// ensure already signed in
    	if ( $this->session->userdata('login_state') == TRUE ) :
		$data = new stdClass();
		$this->load->library('menu');
    	$data->config_changetemplate = $this->menu->changeTemplate();
    	$data->config_menutop = $this->menu->renderTop();
    	$data->config_sidebar =  $this->menu->sideBar();
    	$data->config_username = $this->session->userdata('name');
    	$this->load->vars($data);
		endif;
	}
}
