<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Homepage extends MX_Controller {
	
	function test_lang($lang = '')
	{
		
		$this->config->set_item('language', $lang);
		
		$this->index();
		
	}
	
	function test_layout($layout)
	{
		
		$this->template->set_layout($layout); 
		
		$this->index();
		
	}

	function index() 
	{
		
		$this->lang->load('homepage');
		
		$this->load->library('template'); 
		
		
		Modules::run('http://clients.brainhost.com/sidebar/test');
		
		
		$data['headline'] = $this->lang->line('homepage_headline', 'Brain Host', 'Travis');
	
		$this->template->build('welcome_message', $data);
		
		
	
	}

}


/* End of file homepage.php */
/* Location: ./application/modules/homepage/controllers/homepage.php */