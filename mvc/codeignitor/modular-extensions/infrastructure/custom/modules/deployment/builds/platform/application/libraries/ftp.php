<?php

/*
 * FTP
 * 
 * This class handles FTP functionality
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @method boolean	put(string $host, string $user, string, $pass, string, $local_file, string $remote_file)	This method sends a local file to a remote FTP
 * 
 */
class Ftp
{
	public function put($host,$user,$pass,$local_file,$remote_file)
	{
		// connect
		$conn	= ftp_connect($host);
		
		// login
		$login	= ftp_login($conn,$user,$pass);
		
		// attempt to send file to FTP host
		$success	= (ftp_put($conn,$remote_file,$local_file,FTP_ASCII))
			? TRUE
			: FALSE;
		
		// clsoe connection
		ftp_close($conn);
		
		return $success;
	}
	
}