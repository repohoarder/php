<?php 

class Language extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($language='english',$module=FALSE,$controller=FALSE,$method=FALSE,$params=FALSE)
	{
		// initialize variables
		$params 	= json_decode(urldecode($params),TRUE);

		// set language to session
		$this->session->set_userdata('_language',$language);

		// create redirect URL
		$redirect 	= $module.'/'.$controller.'/'.$method.'/';

		// iterate parameters
		foreach ($params AS $key => $value):

			// add item to redirect URL
			$redirect	.= $value.'/';

		endforeach;

		redirect($redirect);
		return;
	}
}