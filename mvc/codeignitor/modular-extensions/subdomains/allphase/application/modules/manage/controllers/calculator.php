<?php

class Calculator extends MX_Controller
{
	/**
	 * The array of partner information
	 * @var int
	 */
	var $_partner;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');
	}
        function index($funnel_id=false){
            // initialize variables
		$data	= array();
		$error ='';
		// if form was submitted, run _submit method
		if ($this->input->post())	return $this->_submit();
		
		if( ! $funnel_id) :
			$funnel 	= $this->platform->post('sales_funnel/version/get_default',array('partner_id' => $this->_partner['id'],"affiliate_id"=>0));
			$funnel_id	= $funnel['data'][0]['funnel_id'];
		endif;
		
		$types = array('DOMAIN','UPSELL','HOSTING');
		// grab my data
		$response	= $this->platform->post('partner/pricing/getallprices',array('funnel_id'=>$funnel_id,'partner_id' => $this->_partner['id'],'types' => $types));
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Revenue Calculator');

		// set data variables
		$data['error']	= urldecode($error);
               
		// load view
                $data['processing_fee'] = isset($response['data']['processing_fee']) ? $response['data']['processing_fee'] : 12 ;
                $data['calculatorreport'] = isset($response['data']['records']) ? $response['data']['records'] :  array();
        
                // include custom js file
                $this->template->append_metadata('<script type="text/javascript" src="/resources/modules/manage/assets/js/script.js"></script>');
              
		$this->template->build('manage/calculator', $data);
               
        }
        
        function _submit(){
            
        }
}
