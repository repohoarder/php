<?php

## TODO: _valid_domain();
## TODO: Grab, replace, backup and dump database

class Install extends MX_Controller
{
	/**
	 * Build Directory
	 * 
	 * @var  _build_directory This is the directory where the build types reside
	 */
	var $_build_directory;

	/**
	 * Conn
	 * 
	 * @var  _conn The FTP connection string
	 */
	var $_conn;

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
	 * Client
	 * 
	 * @var _client This variable holds all of the client information
	 */
	var $_client;

	/**
	 * Build
	 * 
	 * @var  _build This is the build type we are attempting to build out
	 */
	var $_build;

	/**
	 * Ext
	 *
	 * @var  _ext File extensions we want to do search and replace against
	 */
	var $_ext;

	public function __construct()
	{
		parent::__construct();

		// load config
		$this->load->config('build');

		// set default filenames for build
		$this->_zip_filename		= $this->config->item('zip_filename');
		$this->_sql_filename		= $this->config->item('sql_filename');

		// set default build type directory
		$this->_build_directory		= $this->config->item('build_directory');

		// set default archive directory
		$this->_archive_directory	= $this->config->item('build_archives');

		// set file extensions to be searched
		$this->_ext 				= $this->config->item('ext_to_replace');
	}

	public function index($build=FALSE,$API_KEY=FALSE)
	{
		echo phpinfo();
		exit();
		// make sure build was passed
		if ( ! $build)	:
			return $this->_response(FALSE,'Please pass a valid build type.');
		endif;
		
		if (! $API_KEY)	:
			//return $this->_response(FALSE, 'Authentication Failed');
		endif;
		
		if( $API_KEY != 'zosrUlUZVPPRkNZjAWVedh3aVGE2S') :
			//return $this->_response(FALSE, 'Authentication Failed: Invalid Key');
		endif;
		// see if this is a valid build type
		if ( ! $this->_valid_build($build)):
			return $this->_response(FALSE,'Build does not exist.'.realpath($this->_build_directory.$build));
		endif;

		// initialize variables
		$this->_client['client_id']	= $this->input->post('client_id');
		$this->_client['domain']	= $this->input->post('domain');
		$this->_client['partner_id']= (! $this->input->post('partner_id') ) ? FALSE : $this->input->post('partner_id');
		$this->_build 				= $build;

		// error handling
		if ( ! $this->_client['client_id'] OR ! is_numeric($this->_client['client_id'])):
			return $this->_response(FALSE,'Please pass a valid client id.');
		endif;
		if ( ! $this->_valid_domain($this->_client['domain'])):
			return $this->_response(FALSE,'You must pass a valid domain.');
		endif;

		// set client info
		if ($this->_client_info() === FALSE):
			return $this->_response(FALSE,'Unable to grab client information.');
		endif;

		// build site
		$built 		= $this->_build();

		// track that a site built? or did not build
		// update sitebuilder?

		// return 
		return ($built === TRUE)
			? $this->_response(TRUE,TRUE)
			: $this->_response(FALSE,$built);
	}

	/**
	 * Build
	 * 
	 * This method FTP's the files over to the user's domain as well as builds the database
	 */
	private function _build()
	{
		// make sure we have needed variables
		if ( ! $this->_build)											return 'Please pass a valid build type.';
		if ( ! $this->_client['domain'])								return 'Please pass a valid domain to build files onto.';
		if ( ! isset($this->_client) OR ! is_array($this->_client))		return 'Please pass valid client information.';
		
		
		// see if this build has zip_file, if so build site
		if (file_exists($this->_build_directory.$this->_build.'/'.$this->_zip_filename)):

			// log into FTP server
			if ( ! $this->_ftp_login())	:
				return 'Unable to login via FTP using provided credentials @ initialize.';
			endif;

			// create backup of files currently on FTP server
			if ( ! $this->_create_ftp_backup()):
				return 'There was an error backing up users current public_html directory.';
			endif;

			// ftp files over (if needed) - create backup of current files on FTP server
			if ( ! $this->_create_build()):
				return 'There was an error pushing files to users domain.';
			endif;

			// End seeing if this build has files that need FTP'd over
		
		// run the shell script here 
		/**
		 *  export username=$1
		 *	export password=$2
			export dbname=$3
			export dbuser=$6
			export path=$4
			export domain=$5
		 */
		$path = realpath("../");
		echo "running shell script";
		$params = $this->_client['username']. " ". $this->_client['password']. " ".$this->_client['dbname']. " ".$this->_client['password']. " /home/".$this->_client['username']. "/public_html ".$this->_client['domain']. " ".$this->_client['dbuser'];
        //echo "ssh root@".$this->_client['server']. " 'bash -s' < $path/builder_setup.sh $params";
		$shell = system("ssh root@".$this->_client['server']. " 'bash -s' < $path/builder_setup.sh $params",$verbose);
		echo "output of  shell script";
		var_dump($shell);
		var_dump($verbose);
	// if partner_id is set make api calls to move configs over
		if($this->_client['partner_id']) :
			
			$postvar = array(
					'partner_id' => $this->_client['partner_id']
			);
			$pricing = $this->platform->post('partner/website/upload_prices_config',$postvar);
			if(!$pricing['success']):
				return 'Failed to upload prices config';
			endif;
			$options = $this->platform->post('partner/website/upload_options_config',$postvar);
			if( ! $options['success']):
				return 'Failed to upload Options config';
			endif;
		endif;
			
		endif;
		// if we made it here, we successfully built the website
		return TRUE;
	}

