<?php

ini_set('display_errors','On');

class Deployment extends MX_Controller
{
	var $_build,
		$_domain,
		$_hostname,
		$_username,
		$_password,
		$_path,
		$_db_hostname,
		$_db_username,
		$_db_password,
		$_db_name,
		$_build_dir,
		$_connection,
		$_is_api = FALSE;

	public function __construct()
	{
		parent::__construct();

		// initialize build directory
		$this->_build_dir 	= APPPATH.'../../../infrastructure/custom/modules/deployment/builds/';
		$this->_filename 	= 'website.zip';
		$this->_database 	= 'database.sql';

		if ($this->input->post('api_key')):

			$this->_is_api = TRUE;

		endif;
	}

	public function index()
	{
		// initialize variables
		$data 		= array();

		// if data was POSTed, run custom submit
		if ($this->input->post())
			return $this->_submit();
	
		// set data variables

		// display form
		$this->template->build('deployment',$data);
	}

	private function _submit()
	{
		$response = array();

		// initialize variables
		$this->_build 			= $this->input->post('build');
		$this->_domain 			= $this->input->post('domain');
		$this->_email 			= $this->input->post('email');

		// initialize ftp variables
		$this->_hostname 		= $this->input->post('hostname');
		$this->_username 		= $this->input->post('username');
		$this->_password 		= $this->input->post('password');
		$this->_path 			= $this->input->post('path');

		// initialize database variables
		$this->_db_hostname 	= $this->input->post('db_hostname');
		$this->_db_username 	= $this->input->post('db_username');
		$this->_db_password 	= $this->input->post('db_password');
		$this->_db_name 		= $this->input->post('db_name');

		// make sure build exists
		if ( ! is_dir($this->_build_dir.$this->_build))
			return $this->_display_error('This build does not exist: '.$this->_build);

		// attempt to connect via FTP
		if ( ! $this->_login_ftp($this->_hostname,$this->_username,$this->_password))
			return $this->_display_error('Unable to login to FTP via credentials provided');

		// create backup of current files on FTP Server
		if ( ! $this->_backup_files($this->_path))
			return $this->_display_error('There was an error making a backup of the current files on server.');

		// push new build (ZIP and SQL) to FTP Server
		if ( ! $this->_build($this->_build,$this->_hostname))
			return $this->_display_error('Ther was an error building the website.');

		$unzip = file_get_contents('http://'.$this->_domain.'/_unzip.php');

		if ( ! $this->_is_api):

			// unzip files remotely
			echo 'Unzip Files: '.$unzip.'<br><br>';

		endif;

		
		// create database remotely
		if ($this->_db_name != ''):

			$importdb = file_get_contents('http://'.$this->_domain.'/_database.php?database='.$this->_db_name.'&hostname='.$this->_db_hostname.'&username='.$this->_db_username.'&password='.$this->_db_password);

			if ( ! $this->_is_api):

				echo 'Create Database: '.$importdb.'<br><br>';

			endif;

		endif;

		// generate content
		//echo 'Generating Content: '.file_get_contents().'<br><br>';

		if ( ! $this->_is_api):

			echo 'Successfully Built Website!';	
			return;	

		endif;

		$response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array(
				'build'    => $this->_build,
				'domain'   => $this->_domain,
				'email'    => $this->_email,
				'host'     => $this->_hostname,
				'username' => $this->_username,
				'path'     => $this->_path,
				'db_host'  => $this->_db_hostname,
				'db_user'  => $this->_db_username,
				'db_name'  => $this->_db_name
			)
		);

