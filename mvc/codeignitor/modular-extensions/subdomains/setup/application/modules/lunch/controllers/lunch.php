<?php

class Lunch extends MX_Controller {


	protected 
		$_restaurants = array(
			'in' => array(
				'Cracker Barrel',
				'TGI Friday\'s',
				'Bob Evans',
				'Sakura',
				'Pad Thai',
				'Yellow Tail',
				'Old Carlina BBQ',
				'Mall Food Court',
				'Zoup',
				'The Rail',
				'Penn Station',
				'Winking Lizard',
				'Tres Potrillos',
				'Outback',
				'LongHorn',
				'Chili\'s',
				'Olive Garden',
				'Macaroni Grill',
				'Steak and Shake',
				'Red Lobster',
				'Applebee\'s',
			),
			'out' => array(
				'Chipotle',
				'Taco Bell',
				'McDonalds',
				'Honey Baked Ham',
				'Chick-fil-A',
				'Zoup',
				'Five Guys',
				'Swensons',
				'Wendy\'s',
				'Burger King',
			)
		),
		$_formatted = array();

	function __construct()
	{

		parent::__construct();

		foreach ($this->_restaurants as $type => $list):

			sort($list);

			foreach ($list as $restaurant):

				$key = preg_replace("/[^a-z0-9]/", '', strtolower($restaurant));
				
				$this->_formatted[$type][$key] = $restaurant; 

			endforeach;

		endforeach;

		$this->_restaurants = NULL;

	}


	public function index()
	{

		$data             = $this->_formatted;
		$data['combined'] = array_merge($this->_formatted['in'], $this->_formatted['out']);
		$data['winner']   = NULL;
		$data['selected'] = array();

		if ($this->input->post('submittered')):

			$selected = $this->input->post('restaurants');

			if ( ! $selected):

				$selected = array();

			endif;

			$data['selected'] = $selected;

			shuffle($selected);

			$data['winner'] = array_shift($selected);
			
		endif;

		$this->load->view('lunch', $data);

	}

}