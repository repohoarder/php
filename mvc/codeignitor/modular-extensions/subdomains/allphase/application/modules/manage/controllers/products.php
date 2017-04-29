<?php

class Products extends MX_Controller
{

	var $_partner,
		$_products;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');

		$this->load->config('products');
		$this->_products      = $this->config->item('products');
	}

	public function index($error=FALSE)
	{

		if ($this->input->post('new_submission')):

			return $this->_submit();

		endif;

		$this->load->helper('url');

		foreach ($this->_products as $category => $products):

			foreach ($products as $name => $short):
			
				if($name != 'radio') :
				$styles[] = '.upsell-'.$short.' {background:url("/resources/modules/manage/assets/images/dragon/'.$short.'.png") left bottom no-repeat;}';
				endif;
				
			endforeach;

		endforeach;

		$this->template->append_metadata('
			<script type="text/javascript" src="/resources/allphase/js/zclip/jquery.zclip.min.js"></script>
			<script type="text/javascript" src="/resources/allphase/js/jquery.color-animation.js"></script>
			<script type="text/javascript" src="/resources/modules/manage/assets/js/funnel-links.js"></script>

			<link rel="stylesheet" href="/resources/modules/manage/assets/css/dragon.css"/>
			
			<style type="text/css">

				.funnel_popup, .box .more, .box .view {display:none;}

				'.implode("\n",$styles).'

			</style>

		');

		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Manage Upsells');

		$data['funnels'] = array();

		$funnels = $this->platform->post(
			'sales_funnel/funnel/get_partner_funnel_upsells',
			array(
				'partner_id' => $this->_partner['id']
			)
		);		

		if ($funnels['success']):

			$data['funnels'] = $funnels['data']['funnels'];

		endif;

	
		// set data variables
		$data['error']          = urldecode($error);
		$data['nav_collapsed']  = TRUE;
		$data['products']       = $this->_products;
		$data['partner']        = $this->_partner;

		$data['all_products']   = array();

		foreach ($this->_products as $group_name => $group):

			foreach ($group as $product => $slug):
				if($product != 'radio') :
					$data['all_products'][$slug] = $product;
				endif;
			endforeach;

		endforeach;

		
		// load view
		$this->template->build('manage/products', $data);
	}

	private function _submit() 
	{

		$defaults = array(
			'hosting', 'domain', 'website'
		);

		$funnel_id = $this->input->post('funnel_id');
		$type      = $this->input->post('set_default');
		
		// error handling
		if ( ! $funnel_id):

			redirect('manage/products/Unable to set your default funnel.');
			return;

		endif;
		
		if ($type && in_array($type, $defaults)):

			$set = $this->_set_default_funnel($funnel_id, $type);

			if ( ! $set):

				redirect('manage/products/There was an error updating your default funnel.');
				return;

			endif;

		endif;

		if ($this->input->post('delete_funnel')):

			$deleted = $this->_delete_funnel($funnel_id);

			if ( ! $deleted):

				redirect('manage/products/There was an error deleting this funnel.');
				return;

			endif;

		endif;


		$upload = $this->platform->post(
			'partner/website/upload_options_config', 
			array(
				'partner_id' => $this->_partner['id']
			)
		);	

		redirect('manage/products');
		return;

	}

	private function _delete_funnel($funnel_id)
	{
		$resp = $this->platform->post(
			'sales_funnel/funnel/delete_partner_funnel',
			array(
				'partner_id'  => $this->_partner['id'],
				'funnel_id'   => $funnel_id,
			)
		);

		return (isset($resp['success']) && $resp['success']);
	}

	private function _set_default_funnel($funnel_id, $type)
	{

		$params = array(
			'partner_id'  => $this->_partner['id'],
			'funnel_id'   => $funnel_id,
			'funnel_type' => $type,
		);

		$resp = $this->platform->post(
			'sales_funnel/funnel/set_partner_default',
			$params
		);

		return (isset($resp['success']) && $resp['success']);

	}


	/*
	public function index($error=FALSE, $test = FALSE)
	{
		if ($test) return $this->test();

		// initialize variables
		$data	= array(
			'partner' => $this->_partner
		);
		
		if($this->input->post()):

			if ($this->input->post('new_submission')):

				return $this->_new_submit();

			else:

				return $this->_submit();

			endif;
			
		endif;
		
		// grab partner's funnel id
		$funnel 	= $this->platform->post(
			'sales_funnel/version/get_default',
			array(
				'partner_id'   => $this->_partner['id'],
				'affiliate_id' => 0 
			)
		);

		$this->template->append_metadata('
			<script type="text/javascript" src="/resources/allphase/js/zclip/jquery.zclip.min.js"></script>
			<script type="text/javascript" src="/resources/allphase/js/jquery.color-animation.js"></script>
			<script type="text/javascript" src="/resources/modules/manage/assets/js/funnel-links.js"></script>
		');
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Manage Upsells');

		// set data variables
		$data['error']			= urldecode($error);
		$data['default_funnel']	= $funnel['data'][0]['funnel_id'];
		
		// load view
		$this->template->build('manage/products', $data);
	}
	*/


	/*
	private function _submit()
	{
		// initialize variables
		$funnel_id 	= $this->input->post('funnel_id');
		
		// error handling
		if ( ! $funnel_id):

			redirect('manage/products/There was an error updating your default funnel version.');
			return;

		endif;
		
		// update funnel id for this aprtner
		$update 	= $this->platform->post('partner/funnels/setdefaultfunnel',array('partner_id' => $this->_partner['id'], 'funnel_id' => $funnel_id));
		

		$updated 	= $this->platform->post('sales_funnel/funnel/set_partner_default',
			array(
				'partner_id'  => $this->_partner['id'], 
				'funnel_id'   => $funnel_id,
				'funnel_type' => 'hosting'
			)
		);



		$upload = $this->platform->post(
			'partner/website/upload_options_config', 
			array(
				'partner_id' => $this->_partner['id']
			)
		);

		// redirect to manage pricing page for this funnel id
		redirect('manage/pricing/'.$funnel_id);

	}
	*/
}