<?php

class Site_content extends MX_Controller {

	var $_response = array(
		'success' => 0,
		'error'   => array(),
		'data'    => array()
	);

	function __construct()
	{
		parent::__construct();

		$this->load->model('content');
	}

	function import()
	{

		// site_content/import
		// 	?category=anti_aging
		// 	&domain=domain.com
		// 	&site_title=This%20is%20a%20headline
		// 	&site_tagline=This%20is%20a%20subheadline

		$this->load->library('curl');

		$category = $this->input->get_post('category');
		$domain   = $this->input->get_post('domain');
		
		$title    = $this->input->get_post('site_title');
		$tagline  = $this->input->get_post('site_tagline');
		
		$url      = 'http://'.$domain.'/site_import.php';

		$resp     = $this->curl->get(
			$url,
			array(
				'category'     => $category,
				'site_title'   => $title,
				'site_tagline' => $tagline
			)
		);


		$resp            = trim($resp);

		$this->_response = array(
			'success' => (bool) $resp,
			'error'   => array(),
			'data'    => array(
				'category'     => $category, 
				'site_title'   => $title,
				'site_tagline' => $tagline,
				'domain'       => $domain,
				'url'          => $url,
				'resp'         => $resp
			)
		);

		return $this->index();

	}

	function get($category = NULL)
	{

		if ( ! $category):

			$category    = $this->input->get_post('category');

		endif;

		$title   = $this->input->get_post('site_title');
		$tagline = $this->input->get_post('site_tagline');

		if ( ! $category):

			$this->_response = array(
				'success' => 0,
				'error'   => array(
					'Invalid category provided'
				),
				'data'    => array()
			);

			return $this->index();

		endif;

		$header   = $this->content->get_category_header($category, $title, $tagline);

		$articles = $this->content->get_articles($category);

		if ( ! $articles):

			$this->_response = array(
				'success' => 0,
				'error'   => array(
					'Unable to retrieve articles'
				),
				'data'    => array()
			);

			return $this->index();

		endif;

		$categories = $this->content->get_articles_categories($articles);

		//$cat_list   = $this->content->get_category_list();

		$this->_response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array(
				'header'        => $header,
				'site_title'    => $title,
				'site_tagline'  => $tagline,
				'categories'    => $categories, 
				'articles'      => $articles,
				//'category_list' => $cat_list
			)
		);

		return $this->index();

	}

	function get_category_list()
	{

		$cat_list   = $this->content->get_category_list();

		$this->_response = array(
			'success' => intval((bool) $cat_list),
			'error'   => array(),
			'data'    => array(
				'category_list' => $cat_list
			)
		);

		return $this->index();
	}

	function index()
	{

		echo json_encode($this->_response);

	}

}




