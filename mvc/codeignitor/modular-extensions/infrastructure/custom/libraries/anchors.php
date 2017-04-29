<?php

class Anchors {
	
	private $_ci;
	
	function __construct()
	{
		
		$this->_ci = &get_instance();
		$this->_ci->lang->load('anchors');
		$this->_ci->load->config('anchors_hrefs');
		
	}
	
	function get_link($slug)
	{
		
		return $this->_ci->config->item('anchor_'.$slug);
		
	}
	
	function get_text($slug)
	{
		
		return $this->_ci->lang->line('anchor_'.$slug);
		
	}
	
}