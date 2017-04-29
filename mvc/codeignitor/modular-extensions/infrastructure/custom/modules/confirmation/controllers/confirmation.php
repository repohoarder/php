<?php

class Confirmation extends MX_Controller {
	
	public 
		$_funnel_version,
		$_partner_id,
		$_partner_info    = array(),
		$_affiliate_id    = 0,
		$_offer_id        = 0,
		$_terms    = array(
			'1'  => 'monthly',
			'6'  => 'biannual',
			'12' => 'annual',
			'24' => 'biennial',
			'36' => 'triennial',
			'48' => 'quadrennial',
		),
		$_hosting_prices = array(),
		$_conversion_amt  = 0;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('confirm_order');
		$this->_funnel_version = $this->session->userdata('funnel_id');
		$this->_partner_id     = $this->session->userdata('partner_id');
		$this->_partner_info   = $this->session->userdata('partner_info');
		
		$this->_affiliate_id   = $this->session->userdata('affiliate_id');
		$this->_offer_id       = $this->session->userdata('offer_id');
		
		
	}

	function index($order_id = FALSE)
	{

		if ( ! $order_id):

			$order_id = $this->session->userdata('_id');

		endif;
		if( $this->input->post('modify_packs')) :
			$this->confirm_order->remove_packs($this->input->post('remove_upsell'),$order_id);
		endif;
		
		return $this->confirm($order_id);
	
	}

	function confirm($order_id = NULL)
	{

		$action_id = $this->input->post('action_id');

		if ($action_id):

			$this->load->library('funnel');

			$this->funnel->redirect_form_action($this->_partner_id, $this->_funnel_version, $action_id);
			return;

		endif;

		// initialize hosting prices
		$this->_hosting_prices = $this->confirm_order->get_hosting();


		$this->tracking->page_hit(
			array(
				'visitor_id' => $this->session->userdata('visitor_id'),
				'slug'       => 'confirmation',
			)
		);


		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Please Confirm Your Order');
		
		// append the CSS file
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/brainhost/css/completed.css" type="text/css" />');
			
		$items = $this->confirm_order->get_items_by_order($order_id);

		$data['items'] = array();

		// set needed data variables
		$data['items']['current_packs'] = $items;

		$data['period']            = array(
			'0'		=> 'one-time fee',
			'1' 	=> 'monthly',
			'6'		=> 'semiannual',
			'12'	=> 'annual',
			'24'	=> 'biennial',
			'36'	=> 'triennial',
			'48'	=> 'quadrennial'
		);
		
		$data['hosting_plans'] = $this->_hosting_prices;
		// load view
		$this->template->build('confirmation/confirm', $data);

	}
	/**
	 * This function is requested via ajax and returns a json object to update prices if successfull
	 */
	public function updatehosting(){
		
		$return = array();
		$order_id = $this->session->userdata('_id');
		
		$return['id'] = $order_id;
		
		if(! $order_id) :
			$return['error'] = 'no order id found';
			echo json_encode($return);
		endif;
		
		$return = $this->confirm_order->update_hosting_plan($this->input->post(null,true),$order_id);
		
		echo json_encode($return);
	}
	
	
	

	
	
	
	
}