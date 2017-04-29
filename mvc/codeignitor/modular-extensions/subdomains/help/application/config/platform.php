<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['platform']	= array(
	'url'	=> 'http://help.brainhost.com/',	// This is the Platform URL to use
	'salt'	=> 'H3LPS41T',
	'app'	=> 'help.brainhost.com'
);

if ($_SERVER['SERVER_ADDR'] == '127.0.0.1'):

	$infra_subdomain = explode('.',$_SERVER['SERVER_NAME']);
	array_shift($infra_subdomain);

	$config['platform']['url'] = 'http://platform.'.implode('.',$infra_subdomain);
	
endif;