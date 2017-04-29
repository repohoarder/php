<?php
class Uber extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function client($partner_id=FALSE)
	{
		// initialize variables
		$data	= array();
		$error='';
		
		if($this->input->post('orderid')) :
			$error = $this->_submit($this->input->post('orderid'),$this->input->post('partner_id'));
		endif;
		
		// grab pages
		$queue	= $this->platform->post('partner/account/listing');

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Assign Order to Partner');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/partner/assets/css/listing.css">');
		
		// set data variables
		$data['list']	= $queue['data'];	// The available pages
		$data['error'] = $error;
		// load view
		$this->template->build('partner/uber', $data);
	}
	
	private function _submit($orderid,$partner_id) {
		
		$resp = $this->platform->post(
			'ubersmith/order/get',
			array(
				'order_id' => $orderid
			)
		);
		
		if ( ! $resp['success']):

			return 'Order ID not found '.$orderid;

		endif;
		
		$order        = $resp['data'];
		
		$getv = $this->platform->post(
			'partner/order/getvisitorid',
			array(
				'order_id' => $orderid
			)
		);
		
		
		
		if ( ! $getv['success']):

			return 'Visitor ID not found for'.$orderid;

		endif;
		
		$visitor_id = $getv['data']['visitor_id'];
		
		$getv = $this->platform->post(
			'partner/order/update_partner_id',
			array(
				'partner_id' => $partner_id,
				'visitor_id' => $visitor_id
			)
		);
		
		//var_dump($getv);
		$p['success'] = FALSE;

		if (isset($order['client_id']) && $order['client_id']):

			$p = $this->platform->post(
				'partner/order/update',
				array(
					'order_id'   => $orderid,
					'client_id'  => $order['client_id'],
					'visitor_id' => $visitor_id
				)
			);

		endif;
		
		//var_dump($p);
		
		$q = $this->platform->post(
			'partner/order/submitted',
			array(
				'order_id'   => $orderid,
				'visitor_id' => $visitor_id
			)
		);
		//var_dump($q);
		 return "Order Updated";
	}
}
?>
