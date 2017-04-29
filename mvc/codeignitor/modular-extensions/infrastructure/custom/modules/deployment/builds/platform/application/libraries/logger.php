<?php

/**
 * Log
 * 
 * This class handles manipulation of log files.
 * 
 * @method array	read()
 * @method array	write(string $dir, string $file, string $message)
 * 
 */
class Logger
{
	
	/**
	 * Writes message to log file specified
	 * 
	 * This method writes a log message to the file specified
	 * 
	 * @access	public
	 * 
	 * @example	write()
	 * 
	 * @param	boolean	$path	The path fo the file to write to 
	 * 
	 * @return	boolean
	 */
	public function write($dir,$file,$message)
	{
		## TURN OFF LOGGING 10/17
		return TRUE;
		
		// initialize variables
		$root	= $_SERVER['DOCUMENT_ROOT'];
		
		// make sure dir exists, if not create it
		if ( ! is_dir($root.'/logs/'.$dir))	mkdir($root.'/logs/'.$dir, 0777, TRUE);
		
		// open handle
		$fp		= fopen($root.'/logs/'.$dir.'/'.$file, 'a');
		
		// write the message, if there weas an issue, return FALSE
		if (fwrite($fp,$message) === FALSE)
			return FALSE;
		
		// close pointer
		fclose($fp);
		
		// return
		return TRUE;
	}
}