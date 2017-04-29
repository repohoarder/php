<?php

class Features extends MX_Controller
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

	public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();

		// if data was submitted, then update account information
		if ($this->input->post())	return $this->_submit();

		// get partner subscribed services
		$services 	= $this->platform->post('partner/packages/getid',array('partner_id' => $this->_partner['id']));
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Manage Account');

		// set data variables
		$data['error']		= urldecode($error);
		$data['services']	= $services['data'];
		
		// load view
		$this->template->build('extra/features', $data);
	}

	private function _submit()
	{
		// initialize variables
		$partner_id = $this->_partner['id'];
		$uber_client_id = $this->_partner['uber_client_id'];
		$plans = $this->input->post('plan');
		
		if( ! $plans) :
			redirect('extra/features');
		endif;
		
		// load library to handle upgrades
		$this->load->library('ubersmith_upgrade');
		
		// create packid array for later use
		$packids = array();
		// loob thru planids
		foreach($plans as $k=>$planid) :
			
			// add plan
			if( ! empty($planid)) :
				
				$return = $this->ubersmith_upgrade->addplan($planid,$uber_client_id);

				if( $return['success']) :
					$packids[$planid] = $return['data'];
				endif;
				
			endif;
			
		endforeach;
		//var_dump($packids);echo "<br>";
		$retinv = $this->ubersmith_upgrade->generateInvoice($uber_client_id);
		//var_dump($retinv);echo "<br>";
		$deactivate = array();
		if($retinv['success']) :
			$invoiceid = $retinv['data']['invid'];
			$billcard = $this->ubersmith_upgrade->chargeCard($invoiceid);
			//var_dump($billcard);;echo "<br>";
			if( ! $billcard['success']) :
				foreach ($packids as $k=>$packid) :
					$deactivate[] = $this->ubersmith_upgrade->deactivatePack($packid,$uber_client_id);
				endforeach;
				$disregard = $this->ubersmith_upgrade->disregardInvoice($uber_client_id,$invoiceid);
				//var_dump($deactivate);echo "<br>";
				//var_dump($disregard);echo "<br>";
				
				redirect('extra/features/An error occured while trying to process your card. Please contact customer support to upgrade your account.');
				else:
				// make post call to add userpackages
				// the packids array has the index of the planid and value of the packid
				$config = array(
					"packages" => $packids,
					"invoice"  => $retinv['data'],
					"uber_client_id"=> $uber_client_id,
					"partner_id" => $partner_id
					);
				$add = $this->platform->post('partner/packages/add',$config);
				// redirect back to page
				redirect('extra/features/Service(s) have been successfully added.');
			endif;
		endif;
		
		
		
	}
}