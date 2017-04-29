<?php
class Campaign extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->config('campaigns');
		
	}
	
	public function index($error=FALSE)
	{
		
		$brands_response = $this->platform->post('split_test/campaign/get_brands',false);

	
		// initialize variables
		$data	= array();
		
		// if data is posted, then submit form
		if ($this->input->post())	return $this->_submit();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Add Split Test Campaign');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/campaign/assets/css/style.css">');
		$this->template->append_metadata('<script src="/resources/modules/campaign/assets/js/scripts.js"  type="text/javascript"></script>');
		
		if($brands_response['success']){
			$data['brands'] = $brands_response['data'];
		}
		
		//$data['brands']		= $this->config->item('brands');

		// load view
		$this->template->build('campaign/add', $data);
	}
	
	private function _submit(){
	
		$post = $this->input->post(NULL, TRUE);
		
		$response = $this->platform->post('split_test/campaign/add',$post);
		
		redirect('/campaign/view/' . $post['brand']);
		
		
	
	}
	
	public function view($brand_id=FALSE) {
	
		$post_vars = array();
		
		$brands_response = $this->platform->post('split_test/campaign/get_brands',false);
		if($brands_response['success']){
			$data['brands'] = $brands_response['data'];
		}
		
		if($brand_id):
			$post_vars = array('brand_id' => $brand_id);
		endif;
		$campaigns_response = $this->platform->post('split_test/split_test/get_all_active',$post_vars);
		if($campaigns_response['success']){
			$data['campaigns'] = $campaigns_response['data'];
		}
		// if($brand_id):
			// $campaigns_response = $this->platform->post('split_test/campaign/get_campaigns/' . $brand_id . '/' . $split_test_id,false);
			// var_dump($campaigns_response);
			// if($campaigns_response['success']){
				// $data['campaigns'] = $campaigns_response['data'];
			// }
		// endif;
		
		
		// if($split_test_id):
			// $variations_response = $this->platform->post('split_test/campaign/get_variations/' .  $split_test_id,false);
			// if($variations_response['success']){
				// $data['variations'] = $variations_response['data'];
			// }
			
			// $goals_response = $this->platform->post('split_test/campaign/get_goals/' .  $split_test_id,false);
			// if($goals_response['success']){
				// $data['goals'] = $goals_response['data'];
			// }
		// endif;
		
		$data['selected_brand_id'] = $brand_id;
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/campaign/assets/css/style.css">');
		$this->template->append_metadata('<script src="/resources/modules/campaign/assets/js/scripts.js"  type="text/javascript"></script>');
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('View Split Tests');
		
		$this->template->build('campaign/view', $data);
	
	}
	
	
	function deactivate($split_test_id){
	
		$post = array(
				'split_test_id' => $split_test_id,
				'status' => 0
				);
		
		$response = $this->platform->post('split_test/campaign/status_set',$post);

		redirect('/campaign/view/');
		
		
	
	}
	
}	