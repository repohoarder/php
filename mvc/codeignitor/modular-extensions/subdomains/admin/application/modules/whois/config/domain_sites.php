<?php

// This config will hold array of CSV information by each site we grab domains from

$config['sites']	= array(
	'dailychanges.com'
);


$config['dailychanges.com']	= array(
	'fields'		=> array(
		// Field 		=> CSV Key
		'domain'		=> 0
	),
	'where'			=> array(
		// Column Key  => Equals
		1 	=> 'new'
	)
);