	/**
	 * Create Build
	 * 
	 * This method grabs all build files and passes them to user's server via FTP
	 */
	private function _create_build()
	{
		// initialize variables
		$zip 	= $this->_build_directory.$this->_build.'/'.$this->_zip_filename;	// This is the filepath for the zip file
		$dir 	= $this->_build_directory.$this->_build.'/';						// This is the build's directory
		$temp 	= $this->_build_directory.'temp/'.$this->_client['domainbase'].'/';		// This is the temp folder to create & store extracted zip files
		$sql	= $this->_build_directory.$this->_build.'/database.sql';
		
		// unzip file
		if ( ! $this->_unzip_build($zip,$temp)):
			return FALSE;
		endif;
		
		if( is_file($sql)):
			if( ! copy($sql,$temp."database.sql")):
				return FALSE;
			endif;
		endif;
		// replace strings in directory
		//$this->_string_replace($temp);
		
		// rezip files
		$this->_rezip($temp);
		
		// remove files from tmp
		$this->_delete_all_files($temp,array('website.tgz'));
		
		// add files to ftp
		$this->_add_files_to_ftp($temp,'\public_html');

		// remove temp dir
	//	$this->_remove_directory($temp);

		return TRUE;
	}

	/**
	 * Move file to a new directory
	 * 
	 * @param  string $file   	The file to move
	 * @param  string $newdir 	The new directory to move the file into
	 * @return boolean			Success boolean
	 */
	private function _move_file($file,$newdir)
	{
		// grab filename
		$filename 	= explode('/',$file);
		$filename 	= $filename[count($filename) - 1];	// Last item in array is the file/dir name

		// move file
		if ( ! copy($file,$newdir.$filename)) 	return FALSE;
		
		// return success
		return TRUE;
	}

	/**
	 * Add Files To FTP
	 * 
	 * This method adds all files and folders within a given directory to FTP
	 */
	private function _add_files_to_ftp($source,$destination)
	{
		// string replace file
		$file = "website.tgz";
		$remote 	= str_replace('\\','/',$destination.str_replace($source,'',$file));

		// replace \ with / (for Linux)
		$file 		= str_replace('\\', '/', $file);
		if(ftp_put($this->_conn, $remote, $file, FTP_BINARY)) :
			return true;
		else : 
			return false;
		endif;
		/*// iterate through directory
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);

		// set real path
		$source 	= realpath($source);

		foreach ($files AS $file):

			// set real path
			$file 	= realpath($file);

			// see if this $file is a directory
			if (is_dir($file)):

				// string replace file to create remote directory path
				$remote 	= str_replace('\\','/',$destination.str_replace($source,'',$file));

				// if folder doesn't exist on FTP, create it
				if ( ! @ftp_chdir($this->_conn,$remote)) ftp_mkdir($this->_conn,$remote);
				
			endif;

			// see if $file is a file
			if (is_file($file)):

				// string replace file
				$remote 	= str_replace('\\','/',$destination.str_replace($source,'',$file));

				// replace \ with / (for Linux)
				$file 		= str_replace('\\', '/', $file);

				// add file to FTP
				ftp_put($this->_conn, $remote, $file, FTP_BINARY);

			endif;

		endforeach;
		*/
		return TRUE;
	}

