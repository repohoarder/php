<?php

/**
 * CSV
 * 
 * This class handles CSV functionality
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @method boolean	create($path,$data,$download)
 * 
 */
class Csv
{
	
	/**
	 * Create CSV
	 * 
	 * This method creates a CSV file from data passes to it
	 * 
	 * @param	string	$file			The filename of the CSV to create
	 * @param	array	$csv			The array of data to insert into the CSV
	 * @param	boolean	$downloadable	A boolean to determine whether or not to prompt a download for this CSV
	 * 
	 * @return boolean
	 */
	public function create($file="csv.csv",$csv=array(),$downloadable=FALSE)
	{	
		
		// add timestamp to filename
		$file	= str_replace('.csv','',$file).'_'.date('U').'.csv';

		
		// see if user wants to just download this file from the browser
		if($downloadable === TRUE):
			
			return $this->_download($file,$csv);
			
		endif;
		
		// open the file
		$fp = fopen($file, 'w+');
		
		// iterate through each data array
		foreach ($csv as $fields) {
			// add the data to the CSV
		    fputcsv($fp, $fields);
		}
		
		// close
		fclose($fp);
		
		return TRUE;
	}
	
	private function _download($file,$csv=array())
	{
		// open the temp file
		$fp = tmpfile();
		
		// iterate through each data array
		foreach ($csv as $fields) {
			// add the data to the CSV
		    fputcsv($fp, $fields);
		}

		rewind($fp);

		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename='.$file);
		
		// outputs temp file
		fpassthru($fp);

		// close
		fclose($fp);
		
		return TRUE;
	}
	
}