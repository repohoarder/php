<?php

$config['operating_systems']	= array(
	'iPhone' 			=> '(iPhone)',
	'Windows 3.11' 		=> 'Win16',
	'Windows 95' 		=> '(Windows 95)|(Win95)|(Windows_95)', // Use regular expressions as value to identify operating system
	'Windows 98' 		=> '(Windows 98)|(Win98)',
	'Windows 2000'		=> '(Windows NT 5.0)|(Windows 2000)',
	'Windows XP' 		=> '(Windows NT 5.1)|(Windows XP)',
	'Windows 2003'		=> '(Windows NT 5.2)',
	'Windows Vista' 	=> '(Windows NT 6.0)|(Windows Vista)',
	'Windows 7' 		=> '(Windows NT 6.1)|(Windows 7)',
	'Windows NT 4.0'	=> '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
	'Windows ME' 		=> 'Windows ME',
	'Open BSD'			=> 'OpenBSD',
	'Sun OS'			=> 'SunOS',
	'Linux'				=> '(Linux)|(X11)',
	'Safari' 			=> '(Safari)',
	'Macintosh'			=> '(Mac_PowerPC)|(Macintosh)',
	'QNX'				=> 'QNX',
	'BeOS'				=> 'BeOS',
	'OS/2'				=> 'OS/2',
	'Search Bot'		=> '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
);

$config['browsers']				= array(
	'MSIE'		=> array(
		'bname'	=> 'Internet Explorer',
		'ub'	=> 'MSIE'
	),
	'Firefox'	=> array(
		'bname'	=> 'Mozilla Firefox',
		'ub'	=> 'Firefox'
	),
	'Chrome'	=> array(
		'bname'	=> 'Google Chrome',
		'ub'	=> 'Chrome'
	),
	'Safari'	=> array(
		'bname'	=> 'Apple Safari',
		'ub'	=> 'Safari'
	),
	'Opera'		=> array(
		'bname'	=> 'Opera',
		'ub'	=> 'Opera'
	),
	'Netscape'	=> array(
		'bname'	=> 'Netscape',
		'ub'	=> 'Netscape'
	),
	'Seamonkey'	=> array(
		'bname'	=> 'Seamonkey',
		'ub'	=> 'Seamonkey'
	),
	'Konqueror'	=> array(
		'bname'	=> 'Konqueror',
		'ub'	=> 'Konqueror'
	),
	'Navigator'	=> array(
		'bname'	=> 'Navigator',
		'ub'	=> 'Navigator'
	),
	'Mosaic'	=> array(
		'bname'	=> 'Mosaic',
		'ub'	=> 'Mosaic'
	),
	'Lynx'		=> array(
		'bname'	=> 'Lynx',
		'ub'	=> 'Lynx'
	),
	'Amaya'		=> array(
		'bname'	=> 'Amaya',
		'ub'	=> 'Amaya'
	),
	'Omniweb'	=> array(
		'bname'	=> 'Omniweb',
		'ub'	=> 'Omniweb'
	),
	'Avant'		=> array(
		'bname'	=> 'Avant',
		'ub'	=> 'Avant'
	),
	'Camino'	=> array(
		'bname'	=> 'Camino',
		'ub'	=> 'Camino'
	),
	'Flock'		=> array(
		'bname'	=> 'Flock',
		'ub'	=> 'Flock'
	),
	'AOL'		=> array(
		'bname'	=> 'AOL',
		'ub'	=> 'AOL'
	),
	'AIR'		=> array(
		'bname'	=> 'AIR',
		'ub'	=> 'AIR'
	),
	'Fluid'		=> array(
		'bname'	=> 'Fluid',
		'ub'	=> 'Fluid'
	)
);