	/**
	 * Unzip Build
	 * 
	 * This method unzips files into temp folder
	 */
	private function _unzip_build($zip=FALSE,$temp='.')
	{
		// if no zip was passed, return error
		if ( ! $zip)			return FALSE;

		// create temp directory
		if ( ! $this->_create_temp_directory($temp)) 	return FALSE;

		// initialize zip archive
		$z 	= new ZipArchive;

		// open zip file
		if ( ! $z->open($zip))	return FALSE;

		// extract build files into temp dir
		$z->extractTo($temp);

		// close zip
		$z->close();

		return TRUE;
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
			$filename 	= $filename[count($filename) - 1];	// Last item in array is the file/dir name

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
	
	private function _rezip($source)
	{
		
		$source = realpath($source);
		var_dump("debugsource:".$source);
		
		$zip = system("cd $source;tar cvzf ./website.tgz ./",$verbose);
		var_dump($zip);
		var_dump($verbose);
	}
	
	private function _create_temp_directory($dir)
	{
		// if temp directory is already present, remove it
		if (is_dir($dir))	$this->_remove_directory($dir);

		// create temp dir
		if ( ! mkdir($dir)) 	return FALSE;

		return TRUE;
	}

	/**
	 * Remove Directory
	 * 
	 * This method removes a directory and all files within it recursively
	 */
	private function _remove_directory($source)
	{
		
		//system("rm -rf ")
		// create real path from $dir
		$source	= str_replace('\\', '/', realpath($source));

		// grab all files (and directories) within this directory
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST);

		foreach ($files AS $f):

			// if this is dir, remove it - else unlink
			$action 	= ($f->isDir())? 'rmdir' : 'unlink';

			// perform action
			$action($f->getRealPath());

		endforeach;

		// remove current dir
		rmdir($source);

		return TRUE;
	}

	/**
	 * String Replace
	 * 
	 * This method crawls files in given directory and replaces strings for this build type (stored in DB)
	 * Optionally, you can pass a single file name and it will only find/replace in that file (great for our SQL files)
	 */
	private function _string_replace($dir,$ext=array())
	{
		// get build
		$build 		= $this->platform->post('builder/build/get',array('name' => $this->_build));
		// i think i will have to ditch the recursivedirectoryiterator Matt, Sorry :0(
		$replace 	= $build['data']['replace'];
		// create real path from $dir
		$source		= str_replace('\\', '/', realpath($dir));
		// iterate through all search and replace strings
			foreach ($replace AS $key => $value):

				// initialize variables
				$string 	= $value['replace_string'];
				$with 		= $value['replace_with'];

				if(isset($this->_client[$with])) :
				// string replace each file
				$replacement = system("find $source  --exec sed s/$string/$this->_client[$with]/g",$verbose);

				endif;

			endforeach;	// End iterating through all replace strings
		
		
		var_dump($build);
		// error handling
		if ( ! $build['success'] OR empty($build['data']))	return FALSE;

		// grab string replace variables
		$replace 	= $build['data']['replace'];

		// create real path from $dir
		$source		= str_replace('\\', '/', realpath($dir));

		// if $ext was passed, then those are the only extensions to look at (going to be used for SQL only string replaces)
		if ( ! empty($ext))	$this->_ext 	= $ext;

		// grab all files (and directories) within this directory
		$files 	= new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST);

		foreach ($files AS $f):

			// only attempt to search and replace specific file extensions
			if (in_array(pathinfo($f, PATHINFO_EXTENSION),$this->_ext)):

				// iterate through all search and replace strings
				foreach ($replace AS $key => $value):

					// initialize variables
					$string 	= $value['replace_string'];
					$with 		= $value['replace_with'];
					
					if(isset($this->_client[$with])) :
					// string replace each file
					file_put_contents($f->getRealPath(),str_replace($string,$this->_client[$with],file_get_contents($f->getRealPath())));
					
					endif;

				endforeach;	// End iterating through all replace strings

			endif;	// End seeing if this is a valid file entension to replace

		endforeach;	// End iterating through all files in directory

