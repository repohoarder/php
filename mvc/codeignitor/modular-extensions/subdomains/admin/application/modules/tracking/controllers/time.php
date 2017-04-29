<?php 

class Time extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($username=FALSE,$error=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// if data is posted, then submit form
		if ($this->input->post())	return $this->_submit();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Project Management Tracking');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		$data['noexitpop']  = false;
		// set data variables
		$data['users']		= $this->_get_users_dropdown();
		$data['types']		= $this->_get_types_dropdown();
		$data['brands']		= $this->_get_brands_dropdown();
		$data['username']	= $username;
		$data['error']		= urldecode($error);

		// load view
		$this->template->build('tracking/time', $data);
	}

	private function _submit()
	{
		// initialize variables
		$post['user'] 	= $this->input->post('user');
		$post['brand'] 	= $this->input->post('brand');
		$post['type'] 	= $this->input->post('type');
		$post['hours'] 	= $this->input->post('hours');
		$post['date']	= $this->input->post('date');

		// make sure we have valid data for each variable
		foreach ($post AS $key => $value):

			// if this variables doesn't have any data, then return error
			if ( ! $value):

				redirect('tracking/time/'.$post['user'].'/You must fill out '.$key);
				return;

			endif;

		endforeach;

		// add the time
		$add 	= $this->platform->post('time/tracking/add',$post);

		// if there was an error, return error
		if ( ! $add['success'] OR ! $add['data']):

			redirect('tracking/time/'.$post['user'].'/There was an error adding the time.');
			return;

		endif;

		redirect('tracking/time/'.$post['user'].'/Successfully added time.');
		return;
	}

	/**
	 * Build the user's dropdown array
	 * @param  boolean $user_id [description]
	 * @return [type]           [description]
	 */
	private function _get_users_dropdown()
	{
		// initialize variables
		$dropdown 	= array();

		// get users 
		$users 		= $this->platform->post('time/user/get',array());

		// make sure we were able to grab the users
		if ( ! $users['success'] OR empty($users['data']))
			show_error('Unable to grab users.');

		// iterate through each user to create dropdown array
		foreach ($users['data'] AS $key => $value):

			$dropdown[$value['username']] 	= $value['first_name'].' '.$value['last_name'];

		endforeach;

		return $dropdown;
	}

	/**
	 * Build the type's dropdown array
	 * @param  boolean $user_id [description]
	 * @return [type]           [description]
	 */
	private function _get_types_dropdown($user_id=FALSE)
	{
		// initialize variables
		$dropdown 	= array();

		// get users 
		$type 		= $this->platform->post('time/type/get',array());

		// make sure we were able to grab the users
		if ( ! $type['success'] OR empty($type['data']))
			show_error('Unable to grab types.');

		// iterate through each user to create dropdown array
		foreach ($type['data'] AS $key => $value):

			$dropdown[$value['slug']] 	= $value['name'];

		endforeach;

		return $dropdown;
	}

	/**
	 * Build the brand's dropdown array
	 * @param  boolean $user_id [description]
	 * @return [type]           [description]
	 */
	private function _get_brands_dropdown($user_id=FALSE)
	{
		// initialize variables
		$dropdown 	= array();

		// get users 
		$brand 		= $this->platform->post('time/brand/get',array());

		// make sure we were able to grab the users
		if ( ! $brand['success'] OR empty($brand['data']))
			show_error('Unable to grab brands.');

		// iterate through each user to create dropdown array
		foreach ($brand['data'] AS $key => $value):

			$dropdown[$value['slug']] 	= $value['name'];

		endforeach;

		## OVERWRITE
		$dropdown 	= array(
			'all'		=> 'All Brands',
			'hosting' 	=> 'Hosting Brands',
			'fws'		=> 'Free Website'
		);

		return $dropdown;
	}
}