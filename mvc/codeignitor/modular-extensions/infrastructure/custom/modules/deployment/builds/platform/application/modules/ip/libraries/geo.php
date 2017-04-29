<?php

class Geo {
	
	function ip_to_country_code($ip = FALSE) {
	
		if ( ! $ip):
			
			return FALSE;
			
		endif;
		
		$lib_folder = APPPATH.'modules/ip/libraries/geoip/';
		
		require_once($lib_folder.'geoip.inc.php');
		
		$gi = geoip_open($lib_folder.'geoip.dat',GEOIP_STANDARD);
		
		return geoip_country_code_by_addr($gi,$ip);
	
	}

	function get_record($ip = FALSE)
	{

		if ( ! $ip):
			
			return FALSE;
			
		endif;
		
		$lib_folder = APPPATH.'modules/ip/libraries/geoip/';

		require_once($lib_folder.'geoipcity.inc.php');
		require_once($lib_folder.'geoipregionvars.php');
		
		$gi = geoip_open($lib_folder.'GeoIPCity.dat',GEOIP_STANDARD);
		
		return geoip_record_by_addr($gi,$ip);

	}

}