<?php

class Visitors extends MX_Controller
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

	public function index($start=FALSE,$end=FALSE,$error=FALSE)
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
                
        // merge this array
        $merged = $this->_mergearrays($visitors['data'], $sales['data']);
        
        // after merged arrays calculate the epc and conversion
        $merged = $this->_calculateit($merged);
		
        // set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Visitor Statistics');
                
        $this->template->prepend_footermeta('<script type="text/javascript" src="/resources/modules/statistics/assets/js/visitors.js"></script>');

        $this->template->append_metadata('
			<script src="/resources/reports/highcharts/js/highcharts.js"></script>
			<script src="/resources/reports/highcharts/js/modules/exporting.js"></script>
			<script src="/resources/reports/js/highcharts_defaults.js"></script>
		');
        
        $data['visitorreport'] = $merged;
        $data['partner_id']    = $this->_partner['id'];

		// set data variables
		$data['error']	= urldecode($error);
		
		// load view
		$this->template->build('statistics/visitors', $data);
	}

	/**
	 * This method logs in a user and sets needed sessions
	 * @return boolean
	 */
	private function _submit()
	{
		$this->debug->show($this->input->post(),true);
		// initialize variables
		
		redirect('statistics/visitors');
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
			$tmp['visits']     = $key['visits'];
			$tmp['visitors']   = $key['visitors'];
			$tmp['conversion'] = ( $key['visitors'] > 0 ) ? round( ($key['count'] / $key['visitors']) , 2) : 0;
			$tmp['epc']        = ( $key['visitors'] > 0 ) ? round( ($key['total'] / $key['visitors']) , 2) : 0.00;
			$tmp['total']      = $key['total'];
			$tmp['count']      = $key['count'];
			$return[]          = $tmp ;
        endforeach;
        
        // return array
        return $return;
    }
        
}