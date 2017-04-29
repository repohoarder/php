<?php

class Home extends MX_Controller
{
	/**
	 * The array of PArtner Information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}

	public function index()
	{
		// initialize variables
		$data   = array();
		
		$layout = 'default';
		
		$steps  = $this->platform->post(
			'partner/step/get',
			array(
				'partner_id' => $this->_partner['id']
			)
		);
		
		$data['setup_incomplete'] = FALSE;
		$data['step_slug']        = NULL;
		
		$footermeta               = '';

		foreach ($steps['data'] AS $key=>$value):

			if ($value['completed']):
				continue;
			endif;

			$data['step_slug']        = $value['slug'];
			$data['setup_incomplete'] = TRUE;

			$js     = 'modules/steps/assets/js/'.$data['step_slug'].'.js';
			
			$layout = 'partner_setup';

			if (file_exists(APPPATH.$js)):

				$footermeta = '<script type="text/javascript" src="/resources/'.$js.'"></script>';

			endif;

			break;

		endforeach;



		$this->template->set_layout($layout);

		$this->template->title('Partner Home');

		$this->template->append_metadata('
			<script src="/resources/reports/highcharts/js/highcharts.js"></script>
			<script src="/resources/reports/highcharts/js/modules/exporting.js"></script>
			<script src="/resources/reports/js/highcharts_defaults.js"></script>
		');

		$this->template->prepend_footermeta($footermeta);

		// prepend the statistics page(s) javascript
		$this->template->prepend_footermeta('<script type="text/javascript" src="/resources/modules/statistics/assets/js/sales.js"></script>');
		$this->template->prepend_footermeta('<script type="text/javascript" src="/resources/modules/statistics/assets/js/visitors.js"></script>');
		$this->template->prepend_footermeta('<script type="text/javascript" src="/resources/modules/statistics/assets/js/epc.js"></script>');

		// load view
		$this->template->build('home/home', $data);
	}

	public function test() {
		echo "HIT";
	}
}