<?php

class Sales_path extends MX_Controller {

	protected 
		$_errors        = array(),
		$_max_num_pages = 0,
		$_products      = array(),
		$_tied_products = array(),
		$_partner		= array(),
		$_order_confirmation = array();
		
	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner	= $this->session->userdata('partner');

		$this->load->config('products');
		$this->_products      = $this->config->item('products');
		$this->_tied_products = $this->config->item('tied_products');
		$this->_max_num_pages = $this->config->item('max_products');
		$this->_order_confirmation = $this->config->item('order_confirmation');
	}	

	function index($dragon = FALSE) {

		$post    = $this->input->post(NULL, TRUE);

		if ($post['sales_path']):

			$funnel_id = $this->_submit($post);

			if ($valid === FALSE):

				$this->_errors[] = 'Unable to create funnel.';

			else:

				redirect('manage/pricing/'.$funnel_id);
				return;

			endif;

		endif;

		$this->template->set_theme('allphase');
		$this->template->set_layout('default');

		$styles = array();

		foreach ($this->_products as $category => $products):
	
				foreach ($products as $name => $short):
					$styles[] = '.upsell-'.$short.' {background:url("/resources/modules/manage/assets/images/dragon/'.$short.'.png") left bottom no-repeat !important;}';
				endforeach;
		
		endforeach;

		$this->template->append_metadata('
			<link rel="stylesheet" href="/resources/modules/manage/assets/css/dragon.css"/>
			<style type="text/css">
				'.implode("\n",$styles).'
			</style>
			<script src="/resources/modules/manage/assets/js/jquery.ui.touch-punch.js"></script>
			<script src="/resources/modules/manage/assets/js/dragon.js"></script>	
		');


		if ($dragon):

			$this->template->append_footermeta("
				<img id='pet_dragon' src='/resources/modules/manage/assets/images/dragon/dragon3.gif'/>

				<script type='text/javascript'>

					$(document).ready(function(){

						$('body').mousemove(function(e){
							$('#pet_dragon').css({'position':'absolute', 'top': e.clientY + 10, 'left': e.clientX + 10});
						});

					});					

				</script>
			");

		endif;

		$data['post']          = $post;
		$data['errors']        = $this->_errors;
		$data['upsells']       = $this->_products;
		$data['tied_products'] = $this->_tied_products;
		$data['max_num_pages'] = $this->_max_num_pages;
		$data['nav_collapsed'] = TRUE;
		$data['partner_id']	   = $this->_partner['id'];
		
		//if( $this->_partner['id'] == 82 || $this->_partner['id'] == 49) :
		// order confirmation page	
			$data['order_confirmation'] = $this->_order_confirmation;
		
		//endif;
		
		$this->template->build('sales_path', $data);
	}


	function _submit($post)
	{

		$name = $post['funnel_name'];

		if ( ! $name):

			$name = 'Custom Funnel';

		endif;

		$type = $post['funnel_type'];

		if ( ! in_array($type, array('hosting','domain'))):

			$type = 'hosting';

		endif;
		
		$upsells = $this->input->post('post_upsells');
		
		if($upsells[0] != 'completed') :
			$upsells[1] = 'completed';
		endif;
		
		$params   = array(
			'partner_id'    => $this->_partner['id'],
			'funnel_name'   => $name,
			'funnel_type'   => $type,
			'pre_upsells'   => $this->input->post('pre_upsells'),
			'upsells'       => $this->input->post('products'),
			'post_upsells'  => $upsells,
			'tied_products' => $this->input->post('tied_products'),
			'white_label'	=> $this->input->post('white_label'),
			'exit_pop'		=> $this->input->post('exit_pop')
		);

		$resp = $this->platform->post(
			'sales_funnel/funnel/create_partner_funnel',
			$params
		);

		return ($resp['success']) ? $resp['data']['funnel_id'] : FALSE;

	}



}