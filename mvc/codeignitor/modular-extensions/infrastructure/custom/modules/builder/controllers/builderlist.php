<?php

## TODO: Validate form submission

class Builderlist extends MX_Controller
{
	/**
	 * Zip File Name
	 * 
	 * @var  _zip_filename This is the default filename of the zip we will create for the build
	 */
	var $_zip_filename;

	/**
	 * Database File name
	 * 
	 * @var  _db_filename This is the default database sql filename we will create for build
	 */
	var $_db_filename;

	/**
	 * Dir
	 * 
	 * The default build directory
	 */
	var $_dir;

	/**
	 * Build Directory
	 * 
	 * This is the directory to store the build type
	 */
	var $_build_dir;

	/**
	 * Build Name
	 * 
	 * The name of the build type user is trying to add/update
	 */
	var $_build_name;

	/**
	 * Conn
	 * 
	 * The FTP Connection String
	 */
	var $_conn;

	/**
	 * Replace
	 *
	 * The replace array holder
	 */
	var $_replace;

	public function __construct()
	{
		parent::__construct();

		// load config
		$this->load->config('build');

		// set default filenames for build
		$this->_zip_filename	= $this->config->item('zip_filename');
		$this->_db_filename		= $this->config->item('sql_filename');

		// set default build type directory
		$this->_dir				= $this->config->item('build_directory');

		// set default archive dir
		$this->_archive_dir		= $this->config->item('build_archives');

	}

	
	public function index($error=FALSE)
	{
		

		// set template layout
		$this->template->set_layout('bare');

		// set default title
		$this->template->title('Create New Build');
		
		$list = $this->platform->post('builder/build/getall',array());
		
		$data['noexitpop'] = true;
		
		if($list['success']) {
			$data['list'] = $list['data'];
		}
		// set data variables
		$data['error']	= urldecode($error);

		// append custom stylesheet
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/builder/assets/css/style.css">');

		// append custom javascript
		$this->template->append_metadata('<script src="/resources/modules/builder/assets/js/script.js" type="text/javascript"></script>');

		// build the page
		$this->template->build('buildlist',$data);
	}
	
	public function edit($build_id){
		// if data is posted (and no error is passed), attempt to create build
		if ($this->input->post() AND ! $error) return $this->_edit();

		// set template layout
		$this->template->set_layout('bare');

		// set default title
		$this->template->title('Create New Build');
		
		$list = $this->platform->post('builder/build/getbyid',array('build_id'=>$build_id));
		
		$data['noexitpop'] = true;
		
		if($list['success']) {
			$data['list'] = $list['data'];
		}
		// set data variables
		$data['error']	= urldecode($error);

		// append custom stylesheet
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/builder/assets/css/style.css">');

		// append custom javascript
		$this->template->append_metadata('<script src="/resources/modules/builder/assets/js/script.js" type="text/javascript"></script>');

		// build the page
		$this->template->build('buildlist',$data);
	}
	private function _edit(){
		
	}
}
?>
