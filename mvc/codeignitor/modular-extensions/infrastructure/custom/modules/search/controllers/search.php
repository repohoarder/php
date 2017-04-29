<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MX_Controller {
	
	function __construct()
	{
		
		parent::__construct();
		
	}
	
	function index() 
	{
			
		//$this->template->set_cache(2592000);
		$this->template->set_theme('brainhost');

		$this->template->set_layout('bare');
		
		$this->lang->load('domain_form');
		
		$data['searched_domain']['full'] = 'exampledomain.com';
		$data['searched_domain']['sld'] = 'exampledomain';
		$data['searched_domain']['tld'] = 'com';
		
		$this->template->title($this->lang->line('domain_form_title'));
		
		$this->template->prepend_footermeta('<script src="/resources/brainhost/js/search3.js"></script>');
		
		$this->template->build('domain_search', $data);
	
	}

	function test_services()
	{

		$this->load->library('services');

		$type = 'client';
		$id = '161542';
		$term = 'yearly';
		$service_code = 'addon_domain';
		$variant = 'net';
		$service_options = array(
			'name' => 'TEST PLATFORM API YO',
			'metadata' => array(
				'traffic_hits' => 'test',
				'parent' => '1436553'
			)
		);
		
		$response = $this->services->add($type, $id, $service_code, $term, $variant, $service_options);

		var_dump($response);

	}

	function test_layout($layout = 'bare')
	{
		
		$this->template->set_layout($layout);
		$this->index();

	}
	
}


/* End of file search.php */
/* Location: ./application/modules/homepage/controllers/search.php */