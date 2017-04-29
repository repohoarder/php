<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['platform']	= array(
	'url'	=> 'http://platform.brainhost.com/',	// This is the Platform URL to use
	'salt'	=> 'mattavgs1babyperyratBH',
	'app'	=> 'setup.brainhost.com'
);

if ( substr($_SERVER['HTTP_HOST'],0,14) == 'infrastructure'):

	$config['platform']['app'] = 'infrastructure.brainhost.com';

endif;


if ($_SERVER['SERVER_ADDR'] == '127.0.0.1'):

	$infra_subdomain = explode('.',$_SERVER['SERVER_NAME']);
	array_shift($infra_subdomain);

	$config['platform']['url'] = 'http://platform.'.implode('.',$infra_subdomain).'/';
	
endif;