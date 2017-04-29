<?php

class Scrape
{
	public function __construct()
	{
		// set memory and time limits
		ini_set("memory_limit","0");
		ini_set("set_time_limit","0");		

		// initialize variables
		$this->_download 	= '/var/www/html/uploads/whois';
		$this->_username 	= 'jeff@freewebsite.com';
		$this->_password 	= 'G2ef5ReG';
	}

	public function create($days_back)
	{
		// create array of countries to iterate
		$countries 	= array(
			'US'	=> 'UNITED%20STATES',
			'UK'	=> 'UNITED%20KINGDOM',
			'AUS'	=> 'AUSTRALIA',
			'BR'	=> 'BRAZIL',
			'CA'	=> 'CANADA',
			'SZ'	=> 'SWITZERLAND',
			'NZ'	=> 'NEW%20ZEALAND',
			'SE'	=> 'SWEDEN',
			'LX'	=> 'LUXEMBOURG',
			'NW'	=> 'NORWAY',
			'PP'	=> 'PHILIPPINES',
			'IR'	=> 'ISRAEL'
		);

		//the TLDs to scrape for
		//biz, com, coop, info, mobi, net, org, pro , us
		$tlds = array(
			'com', 
			'info',  
			'net', 
			'org', 
			'biz', 
			'coop', 
			'mobi', 
			'pro', 
			'us', 
			'co',
			'ca',
			'co.uk',
			'gb',
			'au',
			'br',
			'nz',
			'se',
			'lu',
			'no',
			'ph',
			'il'			
		);

		// iterate countries
		foreach ($countries AS $abbr => $country):
						
			// for loop to go back X number of days
			for($i=1;$i<($days_back+1);$i++){

				// grab yesterdays date
				$yesterday_date_format = date('Y_m_d', strtotime('-' . $i . ' days'));

				// iterate through each tld we want to grab
				foreach((array)$tlds as $key=>$tld){
					
					// create URL
					$url = 'http://' . urlencode($this->_username) . ':' . urlencode($this->_password) . '@bestwhois.org/domain_name_data/domain_names_whois_filtered_reg_country_noproxy/filtered_reg_country_noproxy_' . $yesterday_date_format . '/' . $tld . '/'.$country.'/1.csv';
					
					// copy file to list marketer directory
					@copy($url, $this->_download . '/listmarketer/' . date('m-d-Y', strtotime('-' . $i . ' days')) . '_' . $abbr . '_' . $tld .'.csv');

					// also copy file to central location to be saved
					@copy($url, $this->_download . '/backup/' . date('m-d-Y', strtotime('-' . $i . ' days')) . '_' . $abbr . '_' . $tld .'.csv');
				}
			}

		endforeach;

		// foreach CSV in the directories, format to list marketer standards
		return $this->_listmarketer_format();
	}

	public function get()
	{

	}

	private function _listmarketer_format()
	{
		// initialize variables
		$csv 	= array();

		// open the dir
		$dir = new DirectoryIterator($this->_download.'/listmarketer/');

		// create single file with all data
		$fp 	= fopen($this->_download.'/listmarketer/listmarketer.csv','w');

		// create headers
		fputcsv($fp,array('First','Email','IP','Source Date','Domain'));

		// iterate all files in the directory
		foreach ($dir as $fileinfo):

			// make sure not . nor ..
			if ( ! $fileinfo->isDot()):

					// set filename
		        	$file 	= $fileinfo->getFilename();

		        	// make sure we're not grabbing the file we're creating
		        	if ($file != 'listmarketer.csv'):

						// grab file to import from uploads
						$handle 	= fopen($this->_download.'/listmarketer/'.$file,'r');

						// counter
						$counter = 0;

						while (($data = fgetcsv($handle,1000,',')) !== FALSE):

							// make sure email is set
							if (isset($data[2]) AND ! empty($data[2])):

								// initialize variables
								$name 	= @'"'.str_replace(',','',isset($data[14])? $data[14]: 'Friend').'"';
								$email 	= @'"'.$data[2].'"';
								$ip 	= '"'.rand(1,254).'.'.rand(1,254).'.'.rand(1,254).'.'.rand(1,254).'"';//str_replace('"','',$data[6]);
								$date 	= @'"'.$data[5].'"';
								$domain = @'"'.$data[0].'"';

								// increment counter
								$counter++;

								if ($counter > 1):

									// add the lead to the CSV
									//$csv[]	= array($name,$email,$ip,$date,$domain);
									fputcsv($fp, array($name,$email,$ip,$date,$domain),',',' ');

								endif;

							endif;

						endwhile;

						fclose($handle);

						// delete file
						unlink($this->_download.'/listmarketer/'.$file);

					endif;	// end making sure not listmarketer.csv

			endif;	// end no selecting . or ..

		endforeach;	// end iterating through files in directory

		// close file
		fclose($fp);

		return;
	}
}