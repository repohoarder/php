<?php

// initialize variables
$file 		= (isset($_REQUEST['file']))? $_REQUEST['file']: 'website.zip';

echo '<p>attempting to unzip via ZipArchive...</p>';

// this file will unzip the site files
$unzip 		= _unzip($file);

// if unsuccessful, display error
if ($unzip !== TRUE):

	// show error
	echo '<p style="font-weight:bold;color:red;">'.$unzip.'</p>';

	echo '<p>attempting to unzip via command line (linux only)...</p>';

	// attempt to unzip via command line (unix)
	$unzip 	= _unzip_linux($file);

	// if unsuccessful, display error
	if ($unzip !== TRUE):

		// show error
		echo '<p style="font-weight:bold;color:red;">'.$unzip.'</p>';

		echo 'Unable to automatically unzip file.  You must unzip manually.';
		exit;

	endif;

endif;

// remove zip file
@unlink($file);

// remove this file
@unlink('_unzip.php');

// if we made it here, then things were successful
echo 'SUCCESS!';













function _unzip($file='website.zip')
{
	// verify we are able to find the proper file to unzip
	if ( ! is_file($file))
		return 'Unable to find file: '.$file;

	// new zip instance
	$zip 	= new ZipArchive();

	// if we weren't able ot open the file, return error
	if ( ! $zip->open($file, ZIPARCHIVE::CREATE))
		return 'Unable to load ZipArchive';

	// extract zip
	if ( ! $zip->extractTo(getcwd()))
		return 'There was an error extracting the file.';

	// close
	$zip->close();

	return TRUE;
}

function _unzip_linux($file='website.zip')
{
	return system("unzip ".$file);
}



