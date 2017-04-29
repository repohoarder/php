<?php
class Edit extends MX_Controller
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
	/**
	 * dropdown array for replacements
	 * @var type 
	 */
	var $_dropdown;
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
		$this->_dropdown		= $this->config->item('dropdown');
		// set default archive dir
		$this->_archive_dir		= $this->config->item('build_archives');

	}

	
	/**
	 * Create
	 * 
	 * This method will allow a user to create/update build types
	 */
	public function index($build_id)
	{
		$error='';
		$data = array();
		$data['build_dir']='';
		// if data is posted (and no error is passed), attempt to create build
		if ($this->input->post('buildit') AND ! $error):
			$error = $this->_edit($build_id);
		endif;
		
		// upload sql file
		if( $this->input->post('uploadsql') ) :
			//var_dump($this->input->post());
			$error = $this->_upload();
		endif;
		
		$list = $this->platform->post('builder/build/getbyid',array('build_id'=>$build_id));
		
		// set template layout
		$this->template->set_layout('bare');

		// set default title
		$this->template->title('Create New Build');
		$data['build'] = $list['data'];
		$data['build_id']	= $build_id;
		// set data variables
		$data['error']	= urldecode($error);
		$data['noexitpop'] = true;
		$data['dropdown'] = $this->_dropdown;
		// append custom stylesheet
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/builder/assets/css/style.css">');

		// append custom javascript
		$this->template->append_metadata('<script src="/resources/modules/builder/assets/js/script.js" type="text/javascript"></script>');

		// build the page
		$this->template->build('buildedit',$data);
	}
	private function _edit($build_id){
		
		$error ='';
		// initialize variables
		$description	= $this->input->post('description');
		$build 			= $this->input->post('build_name');	// The name of the build type
		$auto_build		= $this->input->post('auto_build');
		
		$ftp_host		= $this->input->post('ftp_host');
		$ftp_user		= $this->input->post('ftp_username');
		$ftp_pass		= $this->input->post('ftp_password');
		$db_host		= $this->input->post('db_host');
		$db_user		= $this->input->post('db_username');
		$db_pass		= $this->input->post('db_password');
		$db_name		= $this->input->post('db_name');
		$version		= $this->input->post('version');
		$slug		= $this->input->post('slug');
		$changelog		= $this->input->post('changelog');
		$directory 		= ($this->input->post('directory'))? $this->input->post('directory'): '/';	// This is the directory to pull via FTP
	
		
		$replace = $this->_generate_replace_array();
		// create add/update array
		$post 	= array(
			'name'			=> $build,
			'auto_build'	=> $auto_build,
			'ftp_host'		=> $ftp_host,
			'ftp_user'		=> $ftp_user,
			'ftp_pass'		=> $ftp_pass,
			'ftp_dir'		=> $directory,
			'db_host'		=> $db_host,
			'db_user'		=> $db_user,
			'db_pass'		=> $db_pass,
			'db_name'		=> $db_name,
			'replace'		=> $replace,
			'description'	=> $description,
			'build_id'		=> $build_id,
			'version'		=> $version,
			'changelog'		=> $changelog,
			'slug'			=> $slug
		);
		
		// add/update build in database
		$create 	= $this->platform->post('builder/build/edit',$post);
		// return
		return ($create['success'])
			? $build.' was successfully updated.' .$error	// Success
			: $create['error']['message'].$error;	
	}
	
	/**
	 * Generate Replace Array
	 *
	 * This method generates the replace array into the format we are looking for
	 * 
	 * @return array
	 */
	private function _generate_replace_array()
	{
		// initialize variables
		$replace 	= array();

		// grab post
		$post 		= $this->input->post();

		// loop 100 times
		for ($i=0; $i < 100; $i++):

			// see if we have at least 1 find and replace set
			if ( ! isset($post['find_text'.$i]) OR ! isset($post['sel_replacewith'.$i])):
				return $replace;	// return array
			else:
				$replace[]	= array(
					'replace_string'	=> $post['find_text'.$i],
					'replace_with'		=> $post['sel_replacewith'.$i]
				);
			endif;	

		endfor;

		return $replace;
	}
}
?>