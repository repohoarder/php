<?php

class Heatmap extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

	}

	public function show($type)
	{
		// get value from $_GET
		$value 	= $_GET['value'];

		// create POST array
		$post 	= array(
			'type'	=> $type,
			'value'	=> $value
		);

		// grab rows
		$rows	= $this->platform->post('clicktale/get',$post);

		// set the layout to use (grabbed from bonus config for this page)
		$this->template->set_layout('blank');

		// load this page's title from the language library
		$this->template->title($this->lang->line('brand_company').' - HeatMap');

		// append js
		$this->template->append_metadata('<script type="text/javascript" src="/resources/modules/clicktale/assets/js/heatcanvas.js"></script>');

		// build data array
		$data['rows']	= $rows['data'];

		// build the page
		$this->template->build('heatmap',$data);
	}
}