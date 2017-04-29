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
		
		// make sure build was passed
		if ( ! $build)	:
			return $this->_response(FALSE,$this->error->code($this, __DIR__,__LINE__),'Please pass a valid build type.');
		endif;
		
		if (! $API_KEY)	:
			//return $this->_response(FALSE, 'Authentication Failed');
		endif;
		
		if( $API_KEY != 'zosrUlUZVPPRkNZjAWVedh3aVGE2S') :
			//return $this->_response(FALSE, 'Authentication Failed: Invalid Key');
		endif;
		// see if this is a valid build type
		if ( ! $this->_valid_build($build)):
			return $this->_response(FALSE,$this->error->code($this, __DIR__,__LINE__),'Build does not exist.'.realpath($this->_build_directory.$build));
		endif;

		// initialize variables
		$this->_client['client_id']	= $this->input->post('client_id');
		$this->_client['domain']	= $this->input->post('domain');
		$this->_client['partner_id']= (! $this->input->post('partner_id') ) ? FALSE : $this->input->post('partner_id');
		$this->_build 				= $build;

		// error handling
		if ( ! $this->_client['client_id'] OR ! is_numeric($this->_client['client_id'])):
			return $this->_response(FALSE,$this->error->code($this, __DIR__,__LINE__),'Please pass a valid client id.');
		endif;
		
		// check for valid domain
		if ( ! $this->_valid_domain($this->_client['domain'])):
			return $this->_response(FALSE,$this->error->code($this, __DIR__,__LINE__),'You must pass a valid domain.');
		endif;

		// set client info
		if ($this->_client_info() === FALSE):
			return $this->_response(FALSE,$this->error->code($this, __DIR__,__LINE__),'Unable to grab client information.');
		endif;
		
		// build site
		$built 		= $this->_build();

		// track that a site built? or did not build
		// update sitebuilder?
		
		if ($this->input->post('buildit')):
			
			echo $built['data'];
			echo "<br><a href='/builder/builderlist'>Back to queue</a>";
		
		
		else:
			// return 
		return ($built['success'] === TRUE)
			? $this->_response(TRUE,$built['error'],$built['data'])
			: $this->_response(FALSE,$built['error'],$built['data']);
			
		endif;
	}


	function rebuild_partners($testing = FALSE) 
	{

		set_time_limit(180);

		if ($_SERVER['REMOTE_ADDR'] != '98.100.69.22' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1'):

			show_404();
			exit();

		endif;

		$this->_build = 'allphase_build';
		$resp         = $this->platform->post(
			'partner/account/all_partners_ftp',
			array()
		);

		if ( ! $resp['success']):

			echo 'Nope, can\'t do it right now. Come back later.';
			return;

		endif;

		$partners = $resp['data']['partners'];
		$errors   = array();

		foreach ($partners as $partner):

			if ( ! $partner['auto_update']):

				continue;

			endif;

			// set client_id and domain first, then _client_info();
		
			$this->_client = array(
				'client_id' => $partner['client_id'],
				'domain'    => $partner['domain'],
			);

			$client = $this->_client_info();

			if ($client !== TRUE):

				$errors[$partner['partner_id']] = 'Partner #'.$partner['partner_id'].' - Unable to get client info';
				echo 'Partner #'.$partner['partner_id'].' NOT BUILT - Unable to get client info'."\n<br/>";
				continue;

			endif;

			$this->_client['username']   = $partner['ftp_user'];
			$this->_client['password']   = $partner['ftp_pass'];
			$this->_client['dbname']     = $partner['ftp_user'].'_build';
			$this->_client['dbuser']     = $partner['ftp_user'].'_build';
			$this->_client['server']     = $partner['ftp_host'];
			$this->_client['partner_id'] = $partner['partner_id'];

			if (strstr($this->_client['server'], 'hostingaccountsetup.com') === FALSE):

				$errors[$partner['partner_id']] = 'Partner #'.$partner['partner_id'].' - Site is not on our servers';
				echo 'Partner #'.$partner['partner_id'].' NOT BUILT - Site is not on our servers'."\n<br/>";
				continue;

			endif;

			$built = $this->_build();

			if ($built['success'] !== TRUE):

				$errors[$partner['partner_id']] = 'Partner #'.$partner['partner_id'].' '.$built['data'];

			endif;

			echo 'Partner #'.$partner['partner_id'].' '.($built['success'] === TRUE ? 'built' : 'NOT BUILT - '.$built['data'])."\n<br/>";

		endforeach;

		echo '<pre>';
		#var_dump($errors);
		echo '</pre>';

	}



	/**
	 * Build
	 * 
	 * This method FTP's the files over to the user's domain as well as builds the database
	 */
	private function _build()
	{
		$output['success'] = false;
		// make sure we have needed variables
		if ( ! $this->_build):
			
			$output['data'] = 'Please pass a valid build type.';
			$output['error']= $this->error->code($this, __DIR__,__LINE__);
			return $output;
			
		endif;
		if ( ! $this->_client['domain']):
			
			$output['data'] =  'Please pass a valid domain to build files onto.';
			$output['error']= $this->error->code($this, __DIR__,__LINE__);
			return $output;
			
		endif;
		if ( ! isset($this->_client) OR ! is_array($this->_client)):
			
			$output['data'] =  'Please pass valid client information.';
			$output['error']= $this->error->code($this, __DIR__,__LINE__);
			return $output;
			
		endif;
		
		
		// see if this build has zip_file, if so build site
		if (file_exists($this->_build_directory.$this->_build.'/'.$this->_zip_filename)):

			// log into FTP server
			if ( ! $this->_ftp_login())	:
				
				$output['data'] =  'Unable to login via FTP using provided credentials '.$this->_client['username'].'@'.$this->_client['password'].' initialize.';
				$output['error']= $this->error->code($this, __DIR__,__LINE__);
				return $output;
				
			endif;
#var_dump($this->_client);
			// create backup of files currently on FTP server
			if ( ! $this->_create_ftp_backup()):
				
				$output['data'] =  'There was an error backing up users current public_html directory.';
				$output['error']= $this->error->code($this, __DIR__,__LINE__);
				return $output;
				
			endif;
#var_dump($this->_client);
			// ftp files over (if needed) - create backup of current files on FTP server
			if ( ! $this->_create_build()):
				
				$output['data'] =  'There was an error pushing files to users domain.';
				$output['error']= $this->error->code($this, __DIR__,__LINE__);
				return $output;
				
			endif;
#var_dump($this->_client);
		// End seeing if this build has files that need FTP'd over
		
		// run the shell script here 
		/*
		    export username=$1
		 	export password=$2
			export dbname=$3
			export dbuser=$6
			export path=$4
			export domain=$5
		 */
		
		$path  = realpath($this->_build_directory);
		$debug = "running shell script $path/builder_setup.sh<br>";
		
		$params = $this->_client['username'] . 
				" " . $this->_client['password'] . 
				" " . $this->_client['dbname']. 
				" " . "/home/".$this->_client['username']. "/public_html". 
				" " . $this->_client['domain']. 
				" " . $this->_client['dbuser'];
		//echo $params."<br>";
		//echo $this->_client['server']."<br>";
		// run shellscript
		exec("ssh root@".$this->_client['server']. " 'bash -s' < $path/builder_setup.sh $params",$verbose,&$shell);
		$debug .= "output of  shell script<br>";
		$debug .= "Shell exit code:".$shell ."<br>";
		foreach($verbose as $line):
			$debug .= $line;
		endforeach;
		
		if($shell !== 0):

			/*exec("whoami", $who, $whocode);
			exec("pwd", $dir, $dircode);
			exec("ssh root@".$this->_client['server']. " ls",$remote,$remcode);

			$json = array(
				'shell'   => $shell,
				'cmd'     => "ssh root@".$this->_client['server']. " 'bash -s' < $path/builder_setup.sh $params",
				'debug'   => $debug,
				'verbose' => $verbose,
				'client'  => $this->_client,
				'whoami'  => array(
					'cmd'    => 'whoami',
					'output' => $who,
					'code'   => $whocode
				),
				'where'   => array(
					'cmd'    => 'pwd',
					'output' => $dir,
					'code'   => $dircode
				),
				'remote'  => array(
					'cmd'    => "ssh root@".$this->_client['server']. " ls",
					'output' => $remote,
					'code'   => $remcode
				)
			);
			@mail('travis.loudin@brainhost.com','builder shell output', json_encode($json));*/
			
				
			$output['data'] =  "Failed to run shelscript:".$debug ;
			$output['error']= "Failed to run shelscript:".$debug.$this->error->code($this, __DIR__,__LINE__);
			return $output;
				
		endif;
		// if partner_id is set make api calls to move configs over
		//var_dump($this->_client);
		if($this->_client['partner_id']) :
			
			$postvar = array(
					'partner_id' => $this->_client['partner_id']
			);
			$pricing = $this->platform->post('partner/website/upload_prices_config',$postvar);
			if(!$pricing['success']):
				
				$output['data'] =  $debug.'Failed to upload Extreme prices config' . json_encode($pricing['error']) ;
				$output['error']= $this->error->code($this, __DIR__,__LINE__);
				return $output;
				
			endif;
			$options = $this->platform->post('partner/website/upload_options_config',$postvar);
			if( ! $options['success']):
				
				$output['data'] = $debug.'Failed to upload Famous Options config'. json_encode($options['error']);
				$output['error']= $this->error->code($this, __DIR__,__LINE__);	
				return $output;
				
			endif;
			
			$pixels = $this->platform->post('partner/website/upload_pixels_config',$postvar);
			if( ! $pixels['success']):
				
				$output['data'] = $debug.'Failed to upload Pixar Pictures pixel config'. json_encode($pixels['error']);
				$output['error']= $this->error->code($this, __DIR__,__LINE__);	
				return $output;
				
			endif;
		endif;
			
		endif;
		// if we made it here, we successfully built the website
		//echo "<a href='/builder/builderlist'>Back to queue</a>";
		$output['success']	= TRUE;
		$output['data']		= $debug;
		$output['error']	= FALSE;
		return $output;
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
		$this->_string_replace($temp);
		
		// rezip files
		$this->_rezip($temp);
		
		// remove files from tmp
		$this->_delete_all_files($temp,array('website.tgz'));
		
		// add files to ftp
		$this->_add_files_to_ftp($temp,'\public_html/');

		// remove temp dir
		$this->_remove_directory($temp);

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
		$file 		= str_replace('\\','/',$source.$file);
		if(ftp_put($this->_conn, $remote, $file, FTP_BINARY)) :
			
			return true;
		else : 
			echo "file failed to upload";
			return false;
		endif;
	
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
		// what is happening below?
		// change directory into the temp website directory and then tar up the files so they are in one nice little directory inside the tarball.
		$source = realpath($source);
		$zip = system("cd $source;tar czf ./website.tgz ./",$verbose);
		return $zip;
	}
	
	private function _create_temp_directory($dir)
	{
		// if temp directory is already present, remove it
		if (is_dir($dir)):
			$this->_remove_directory($dir);
		endif;

		// create temp dir
		if ( ! mkdir($dir)):
			return FALSE;
		endif;

		return TRUE;
	}

	/**
	 * Remove Directory
	 * 
	 * This method removes a directory and all files within it recursively
	 */
	private function _remove_directory($source)
	{
		// create real path from $dir
		$source	= str_replace('\\', '/', realpath($source));
		// remove the directory
		
		if(preg_match("/\/home\/infrastr\/ci\/infrastructure\/custom\/modules\/builder\/builds\/temp\//",$source)):
			$ll = system("rm -rf $source",$verbose);
		endif;
		
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
		// error handling
		if ( ! $build['success'] OR empty($build['data'])):
			return FALSE;
		endif;
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
				$withval = $this->_client[$with];
				//grep -rl '$string' ./|xargs sed -i 's/$string$withval/g'  WINNER
				//find /home/infrastr/ci/infrastructure/custom/modules/builder/builds/temp/nenabeana.com -name "*.php" -o -name "*.sql" -o -name "*.html" -o -name "*.js" -o -name "*.css" -print0 | xargs sed -i 's/freesola_baby/nena_babytest1/g' 
				//find /home/infrastr/ci/infrastructure/custom/modules/builder/builds/temp/nenabeana.com -type f -name "*.php" -o -name "*.sql" -o -name "*.html" -o -name "*.js" -o -name "*.css" -exec sed -i'' -e 's/freesola_baby/1834king/g' {} +
				//echo "find $source -name \"*.php\" -o -name \"*.sql\" -o -name \"*.html\" -o -name \"*.js\" -o -name \"*.css\" -print0 | xargs -0 sed -i 's/$string/$withval/g' <br>";
				$replacement = system("cd $dir;grep -rl '$string' ./|xargs sed -i 's/$string/$withval/g'",$verbose);
				//echo "$dir -- $string/$withval .<br>";
				endif;

			endforeach;	// End iterating through all replace strings
		
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
		if ( ! $this->_conn	= @ftp_connect($this->_client['domainbase'])):
			return FALSE;
		endif;

		// login with provided credentials
		if ( ! @ftp_login($this->_conn,$this->_client['username'],$this->_client['password'])):
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
		$this->_client['dbname']	= $this->_client['username'].'_build';
		$this->_client['dbuser']	= $this->_client['dbname'];
		$this->_client['fdbuser']	= $this->_client['username'].'_builder';
		$this->_client['server']	= $client['data']['metadata']['install_server_ip'];
		$this->_client['domainbase']= $client['data']['metadata']['core_domain_name'];
		$this->_client['domain']	= str_replace("http://http://","http://","http://".$client['data']['metadata']['core_domain_name']);
		//var_dump($this->_client);
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
	private function _response($success=TRUE,$error=FALSE,$data=FALSE)
	{
		// echo results
		echo json_encode($this->api->response($success,$error,$data));
	}
}
