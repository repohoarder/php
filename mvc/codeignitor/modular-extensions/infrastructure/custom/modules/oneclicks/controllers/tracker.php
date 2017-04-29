<?php

class Tracker extends MX_Controller {

	protected 
		$_params   = array(),
		$_errors   = array(),
		$_required = array(
			'client_id', 
			'slug', 
			'source'
		),
		$_response = array();

	function __construct()
	{

		parent::__construct();
		
		$req  = array_combine($this->_required, array_fill(0, count($this->_required), NULL));
		$post = ($this->input->get_post(NULL, TRUE) ? $this->input->get_post(NULL, TRUE) : array());

		$this->_params = array_merge(
			$req,
			array_intersect_key(
				$post,
				$req
			)
		);

		if (count(array_filter($this->_params)) < count($this->_required)):

			$this->_errors[] = 'Missing required fields';

		endif;

	}

	function index()
	{

		header('Content-type: application/json');
		echo json_encode($this->_response);
		return;
	}

	function link_view()
	{

		if (count($this->_errors)):

			$this->_response = array(
				'success' => 0,
				'error'   => $this->_errors,
				'data'    => array()
			);

			return $this->index();

		endif;

		$this->_params['version'] = 'default';

		if ($this->input->get_post('version')):
			$this->_params['version'] = $this->input->get_post('version');
		endif;

		$this->_response = $this->platform->post(
			'sales_funnel/oneclicks/track_link_view',
			$this->_params
		);

		return $this->index();


	}

}