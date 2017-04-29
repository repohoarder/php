<?php


class Clicktale extends MX_Controller
{
	/**
	 * Height
	 * 
	 * @var  _height description
	 */
	var $_height;

	/**
	 * Width
	 * 
	 * @var  _width
	 */
	var $_width;

	/**
	 * Reduction
	 * 
	 * @var  _reduction
	 */
	var $_reduction;

	/**
	 * Selector
	 * 
	 * @var  _selector
	 */
	var $_selector;

	public function __construct()
	{
		parent::__construct();

		// initialize variables
		$this->_width		= 960;
		$this->_height		= 900;
		$this->_reduction	= 4;
		$this->_selector	= 0;
	}

	public function test()
	{
		// set the layout to use (grabbed from bonus config for this page)
		$this->template->set_layout('bare');

		// load this page's title from the language library
		$this->template->title($this->lang->line('brand_company').' - ClickTale');
		
		// build the page
		$this->template->build('test');		
	}

	/**
	 * Index
	 * 
	 * This method shows the admin interface for clicktale
	 */
	public function index()
	{
		// set the layout to use (grabbed from bonus config for this page)
		$this->template->set_layout('bare');

		// load this page's title from the language library
		$this->template->title($this->lang->line('brand_company').' - ClickTale');
		
		// build the page
		$this->template->build('admin');
	}

	/**
	 * Show Users
	 * 
	 * This method allows us to see stats based on user's
	 */
	public function show_user($user=FALSE)
	{
		// if user is passed, we need to show image instead
		if ($user) return $this->_create_image('Session',$user);

		// get user's (sessions)
		$users 	= $this->platform->post('clicktale/get_distinct',array('type' => 'Session'));

		// error handling
		if ( ! $users['success'] OR ! isset($users['data'])) $users = array('data' => array());

		// set the layout to use (grabbed from bonus config for this page)
		$this->template->set_layout('bare');

		// load this page's title from the language library
		$this->template->title($this->lang->line('brand_company').' - ClickTale Show User');
		
		// set data variables
		$data['users']	= $users['data'];

		// build the page
		$this->template->build('user',$data);
	}

	/**
	 * Show Page
	 * 
	 * This method allows us to see stats based on page
	 */
	public function show_page()
	{
		// if user is passed, we need to show image instead
		if (isset($_GET['URL']) AND ! empty($_GET['URL'])) return $this->_create_image('URL',$_GET['URL']);

		// get URLs
		$urls 	= $this->platform->post('clicktale/get_distinct',array('type' => 'URL'));

		// error handling
		if ( ! $urls['success'] OR ! isset($urls['data'])) $urls = array('data' => array());

		// set the layout to use (grabbed from bonus config for this page)
		$this->template->set_layout('bare');

		// load this page's title from the language library
		$this->template->title($this->lang->line('brand_company').' - ClickTale Show User');
		
		// set data array
		$data['pages']	= $urls['data'];

		// build the page
		$this->template->build('page',$data);
	}

	/**
	 * Show Date
	 * 
	 * This method allows us to see stats based on date
	 */
	public function show_date($date=FALSE)
	{
		// if user is passed, we need to show image instead
		if ($date) return $this->_create_image('CreateDate',$date);

		// get URLs
		$dates 	= $this->platform->post('clicktale/get_distinct_date',array());

		// error handling
		if ( ! $dates['success'] OR ! isset($dates['data'])) $urls = array('data' => array());

		// set the layout to use (grabbed from bonus config for this page)
		$this->template->set_layout('bare');

		// load this page's title from the language library
		$this->template->title($this->lang->line('brand_company').' - ClickTale Show User');
		
		// set data array
		$data['dates']	= $dates['data'];

		// build the page
		$this->template->build('date',$data);
	}



	/**
	 * Create Image
	 * 
	 * This method creates and displays the clicktale image
	 */
	private function _create_image($type,$value)
	{
		// REDIRECT TO NEW HEAT MAPS
		header("Location: /clicktale/heatmap/show/".$type."/?value=".$value);	// use header() because codeignitor's redirect() was stripping trailing /

		// create POST array
		$post 	= array(
			'type'	=> $type,
			'value'	=> $value
		);

		// grab rows
		$rows	= $this->platform->post('clicktale/get',$post);

		// make sure we got valid response
		if ( ! $rows['success']):
			echo 'Unable to retrieve data.';
			exit;
		endif;

		// reset rows variable to actual data we need
		$rows	= $rows['data'];

		// initialize image
		$image 		= ImageCreate($this->_width, $this->_height);	// create image
		$border 	= ImageColorAllocate($image, 100, 100, 100);	// border color of image
		$bgcolor 	= ImageColorAllocate($image, 255, 255, 255);	// background color of image
		ImageFill($image, 0, 0, $bgcolor);			
		imagerectangle ($image,0,0,$this->_width-1,$this->_height-1,$border);

		// build image with data
		foreach ($rows AS $key => $value):
			$red 	= ImageColorAllocate($image, 255, 0, 0);					// Create red color
			$tempx	= floor($value['CoordX']/$this->_reduction);				// grab X coordinate
			$tempy	= floor($value['CoordY']/$this->_reduction);				// grab Y coordinate
			imagerectangle ($image, $tempx,$tempy,$tempx+1,$tempy+1, $red);		// Add to image
		endforeach;

		// show image
		header("Content-Type: image/png");
		ImagePng($image);
		ImageDestroy($image);
	}

	/**
	 * Number Pad
	 * 
	 * This is a number padding function
	 */
	private function number_pad($number,$n){
		return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
	}		

	/**
	 * Track
	 * 
	 * This method tracks a ClickTale click
	 */
	public function track()
	{
		// initialize variables
		$x		= $this->input->post('x');
		$y		= $this->input->post('y');
		$url	= $this->input->post('url');

		// make sure we got variables we are looking for
		if ( ! $x OR ! $y OR ! $url):
			// show error
			echo json_encode(array('success' => FALSE, 'error' => array('Invalid parameters passed.')));
			exit;
		endif;

		// create POST array
		$track	= array(
			'x'			=> $x,
			'y'			=> $y,
			'url'		=> $url,
			'session'	=> $this->session->userdata('session_id'),
			'ip'		=> $_SERVER['REMOTE_ADDR']
		);

		// platform call to insert track
		$insert	= $this->platform->post('clicktale/track',$track);

		// show response
		echo json_encode($insert);
	}
}