<?php

class Epc extends MX_Controller {

	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}

	function index($start = FALSE, $end = FALSE, $error = FALSE)
	{
		// initialize variables
		$data	= array();

		// if form was submitted, run _submit method
		if ($this->input->post())	return $this->_submit();

		$visitors   = $this->platform->post(
			'partner/statistics/visitor/getvisitorsreport',
			array(
				'partner_id' => $this->_partner['id']
			)
		);

		$sales  	= $this->platform->post(
			'partner/statistics/sale/getsalesreport',
			array(
				'partner_id' => $this->_partner['id']
			)
		);

		$merged                = $this->_mergearrays($visitors['data'], $sales['data']);
		$data['visitorreport'] = $this->_calculateit($merged);

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('EPC Statistics');

		// set data variables
		$data['error']       = urldecode($error);
		
		$data['partner_id']  = $this->_partner['id'];

        // include custom js file
        $this->template->prepend_footermeta('<script type="text/javascript" src="/resources/modules/statistics/assets/js/epc.js"></script>');
		
		$this->template->append_metadata('
			<script src="/resources/reports/highcharts/js/highcharts.js"></script>
			<script src="/resources/reports/highcharts/js/modules/exporting.js"></script>
			<script src="/resources/reports/js/highcharts_defaults.js"></script>
		');

		$this->template->build('statistics/epc', $data);
	}




	private function _mergearrays($arr1,$arr2){
            
        $return = array();
        foreach ($arr1 as $k=>$array){
            if(isset($arr2[$k])) :
            $return[] = array_merge($arr1[$k],$arr2[$k]);
            endif;
        }
        
        return $return;
    }

    private function _calculateit($arr) {

        $return = array();

        // loob thru array and build conversion and epc keys
        foreach($arr as $index => $key) :
			$tmp['header']     = $key['header'];
			$tmp['rundate']    = $key['rundate'];
			$tmp['visitors']   = $key['visitors'];
			$tmp['conversion'] = ( $key['visitors'] > 0 ) ? round( ($key['count'] / $key['visitors']) , 2) : 0;
			$tmp['epc']        = ( $key['visitors'] > 0 ) ? round( ($key['total'] / $key['visitors']) , 2) : 0.00;
			$tmp['estimated_epc'] = round($tmp['epc'] * 5 / 10 ,2);
			$tmp['total']      = $key['total'];
			$tmp['count']      = $key['count'];
			$return[]          = $tmp ;
        endforeach;
        
        // return array
        return $return;
    }

}