		echo json_encode($response);
		return;


	}

	private function _login_ftp($hostname,$username,$password)
	{
		// connect to host
		if ( ! $this->_connection	= @ftp_connect($hostname)):
			return FALSE;
		endif;

		// login with provided credentials
		if ( ! @ftp_login($this->_connection,$username,$password)):
			return FALSE;
		endif;

		return TRUE;
	}

	private function _backup_files($path)
	{
		// turn path into parts
		$parts 		= explode('/',$path);
		$main 		= end($parts);			// main folder in array

		// iterate all path aprts
		foreach ($parts AS $directory):

			// make sure this isnt an empty directory
			if ($directory != ''):

				// if this is the MAIN directory, then let's create a backup first
				if ($directory == $main):

					// let's make a backup of this main directory
					if ( ! ftp_rename($this->_connection, $main, $main.'_'.strtotime(date('Y-m-d H:i:s'))))
						return FALSE;

					// create new main directory
					if ( ! ftp_mkdir($this->_connection, $main))
						return FALSE;

				endif;

				// change directory
				if ( ! ftp_chdir($this->_connection,$directory))
					return FALSE;

			endif;

		endforeach;

		// everything is good, return TRUE
		return TRUE;
	}

	private function _build($build,$hostname)
	{
		// initialize variables
		$zip 	= $this->_build_dir.$build.'/'.$this->_filename;
		$sql 	= $this->_build_dir.$build.'/'.$this->_database;
		$temp 	= $this->_build_dir.'temp/'.$hostname;

		// remove temp directory if already exists
		if (is_dir($temp))
			$this->_remove_directory($temp);

		// create temp directory
		if ( ! mkdir($temp))
			return FALSE;

		// unzip into temp directory
		if ( ! $this->_unzip($zip,$temp))
			return FALSE;

		// if SQL exists, move to temp directory
		if (is_file($sql))
			copy($sql,$temp.'/'.$this->_database);

		// string replace
		$this->_string_replace($temp);

		// rezip
		if ( ! $this->_zip($temp,$temp.'/'.$this->_filename))
			return FALSE;

		// send via FTP
		ftp_put($this->_connection, $this->_filename, $temp.'/'.$this->_filename, FTP_BINARY);

		// we need to push UNZIP file to user's FTP
		ftp_put($this->_connection, '_unzip.php', $this->_build_dir.'_unzip.php', FTP_BINARY);

		// if we have a SQL file, then we need to push the database install file over as well
		if (is_file($sql))
			ftp_put($this->_connection, '_database.php', $this->_build_dir.'_database.php', FTP_BINARY);

		// remove temp directory
		$this->_remove_directory($temp);

		// return 
		return TRUE;
	}

	private function _zip($source,$destination)
	{
	    if ( ! extension_loaded('zip') || !file_exists($source)) {
	        return FALSE;
	    }

	    $zip = new ZipArchive();
	    if ( ! $zip->open($destination, ZIPARCHIVE::CREATE)) {
	        return FALSE;
	    }

	    $source = str_replace('\\', '/', realpath($source));

	    if (is_dir($source) === TRUE)
	    {
	        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

	        foreach ($files as $file)
	        {
	            $file = str_replace('\\', '/', $file);

	            // Ignore "." and ".." folders
	            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
	                continue;

	            $file = realpath($file);

	            if (is_dir($file) === TRUE)
	            {
	                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
	            }
	            else if (is_file($file) === TRUE)
	            {
	                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
	            }
	        }
	    }
	    else if (is_file($source) === TRUE)
	    {
	        $zip->addFromString(basename($source), file_get_contents($source));
	    }

	    return $zip->close();
	}

	private function _unzip($zip,$destination)
	{
		// initialize zip archive
		$z 	= new ZipArchive;

		// open zip file
		if ( ! $z->open($zip))
			return FALSE;

		// extract build files into temp dir
		$z->extractTo($destination);

		// close zip
		$z->close();

		// things were successful
		return TRUE;		
	}

	private function _remove_directory($dir)
	{
		// grab all fiules from directory
		$files = array_diff(scandir($dir), array('.','..'));

		// iterate all directory files
		foreach ($files as $file):

			// remove file, run function recursively for new directory 
			(is_dir("$dir/$file")) ? $this->_remove_directory("$dir/$file") : unlink("$dir/$file"); 
		
		endforeach;

		// remove parent directory
		return rmdir($dir); 

		// OLD
		//return (is_file($dir))? @unlink($dir): array_map(array(__CLASS__, __FUNCTION__), glob($dir.'/*')) == @rmdir($dir);
	}

	private function _display_error($error)
	{
		if ( ! $this->_is_api):

			echo $error;
			return;

		endif;

		$response = array(
			'success' => 0,
			'error'   => array($error),
			'data'    => array()
		);

		echo json_encode($response);
		return;
	}

	private function _string_replace($dir)
	{
	    $source = str_replace('\\', '/', realpath($dir));

	    if (is_dir($source) === TRUE)
	    {
	    	// iterate directory
	        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

	        foreach ($files as $file)
	        {
	            $file = str_replace('\\', '/', $file);

	            // Ignore "." and ".." folders
	            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
	                continue;

	            $file = realpath($file);

	            if (is_dir($file) === TRUE)
	            {
	            	// it's a directory - do nothing
	            }
	            else if (is_file($file) === TRUE)
	            {
	            	$this->_replace($file);
	            }
	        }
	    }
	    else if (is_file($source) === TRUE)
	    {
	    	$this->_replace($file);
	    }
	}

	private function _replace($file)
	{
		$arr 	= array(
			'__EMAIL__'				=> $this->_email,
			'__BUILD__'				=> $this->_build,
			'__DOMAIN__'			=> $this->_domain,
			'__HOSTNAME__'			=> $this->_hostname,
			'__USERNAME__'			=> $this->_username,
			'__PASSWORD__'			=> $this->_password,
			'__PASSWORD_HASH__'		=> md5($this->_password),
			'__PATH__'				=> $this->_path,
			'__DATABASE__'			=> $this->_db_name,
			'__DATABASE_HOSTNAME__'	=> $this->_db_hostname,
			'__DATABASE_USERNAME__'	=> $this->_db_username,
			'__DATABASE_PASSWORD__'	=> $this->_db_password
		);

		// open file and get data
		$data 	= file_get_contents($file);		

		// iterate all string replace variables
		foreach ($arr AS $string => $replace):

			// string replace
			$data 	= str_replace($string,$replace,$data);

		endforeach;

		// save file
		file_put_contents($file, $data);

		// return
		return TRUE;
	}
}