		return TRUE;
	}

	/**
	 * Create FTP Backup
	 * 
	 * This method creates a backup of the server's /public_html directory
	 */
	private function _create_ftp_backup()
	{
		// rename public_html folder
		if ( ! ftp_rename($this->_conn,'public_html', 'public_html_'.strtotime(date("Y-m-d H:i:s"))))	return FALSE;

		// create new public_html folder
		if ( ! ftp_mkdir($this->_conn, 'public_html')) 													return FALSE;

		return TRUE;
	}

	/**
	 * FTP Login
	 * 
	 * This method logs into a FTP server
	 */
	private function _ftp_login()
	{
		// connect to host
		if ( ! $this->_conn	= ftp_connect($this->_client['domainbase'])):
			return FALSE;
		endif;

		// login with provided credentials
		if ( ! ftp_login($this->_conn,$this->_client['username'],$this->_client['password'])):
			return FALSE;
		endif;

		return TRUE;
	}

	/**
	 * Valid build
	 * 
	 * This method determines if given build type exists
	 */
	private function _valid_build($build)
	{
		
		// make sure this build has a directory associated with it
		if (! is_dir($this->_build_directory.$build)) return FALSE;

		// grab build info - if not there, return false
		$build 	= $this->platform->post('builder/build/get',array('name' => $build));

		// make sure we got valid build information
		if ( ! $build['success'] OR empty($build['data']))	return FALSE;

		return TRUE;
	}

	/**
	 * Client Info
	 * 
	 * This method grabs client info and returns an array of expected variables
	 */
	private function _client_info()
	{
		// create post array
		$post 	= array(
			'name'		=> 'id',
			'client_id'	=> $this->_client['client_id']	// grab client id from global client variable
		);

		// grab client info from ubersmith
		$client 	= $this->platform->post('ubersmith/client/get',$post);

		// make sure we were able to grab client information
		if ( ! $client['success'] OR empty($client['data']))	return FALSE;
		//var_dump($client);
		// create client info array
		$this->_client['first']		= $client['data']['first'];
		$this->_client['last']		= $client['data']['last'];
		$this->_client['email']		= $client['data']['email'];
		$this->_client['username']	= $client['data']['metadata']['global_username'];
		$this->_client['password']	= $client['data']['metadata']['global_password'];
		$this->_client['dbname']	= $this->_client['username'].'_'.$this->_build;
		$this->_client['dbuser']	= $this->_client['username'].'_'.$this->_build;
		$this->_client['server']	= $client['data']['metadata']['install_server_ip'];
		$this->_client['domainbase']= $client['data']['metadata']['core_domain_name'];
		$this->_client['domain']	= str_replace("http://http://","http://","http://".$client['data']['metadata']['core_domain_name']);
		return TRUE;
	}

	/**
	 * This method creates a new SQL database
	 * @return boolean
	 * @deprecated This method has been deprecated by use of _install.php script
	 */
	private function _sql_create_database()
	{
		// initialize variables
		$host 		= $this->_client['domain'];
		$username 	= $this->_client['username'];
		$password 	= $this->_client['password'];
		$dbname 	= $this->_client['dbname'];

		// generate command
		$command 	= 'mysql -h '.$host.' -u'.$username.' -p'.$password.' -e "CREATE DATABASE '.$dbname.'"';

		// run command
		system($command,$bool);

		print 'Create New Database: '.$command.' ----- '.$bool.'<BR>';

		return TRUE;
	}

	/**
	 * This method creates a new database user
	 * @return boolean
	 * @deprecated This method has been deprecated by use of _install.php script
	 */
	private function _sql_create_user()
	{
		// initialize variables
		$host 		= $this->_client['domain'];
		$username 	= $this->_client['username'];
		$password 	= $this->_client['password'];
		$dbname 	= $this->_client['dbname'];

		// generate command
		$command 	= 'mysql -h '.$host.' -u'.$username.' -p'.$password.' -e  "GRANT ALL ON '.$dbname.'.* TO '.$dbname.'@% IDENTIFIED BY \''.$password.'\'"';

		// run command
		system($command,$bool);

		print 'Create New User: '.$command.' ----- '.$bool.'<BR>';

		return TRUE;
	}

	/**
	 * This method performs a SQL dump on the _client specified variables
	 * @return boolean	Success or failure
	 * @deprecated This method has been deprecated by use of _install.php script
	 */
	private function _sql_dump($sql)
	{
		// initialize variables
		$host 		= $this->_client['domain'];
		$username 	= $this->_client['username'];
		$password 	= $this->_client['password'];
		$dbname 	= $this->_client['dbname'];

		// generate command
		$command 	= 'mysql -h '.$host.' -u'.$username.' -p'.$password.' '.$dbname.' < '.realpath($sql);

		// run command
		system($command,$bool);

		print 'Dump Database: '.$command.' ----- '.$bool.'<BR>';


		return TRUE;		
	}

	/**
	 * This method performs a SQL backup of the _client database
	 * @return boolean Success or failure
	 * @deprecated This method has been deprecated by use of _install.php script
	 */
	private function _sql_backup()
	{
		// initialize variables
		$host 		= $this->_client['domain'];
		$username 	= $this->_client['username'];
		$password 	= $this->_client['password'];
		$dbname 	= $this->_client['dbname'];

		// generate command to run
		$command		= 'mysqldump --opt --single-transaction --quick -h '.$host.' -u'.$username.' -p'.$password.' '.$dbname.' > '.$dbname.'_backup_'.strtotime(date("Y-m-d H:i:s")).'.sql';

		// execute command
		system($command,$bool);	// bool = 0 {success}, bool = 1 {warning} - usually db user doesn't have proper access, bool = 2 {error} - usually unable to connect to db

		print 'Create SQL Backup: '.$command.' ----- '.$bool.'<BR>';

		// return success or not
		return TRUE;
	}

	/**
	 * Valid Domain
	 * 
	 * This method validates a domain passed
	 */
	private function _valid_domain($domain=FALSE)
	{
		return TRUE;
	}

	/**
	 * Response
	 * 
	 * This method displays response
	 */
	private function _response($success=TRUE,$error=FALSE)
	{
		// echo results
		echo json_encode($this->api->response($success,$error));
	}
}

