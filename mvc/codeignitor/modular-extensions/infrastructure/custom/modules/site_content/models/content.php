<?php

class Content extends CI_Model {

	protected 
		$_images_api = 'http://images.brainhost.com/autobuild_images.php';

	function __construct()
	{
		parent::__construct();
	}

	function get_category_list()
	{

		$this->load->library('curl');

		$params = array('function' => 'get_category_list');

		$resp   = $this->curl->get($this->_images_api, $params);
		$resp   = json_decode($resp, TRUE);

		if ( ! is_array($resp) || ! isset($resp['success']) || ! $resp['success']):

			return FALSE;

		endif;

		return $resp['data']['cats'];

	}

	function sanitize_article_ids($article_ids)
	{
		if ( ! is_array($article_ids)):

			$article_ids = array(intval($article_ids));

		endif;

		$article_ids = array_filter($article_ids, 'is_numeric');

		if ( ! $article_ids):

			return FALSE;

		endif;

		return $article_ids;
	}

	function get_articles($category)
	{
		$resp = $this->platform->post(
			'site_content/get_articles',
			array(
				'category' => $category
			)
		);

		if ( ! is_array($resp) || ! isset($resp['success']) || ! $resp['success']):

			return FALSE;

		endif;

		$articles = $resp['data']['articles'];
		unset($resp);

		foreach ($articles as $key => $article):

			$articles[$key]['images'] = $this->get_article_images($article);

		endforeach;

		return $articles;

	}

	function get_article_images($article)
	{

		$params = array(
			'function'       => 'get_images',
			'category'       => $article['cat_slug'],
			'subcategoriess' => array_filter(array($article['child_cat_slug']))
		);

		$this->load->library('curl');

		$resp = $this->curl->get($this->_images_api, $params);
		$resp = json_decode($resp, TRUE);

		if ( ! is_array($resp) || ! isset($resp['success']) || ! $resp['success']):

			return FALSE;

		endif;

		return $resp['data']['images'];

	}

	function get_category_header($category, $title, $tagline)
	{

		$url = $this->_images_api.'?function=get_header_image&category='.urlencode($category).'&title='.urlencode($title).'&tagline='.urlencode($tagline);

		return $url;
	}

	function get_articles_categories($articles)
	{

		if ( ! $articles || ! is_array($articles)):

			return FALSE;

		endif;

		$categories = array();

		foreach ($articles as $article):

			$is_child          = (bool) $article['child_cat_name'];
			$slug              = ($is_child) ? $article['child_cat_slug'] : $article['cat_slug'];
			
			$categories[$slug] = array(
				'slug'   => $slug,
				'title'  => ($is_child) ? $article['child_cat_name'] : $article['cat_name'],
				'parent' => ($is_child) ? $article['cat_slug'] : NULL
			);

			if ($is_child):

				$categories[$article['cat_slug']] = array(
					'slug'   => $article['cat_slug'],
					'title'  => $article['cat_name'],
					'parent' => NULL
				);

			endif;

		endforeach;

		return $categories;
	}

}