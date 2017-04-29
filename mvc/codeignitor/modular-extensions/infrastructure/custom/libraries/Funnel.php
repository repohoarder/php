<?php

class Funnel {

	protected $_ci;

	function __construct()
	{

		$this->_ci = &get_instance();

	}


	function get_next_page($partner_id, $funnel_version, $action_id)
	{
		// initialize variables
		$next_page 		= FALSE;

		// grab next page id for this partner/funnel/action
		$action			= $this->_ci->platform->post(
			'sales_funnel/action/get_funnel_action',
			array(
				'action_id' => $action_id, 
				'funnel_id' => $funnel_version
			)
		);

		// if not successful, then this page doesn't have access to be submitted in this funnel
		if ( ! $action['success']):
			redirect('initialize/'.$partner_id.'/'.$funnel_version);
			return;
		endif;

		// set the next page id
		$next_page_id	= $action['data']['next_page_id'];	// This variable determins the next page to show

		// grab next page
		$next_page 		= $this->_ci->platform->post(
			'sales_funnel/page/get_by_id',
			array(
				'id' => $next_page_id
			)
		);

		if ( ! $next_page['success']):
			redirect('initialize/'.$partner_id.'/'.$funnel_version);
			return;
		endif;

		return $next_page['data'];
	}


	function redirect_form_action($partner_id, $funnel_version, $action_id)
	{

		// determine next page
		$next_page	= $this->get_next_page($partner_id, $funnel_version, $action_id); 

		// redirect user
		redirect($next_page['uri']);
		return;
	}

}