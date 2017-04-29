<?php


class Test_apis extends MX_Controller {

	function index() 
	{


		$this->lang->load('hosting');

		$this->template->set_layout('sales_funnel2');

		$this->template->title($this->lang->line('hosting_title'));

		$this->template->prepend_footermeta('
			<script type="text/javascript" src="/resources/modules/hosting/assets/new/js/test_apis.js?v=1"></script>
		');
		
		$this->template->build('test_apis');
	}

}