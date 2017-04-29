<?php 

class Refunds extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($error=false){
		
		$data = array();
		// set data variables
		$data['error'] = $error;
		$data['list']	= array();	// The available pages
		$data['search'] ='';
		// set template layout to use
		$this->template->set_layout('default');
		
		// process the refund
		if($this->input->post('clientid')) :
			
			$data['error'] = $this->_refundit();
			$_POST['search'] = $this->input->post('clientid');
			
		endif;
		
		if($this->input->post('search')) :
			$search = $this->input->post('search');
			$data['search'] = $search;
			if( !empty($search)) :
				$list = $this->platform->post('ubersmith/client/payment_records',array('client_id'=>$search));
				
				if($list['success']) :
					$data['list'] = $list['data'];
					else:
					$data['error'] = 'Client ID not found';
				endif;
			endif;
		endif;
		
		
		// set the page's title
		$this->template->title('All Phase Client Refunds');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/partner/assets/css/listing.css">');
		
		
		
		// load view
		$this->template->build('clients/refunds', $data);
	}
	
	private function _refundit() {
		
		$err = '';
		$post = $this->input->post(null,true);
		$refund = array();
		$packages = array();
		$refund = array();
		$client_id = $post['clientid'];
		
		if ( isset($post['deactivate'])) :
			$deactivate = $this->platform->post('ubersmith/client/deactivate/'.$client_id,array('client_id'=>$client_id));
			
			if($deactivate['success']) :
				$err = "Successfully cancelled account<br>";
			endif;
		endif;
		
		
		if(isset($post['refund_entire'])) :
			
			foreach($post['refund_entire'] as $recordid=>$amount) :

				$refund[$recordid]['full_refund']   = true;
				$refund[$recordid]['note']	= $post['note'][$recordid];
				$refund[$recordid]['pay_record_id'] = $recordid;

			endforeach;
			
		endif;
		if(isset($post['individual'])) :
			
			foreach($post['individual'] as $recordid => $item) :

				// loop thru and create these again in case the  refund entire isnt checked
				$refund[$recordid]['note']	= $post['note'][$recordid];
				$refund[$recordid]['pay_record_id'] = $recordid;

				foreach($post['individual'][$recordid] as $payid=>$amount) :

					// set individual refudn array
					$refund[$recordid]['refund_items'][$payid] = $amount;

					// set packages array
					$packages[$recordid]['packages'][]	= $post['packid'][$payid];

				endforeach;

			endforeach;
			
		endif;
		
		$postArr['refunds'] = $refund;
		$postArr['packages'] = $packages;
		
		
		$execute = $this->platform->post('ubersmith/pay_records/refund_payment_ids',$postArr);
		
	
		if($execute['data']) :
			foreach($execute['data'] as $id=>$proc) :
			
			   
				if(is_array($proc)) :
					$err .= "$id: success<br>"; 
				else:
					$err .= "$id : $proc<br>";
				endif;
			
			endforeach;
		endif;
		
		return $err;
		
	}
}
