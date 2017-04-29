<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sidebar extends MX_Controller {
	
	/*
	 * Current working module
	 * 
	 * @var string
	 */	
	var $module	= '';
	
	/*
	 * Array of JS files to include
	 * 
	 * @var array
	 */
	var $js		= array();
	
	/*
	 * Array of CSS files to include
	 * 
	 * @var array
	 */
	var $css	= array();
	
	function test()
	{
		
		echo 'testing';
		exit();
		
	}
	
    function __construct() 
    {
        parent::__construct();
        
		// get the module we are currently working in
		$this->module		= $this->uri->segments[1];
	}
	
	public function index()
	{
		return $this->original();
	}
	
	public function original()
	{
		// set global js
		$this->js	= array(
			//'/'.$this->module.'/assets/javascript/script.js'
		);
		
		// set global css
		$this->css	= array(
			//'/'.$this->module.'/assets/css/style.css'
		);

		// show view
		return $this->output('default');
	}

	private function output($header='default')
	{
		
		// make sure file exists
		if( ! file_exists(getcwd().'/'.APPPATH.'modules/'.$this->module.'/views/'.$header.'.php')) show_404();
		
		// grab assets
		$data['js']		= $this->js;
		$data['css']	= $this->css;
		
		// load the file
		$this->load->view($this->module.'/'.$header,$data);
	}
	
}