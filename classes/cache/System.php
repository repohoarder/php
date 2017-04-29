<?php

namespace Cache;

class System
{
	var $_dir 		= '';
	var $_expiry 	= '18000';	// seconds

	public function __construct($dir=false,$expiry=FALSE)
	{
		// set cache dir
		if ($dir)
			$this->_dir 	= $dir;

		// set expiry 
		if ($expiry)
			$this->_expiry 	= $expiry;
	}

	public function Set($key,$value,$expiry=FALSE,$debug=TRUE)
	{
		// if coding locally, don't cache anything
		if ($debug AND $_SERVER['REMOTE_ADDR'] == '127.0.0.1')
			return TRUE;

		// init vars
		$filename	= 'cache-'.$key.'.html';
		$filepath 	= $this->_dir.$filename;

		// if expiry passed, override existing 
		$expiry 	= ($expiry)? $expiry: $this->_expiry;

		// determine if file already exists
		if (file_exists($filepath)):

			if ((time() - $expiry) < filemtime($filepath)):

				return TRUE;	// cached file found

			endif;

			// if we made it here, the cache file exists, but was outside of expiry - delete the file
			@unlink($filepath);

		endif;

		// open new file
		$cached 	= @fopen($filepath,'w');

		// write value to new file
		@fwrite($cached,$value);

		// close file
		@fclose($cached);

		// return
		return TRUE;
	}

	public function Get($key)
	{
		// init vars 
		$filename	= 'cache-'.$key.'.html';
		$filepath 	= $this->_dir.$filename;		

		// see if file exists
		if (file_exists($filepath) AND (time() - $this->_expiry) < filemtime($filepath)):

			// return value found in file 
			return file_get_contents($filepath);

		endif;

		// cached file doesn't exist, return false
		return FALSE;
	}
}