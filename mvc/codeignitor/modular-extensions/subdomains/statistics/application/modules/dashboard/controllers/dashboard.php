<?php

class Dashboard extends MX_Controller {

	protected $global_meta = '';

	function __construct()
	{

		parent::__construct();

		$this->global_meta = '
			<script>window.jQuery || document.write(\'<script src="/resources/reports/js/libs/jquery-1.7.1.min.js"><\/script>\')</script>
			<script src="/resources/reports/highcharts/js/highcharts.js"></script>
			<script src="/resources/reports/highcharts/js/modules/exporting.js"></script>

			<script src="/resources/reports/js/highcharts_defaults.js"></script>
		';

		$this->template->append_metadata($this->global_meta);
	}

	function index()
	{

		$this->template->set_layout('no_boxes');

		$this->template->build('dash');

	}


	function brand($brand = 'brain_host')
	{

		$this->load->config('reports');

		$this->template->set_layout('no_boxes');

		$data['brand'] = $brand;

		$data['reports'] = $this->config->item('reports_reports');

		$this->template->build('brand', $data);

	}


	function report($report, $brand = 'all_brands')
	{

		$data = array(
			'report' => $report,
			'brand'  => $brand
		);

		$this->template->set_layout('no_boxes');

		$this->template->build('report',$data);

	}


}