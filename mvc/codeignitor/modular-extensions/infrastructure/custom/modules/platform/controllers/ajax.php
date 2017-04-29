<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MX_Controller {

	protected $_output_type = 'json';
	protected $_output = '';

	function __construct()
	{

		if ($this->input->post('output_type')):

			$this->_output_type = $this->input->post('output_type');

		endif;

	}

	function index()
	{

		// set data variable
		$data['output']	= $this->_output;
		
		// output json
		$this->load->view($this->_output_type, $data);

	}

	function post()
	{

		$api_method    = $this->input->post('api_method');
		$api_params    = $this->input->post('api_params');
		
		$this->_output = $this->platform->post($api_method, $api_params);
		
		return $this->index();
	}

	function examples()
	{

		$this->load->view('examples');

	}

	/**
	 * Action
	 * 
	 * This method was created to track a page action
	 */
	public function action()
	{
		// initialize variables
		$action_id    = $this->input->post('action_id');
		$conversion   = $this->input->post('conversion');
		$amount       = $this->input->post('amount');
		# $funnel_id    = $this->input->post('funnel_id');
		# $affiliate_id = $this->input->post('affiliate_id');
		# $offer_id     = $this->input->post('offer_id');

		// verify we have a valid action and funnel_id
		if ($funnel_id AND $action_id):

			$this->tracking->page_action(
				array(
					'visitor_id'        => $this->session->userdata('visitor_id'),
					'action_id'         => $action_id,
					'conversion'        => $conversion,
					'conversion_amount' => $amount		
				)
			);

			/*	
			// track action
			$this->tracking->action(
				$funnel_id,
				$action_id,
				$conversion,
				$amount,
				$affiliate_id,
				$offer_id
			);
			*/

		endif;

		$this->_output 	= array(
			'success'	=> TRUE,
			'data'		=> TRUE
		);

		return $this->index();
	}
}