<?php
	if (!defined('BASEPATH'))
	{
		exit('No direct script access allowed');
	}

	class Getresponse extends MX_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
		}
	
		function index(){
			
		}
		function insert_active_leads(){
			
			$params = array('start_date' => date('Y-m-d', time() - 60*60*24*30),
				'end_date' => date('Y-m-d', time() )
				);
			//@mail('jamie.rohr@brainhost.com','getcron',$params);
			$response = $this->platform->post('leads/getresponse_active/insert',$params);
			
			var_dump($response);
		}
		
		function move_refunded(){
			
			$params = array('start_date' => date('Y-m-d', time() - 60*60*24*30),
				'end_date' => date('Y-m-d', time() )
				);
			//@mail('jamie.rohr@brainhost.com','getcron','$params');
			$response = $this->platform->post('leads/getresponse_move/moveit',$params);
			
			
			var_dump($response);
		}
	}

