<?php 

class Pixels extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('menu');
	}

	
	/**
	 * This method views all partner pixels (including ones needing approved)
	 * @param  string $partner_id [description]
	 * @return [type]             [description]
	 */
	public function view($partner_id='all')
	{
		// initialize variables
		$data	= array();

		// get pixels
		$pixels 	= $this->platform->post('partner/pixel/get',array('partner_id' => $partner_id, 'approved' => 'both'));

		// if error, default variable
		if ( ! $pixels['success'] OR ! is_array($pixels['data']['pixels']) OR empty($pixels['data']['pixels']))
			$pixels['data']	= array('pixels' => array());

		$breadcrumb = array("Partners"=>"/partner/view" ,"Pixels" => "/partner/pixels/view" ,"Add Pixel"=>"/partner/pixels/add");
		$data['breadcrumb'] = $this->menu->createBreadCrumb($breadcrumb);
		// set template layout to use
		$this->template->set_layout('default');

		// set data variables
		$data['pixels']		= $pixels['data']['pixels'];

		// load view
		$this->template->build('partner/pixels/view', $data);
	}

	/**
	 * This method allows user to add a partner pixel
	 */
	public function add()
	{
		// initialize variables
		$data	= array();
		$data['error'] = '';
		if($this->input->post()):
			
			$add = $this->_submit();
			if($add) :
				$data['error'] ='Pixel Added';

			else:

				$data['error'] = "Pixel was unable to save";

			endif;
			
		endif;
		$data['partners'] ='';
		$partners = $this->platform->post('partner/account/listing');
		
		if($partners['success']) :
			
			$data['partners'] = $partners['data'];
		
		endif;
		
		// set template layout to use
		$this->template->set_layout('default');
		
		$breadcrumb = array("Partners"=>"/partner/view" ,"Pixels" => "/partner/pixels/view" ,"Pixel Add"=>"");
		$data['breadcrumb'] = $this->menu->createBreadCrumb($breadcrumb);
		// load view

		$this->template->build('partner/pixels/add', $data);
	
	
	}
	private function _submit(){
		
		$post = $this->input->post(null,true);
		
		$pixels = $post['pixel'];
		if(! $post['pixel'] || !empty($pixels)) :
		
			if ( empty($post['pixel_id'])) :
				
				$pixeladd = $this->platform->post('partner/pixel/add',$post);
			else:
				
				$pixeladd = $this->platform->post('partner/pixel/edit',$post);
			
			endif;
			
			
			if($pixeladd['success']) :
				return true;
			else:
				return false;
			endif;
		else: 
			return false;
		endif;
		
		
	}

	/**
	 * This method approves a partner's pixel
	 * @param  boolean $partner_id [description]
	 * @param  boolean $pixel_id   [description]
	 * @return [type]              [description]
	 */
	public function approve($partner_id=FALSE,$pixel_id=FALSE)
	{
		// if nop partner nor pixel id, then redirect back to view pixels
		if ( ! $partner_id OR ! $pixel_id)
			redirect($this->config->item('subdir').'/partner/pixels/view');

		// approve pixel
		$approved 	= $this->platform->post('partner/pixel/approve',array('partner_id' => $partner_id, 'pixel_id' => $pixel_id));

		// if there was an error, display it
		if ( ! $approved['success']):

			show_error('There was an error approving the pixel: '.$approved['error']);

		else:



			$p_resp = $this->platform->post(
				'partner/account/details',
				array(
					'partner_id' => $partner_id
				)
			);

			if ($p_resp['success']):

				$partner = $p_resp['data'][0];

				if ($partner['email']):

					$headers = 'From: partners@allphasehosting.com';

					@mail(
						$partner['email'],
						'Your Tracking Pixel has been Approved',
						'Your tracking pixel should now appear on pages that you selected. Thank you, '."\n\n".'All Phase Hosting', 
						$headers
					);

				endif;

			endif;




			$upload = $this->platform->post(
				'partner/website/upload_pixels_config', 
				array(
					'partner_id' => $partner_id
				)
			);

			redirect($this->config->item('subdir').'/partner/pixels/view');

		endif;
	}

	public function deactivate($partner_id=FALSE,$pixel_id=FALSE)
	{
		// if nop partner nor pixel id, then redirect back to view pixels
		if ( ! $partner_id OR ! $pixel_id)
			redirect($this->config->item('subdir').'/partner/pixels/view');
			
		// deactivate pixel
		$deactivate 	= $this->platform->post('partner/pixel/deactivate',array('partner_id' => $partner_id, 'pixel_id' => $pixel_id));

		// if there was an error, display it
		if ( ! $deactivate['success'])
			show_error('There was an error deactivating the pixel: '.$deactivate['error']);

		redirect($this->config->item('subdir').'/partner/pixels/view');
	}
	
	public function edit($id=false,$partner_id=false){
		
		if( ! $id) :
			redirect("/partner/pixels/view");
		endif;
		
		$data	= array();
		$data['error'] = '';
		if($this->input->post()):
			
			$add = $this->_submit();
			if($add) :
				$data['error'] ='Pixel Updated';
			else:
				$data['error'] = "Pixel was unable to save";
			endif;
			
		endif;
		$data['partners'] ='';
		$partners = $this->platform->post('partner/account/listing');
		
		$post = array(
			'pixel_id'=>$id,
			'partner_id'=>$partner_id
		);
		$pixel = $this->platform->post('partner/pixel/get',$post);
		
		$data['pixel'] = $pixel['data']['pixels'][0];
		
		
		if($partners['success']) :
			
			$data['partners'] = $partners['data'];
		
		endif;
		
		$breadcrumb = array("Partners"=>"/partner/view" ,"Pixels" => "/partner/pixels/view" ,"Pixel Edit"=>"");
		$data['breadcrumb'] = $this->menu->createBreadCrumb($breadcrumb);
		
		// set template layout to use
		$this->template->set_layout('default');

		// load view

		$this->template->build('partner/pixels/add', $data);
	
		
	}

}

