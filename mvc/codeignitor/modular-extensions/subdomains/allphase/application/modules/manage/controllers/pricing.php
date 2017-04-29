<?php

class Pricing extends MX_Controller
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

	/**
	 * This method allows the Partner to update their pricing structure
	 * @return view
	 */
	public function index($funnel_id=FALSE,$error=FALSE)
	{
		// initialize variables
		$data		= array();

		// default funnel id to 0 if none passed
		$funnel_id	= ( ! $funnel_id)
			? 1
			: $funnel_id;

		// if form was submitted, run _submit method
		if ($this->input->post())	return $this->_submit();
		
		$types = array('DOMAIN','UPSELL','HOSTING');

		// get this partner's current pricing structure
		//$structure 	= $this->platform->post('partner/pricing/structure',array('partner_id' => $this->_partner['id'],'types'	=> $types));
		
		$structure 	= $this->platform->post('partner/pricing/get_all', array('partner_id' => $this->_partner['id'], 'funnel_id' => $funnel_id));

		// make sure we got valid pricing structure
		if ( ! $structure['success'] OR empty($structure['data']))	$structure['data']	= array();

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Manage Pricing');
                
		// set data variables
        $data['disallowed']     = array('FEES',"RESERVES","SUPPORT");
		$data['error']			= urldecode($error);
		$data['services']		= $structure['data'];
		$data['funnel_id']		= $funnel_id;
		
		// load view
		$this->template->build('manage/pricing', $data);
	}

	/**
	 * This method updates the pricing structure for this partner
	 * @return boolean
	 */
	private function _submit()
	{
		// initialize variables
		$funnel_id 	= $this->input->post('funnel_id');
		$services 	= $this->input->post('services');
		$trial		= $this->input->post('trial');
        // create update array      
        $update 	= array(
			'partner_id' 	=> $this->_partner['id'],
			'services'		=> $services,
			'funnel_id'		=> $funnel_id,
			'trial'			=> $trial
		);
 
        // perform update
		$updated 	= $this->platform->post('partner/pricing/update',$update);

		
		$upload = $this->platform->post(
			'partner/website/upload_prices_config', 
			array(
				'partner_id' => $this->_partner['id']
			)
		);
                
		// redirect back to pricing structure page
		redirect('manage/products/Prices successfully updated.');
	}
}