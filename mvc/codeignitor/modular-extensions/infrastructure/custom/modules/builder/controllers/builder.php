<?php

## TODO: Validate form submission

class Builder extends MX_Controller
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
	 *dropdown for replaces
	 * @var array 
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

	public function index()
	{
		$this->create();
	}
	
	/**
	 * Create
	 * 
	 * This method will allow a user to create/update build types
	 */
	public function create($error=FALSE)
	{
		$data = array();
		$data['build_dir']= '';
		// if data is posted (and no error is passed), attempt to create build
		if ($this->input->post('buildit')):
			$error = $this->_build();
			$data['build_dir'] = $this->input->post('build_name');
		endif;
		
		// upload sql file
		if( $this->input->post('uploadsql') ) :
			//var_dump($this->input->post());
			$error = $this->_upload();
		endif;
		
		// set template layout
		$this->template->set_layout('bare');

		// set default title
		$this->template->title('Create New Build');
		
		// set data variables
		$data['dropdown'] = $this->_dropdown;
		$data['error']	= urldecode($error);
		$data['noexitpop'] = true;
		// append custom stylesheet
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/builder/assets/css/style.css">');

		// append custom javascript
		$this->template->append_metadata('<script src="/resources/modules/builder/assets/js/script.js" type="text/javascript"></script>');

		// build the page
		$this->template->build('build',$data);
	}

	private function _upload(){
		
		if( ! $this->input->post('build_dir')):
			return "Build directory is not set";
		endif;
		$return = 'uploading';
		//echo ">>".$this->_dir.$this->input->post('build_dir')."/".$this->_db_filename;
		if(is_file($this->_dir.$this->input->post('build_dir')."/".$this->_db_filename)):
			unlink($this->_dir.$this->input->post('build_dir')."/".$this->_db_filename);
		endif;
		
		if(move_uploaded_file($_FILES["sqlfile"]["tmp_name"],$this->_dir.$this->input->post('build_dir')."/".$this->_db_filename)):
			$return .= "File was uploaded successfully";
		return $return;
		endif;
		
		return false;
	}
	/**
	 * Build
	 * 
	 * This method attempts to create a new build
	 */
	private function _build()
	{
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
		$slug			= $this->input->post('slug');
		$directory 		= ($this->input->post('directory'))? $this->input->post('directory'): '/';	// This is the directory to pull via FTP

		$replace 		= $this->_generate_replace_array();	// Generates the array of string's and replaces

		// set global build name
		$this->_build_name	= $build;

		// set build directory path
		$this->_build_dir 	= $this->_dir.$this->_build_name.'/';

		// validate form
		// ## Make sure directory passed is valid

		// create the directory for the build
		$this->_create_build_directory();

		// see if FTP credentials were supplied
		if($ftp_host AND $ftp_user AND $ftp_pass):
			
			
		
			// connect via FTP and grab all files
			$download	= $this->_download_files_via_ftp($ftp_host,$ftp_user,$ftp_pass,$directory);

			// if unable to grab all files, return an error
			if ($download !== TRUE || is_array($download)):
				
				$output = isset($download['output']) ? "Err SCP: ".$download['output']. " exit code: ". $download['exit'] : $download;
				return $this->create($output);
			endif;

			// zip files in build directory
			if ( ! $this->_zip($this->_build_dir)):
				$error .='Unable to create zip archive of build directory.<br>';
			endif;

		endif;	// End seeing if FTP credentials were passes

		// see if we need to grab a database
		if ($db_host AND $db_user AND $db_pass):
			
			// connect to DB and grab SQL dump of database
			$sql 	= $this->_get_sql_dump($db_host,$db_user,$db_pass,$db_name);

			// make sure we were able to grab SQL files
			if ( ! $sql['success']):
				$error .='Error grabbing database dump.'.$sql['error'].'<br>';
			endif;

		endif;

		// delete all files from build dir (except newly created zip and sql files)
		$this->_delete_all_files($this->_build_dir, array($this->_zip_filename,$this->_db_filename));

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
			'slug'			=> $slug
		);

		// add/update build in database
		$create 	= $this->platform->post('builder/build/create',$post);
		//var_dump($create);
		// return
		return ($create['success'])
			? $this->_build_name.' was successfully built.' .$error	// Success
			: $create['error']['message'].$error;								// Failure
	}


	/**
	 * Delete All Files
	 * 
	 * This method deletes all files (and folders, recursively) in a given directory
	 */
	private function _delete_all_files($dir,$exclude=array())
	{
		
		// grab all files and directories from $old directory
		$files 	= glob($dir.'{,.}*', GLOB_BRACE);	// grab all files

		// iterate files and remove
		foreach ($files AS $file):

			// grab filename
			$filename 	= explode('/',$file);
		
			if(isset( $filename[count($filename) - 1])) :
				$filename 	= $filename[count($filename) - 1];	// Last item in array is the file/dir name
			endif;
			// if file is . or .. just continue
			if ($filename == '.' OR $filename == '..' ) continue;

			// if this is a directory, we need to delete all files within it
			if (is_dir($file)):

				// delete all files within this directory
				$this->_delete_all_files($file.'/', $exclude);

				// remove this directory
				if (is_dir($file)) rmdir($file);

			endif;

			// if this is a file, then move it to new dir
			if (is_file($file) AND ! in_array($filename, $exclude)):

				// delete file
				unlink($file);

			endif;

		endforeach;

		// done.
		return TRUE;
	}

	/**
	 * Zip Directory
	 * 
	 * This method zips files in the given directory
	 */
	private function _zip($source)
	{
		// initialize variables
		$zip_file 	= str_replace('\\', '/', realpath($this->_build_dir)).'/'.$this->_zip_filename;	// create realpath to zip file

		// if no files in directory, return FALSE
		if ( ! extension_loaded('zip') OR ! file_exists($source))	return FALSE;

		// initialize zip archive
		$zip = new ZipArchive();

		// attempt to create zip archive
		if ( ! $zip->open($zip_file, ZIPARCHIVE::CREATE))			return FALSE;

		// create real path from $dir
		$source	= str_replace('\\', '/', realpath($source));

		// see if this is a directory
		if (is_dir($source)):

			// grab all files recursively
			$files 	= new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			// iterate through files
			foreach ($files AS $file):
				
				$file 	= str_replace('\\', '/', $file);	// clean file path

				// ignore . and ..
				if (in_array(substr($file, strrpos($file, '/')+1), array('.', '..'))) continue;

				// grab realpath of file
				$file 	= realpath($file);

				// if this is a directory, add empty directory to zip archive
				if (is_dir($file))	$zip->addEmptyDir(str_replace(realpath($source).'/', '', $file.'/'));

				// if this is a file, add file to archive
				if (is_file($file))	$zip->addFromString(str_replace(realpath($source).'/','',$file), file_get_contents($file));

			endforeach;	// end iterating through files in directory

		endif;	// End if is_dir($source)

		// if user was passing single file instead, just zip it
		if (is_file($source))	$zip->addFromString(basename($source), file_get_contents($source));

		// close zip archive & return
		return $zip->close();
	}

	/**
	 * Get Files via FTP
	 * 
	 * This method connect to FTP and grabs all files & saves as (tar or zip)
	 */
	private function _download_files_via_ftp($host,$user,$pass,$directory)
	{
		
		if( $this->input->post('usescp')) :
			$output = $this->_scp_files($host,$user,$pass,$directory);
			if($output['exit'] !== 0 ):
				return $output;
			endif;
		else:
	
		// attempt to connect to host
		$this->_conn 	= ftp_connect($host) or die('Unable to connect to FTP via Host.');

		// attempt to login
		if ( ! ftp_login($this->_conn,$user,$pass)):
			return $this->_close_ftp('Unable to login via FTP using provided credentials');
		endif;

		// we need to change the FTP directory before starting to download the files
		@ftp_chdir($this->_conn,$directory);

		// download all of the files
		$this->_ftp_download('.',$directory);
		endif;
		return TRUE;
	}
	
	/** 
	 * 
	 * run scp command 
	 * @param type $host
	 * @param type $user
	 * @param type $pass
	 * @param type $directory
	 */
	private function _scp_files($host,$user,$pass,$directory){
	
		$build_dir = $this->_build_dir ;
		$return = array();
		
		// use scp to copy the files down
		//exec("scp -r $user@$host:$directory $build_dir",$verbose,&$returncode);
		exec("scp -r $user@$host:$directory $build_dir",$verbose,$returncode);

		// get the doc root folder
		$dirArr = explode("/",$directory);
		$dirArr = array_reverse($dirArr);
		
		if (substr($directory, -1) == "/") :
			$dir = $dirArr[1];
		else:
			$dir = $dirArr[0];
		endif;
		
		// move files out of the doc root of downloaded files
		exec("mv $build_dir/$dir/* $build_dir");
		exec("mv $build_dir/$dir/.htaccess $build_dir/.htaccess");	
		$return['output'] = $verbose;
		$return['exit']	= $returncode;
		return $return;
	}
	/**
	 * FTP Download
	 * 
	 * This method actually downloads the files from FTP Server to local directory
	 */
	private function _ftp_download($dir,$chdir,$build_dir='',$run = false)
	{
		$excluded =  array(".","..");
		
		// see if we need to change dir within FTP
		if ( $dir != '.'):
			// change directory within FTP connection
			$build_dir = str_replace("//","/","$build_dir/$dir");
			//echo "<h1>dir:$base_dir/$dir</h1> <br><br>";
			if ( ! ftp_chdir($this->_conn, str_replace('//','/',"$chdir/$dir"))):
				return $this->create('Unable to change FTP directory: '.$dir);
			endif;
			// see if directory exists locally
			if ( ! is_dir($this->_build_dir."$build_dir")):
				//echo "<<<".str_replace('//','/',$this->_build_dir."$build_dir")."###<br><br>";
				mkdir(str_replace('//','/',$this->_build_dir."$build_dir"));	// create directory locally
			endif; 
			 
			 
			$base_dir = "$chdir/$dir";
			
		else:
			// reset dir to '' if it's .
			$base_dir = '';
		endif;	// end chdir within FTP server

		// read all files in directory changing this to raw ftp then can determine if file or directory
		$files = ftp_rawlist($this->_conn,$base_dir,false); 
		
		
		foreach($files as $key=>$filelist):
			// trim the sides
			$filelist = trim($filelist);
			
			// explode on spaces
			$rowlist = explode(" ",$filelist);
			
			// last array element should always be the filename. i would hope this function would not change
			$filename = array_pop($rowlist);
			
			// initialize variables
			$remote_file	= $run == false  ?  str_replace('//','/',$chdir.$filename) :  str_replace('//','/',$base_dir.$filename);
			$local_file		= str_replace('//','/',$this->_build_dir.'/'.$build_dir.'/'.$filename);	// The local directory to save file to
			$base_dir = $run == false ? $chdir : $base_dir;
			
			// if this file is a directory type lets recursively call this function again to get the files
			if(substr($filelist,0,1) == "d"):
				if(!in_array($filename,$excluded)):
					//echo " $base_dir $filename -- <br> $build_dir <br> $local_file<br>";
					$this->_ftp_download($filename."/",$base_dir,$build_dir,true);
				endif;
			// else lets copy the file to its proper locations
			else:
				//echo "<h2>$remote_file</h2>";
				@ftp_get($this->_conn,$local_file,$remote_file,FTP_BINARY);
			endif;
		endforeach;
		
		return true;
		
	}
	/**
	 * Get SQL Dump
	 * 
	 * This method connects to a database and get a SQL dump of the database
	 */
	private function _get_sql_dump($host,$user,$pass,$name)
	{
		// generate command to run
		$command		= 'mysqldump --opt --single-transaction --quick -h '.$host.' -u'.$user.' -p'.$pass.' '.$name.' > '.str_replace('//','/',$this->_build_dir).$this->_db_filename;

		// execute command
		system($command,$bool);	// bool = 0 {success}, bool = 1 {warning} - usually db user doesn't have proper access, bool = 2 {error} - usually unable to connect to db
		
		
		// return success or not
		$success = ($bool == 0)
			? TRUE
			: FALSE;
		return array('success'=> $success,'error'=>$bool);
	}

	/**
	 * Close FTP
	 * 
	 * This method closes an FTP connection and passes back error if supplied
	 */
	private function _close_ftp($error=FALSE)
	{
		// close ftp connection
		if ( ! ftp_close($this->_conn)) return 'Unable to close FTP Connection.';

		// return
		return ($error)? $error :TRUE;
	}



	/**
	 * Create build directory
	 * 
	 * This method creates a new directory if one doesn't exist, or deletes files from dir if one does exist
	 */
	private function _create_build_directory()
	{
		// initialize variables
		$dir 			= $this->_build_dir;

		// see if directory exists
		if ( ! is_dir($dir)):

			// dir does not exist, create it
			if ( ! mkdir($dir)):
				return $this->create('Failed creating directory: '.$this->_build_name);	// return error
			endif;

		else:	// this means the directory already exists - store current files into versioning folder

			// initialize variables
			$archive 	= $this->_archive_dir.$this->_build_name.'/';

			// see if this build already has an archive directory
			if( ! is_dir($archive)):
				// dir does not exist, create it
				if ( ! mkdir($archive)):
					return $this->create('Failed creating directory: archive/'.$this->_build_name);	// return error
				endif;				
			endif;

			// new archive version directory name
			$archive 	= $archive.strtotime(date('Y-m-d H:i:s')).'/';

			// create new version folder within /archives directory
			mkdir($archive);

			// move all files from current build directory into archive
			$this->_move_all_files($dir,$archive);

		endif;	// end seeing if directory exists

		return TRUE;
	}

	/**
	 * Move All Files
	 * 
	 * This method moves all files and folders from $old directory into $new directory
	 */
	private function _move_all_files($old,$new)
	{
		// grab all files and directories from $old directory
		$files 	= glob($old.'{,.}*', GLOB_BRACE);	// grab all files

		// iterate files and remove
		foreach ($files AS $file):

			// grab filename
			$filename 	= explode('/',$file);
			$filename 	= $filename[count($filename) - 1];	// Last item in array is the file/dir name

			// if file is . or .. just continue
			if ($filename == '.' OR $filename == '..') continue;

			// if this is a directory, we need to grab all files within it
			if (is_dir($file)):

				// make this directory in the new structure
				mkdir($new.$filename);

				// move all files within this directory
				$this->_move_all_files($file.'/',$new.$filename.'/');

				// remove this directory
				if (is_dir($file)) rmdir($file);

			endif;

			// if this is a file, then move it to new dir
			if (is_file($file)):

				// move file to new directory
				rename($file,$new.$filename) or die('unable to rename old: '.$file.'  --- to '.$new.$filename);

			endif;

		endforeach;

		// done.
		return TRUE;
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

	/**
	 * Find
	 *
	 * This method grabs all replace_string's to use
	 */
	private function _find_and_replace($post=array(),$cnt=0)
	{


		return $this->_replace;
	}

	/**
	 * Is Build Type
	 * 
	 * This method determines if the passed build name already exists
	 */
	private function _is_build_type($build)
	{
		return FALSE;
	}

	private function create_zip()
	{

	}
}