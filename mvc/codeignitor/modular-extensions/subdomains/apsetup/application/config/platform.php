<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['platform']	= array(
	'url'	=> 'http://platform.brainhost.com/',	// This is the Platform URL to use
	'salt'	=> 'UseTh1554LtF04AlLPH453',
	'app'	=> 'a.allphasehosting.com'
);


if ($_SERVER['SERVER_ADDR'] == '127.0.0.1'):

	$infra_subdomain = explode('.',$_SERVER['SERVER_NAME']);
	array_shift($infra_subdomain);

	$config['platform']['url'] = 'http://platform.'.implode('.',$infra_subdomain).'/';
	
endif;