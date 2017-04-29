<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(CUSTOM_PATH.'config/hooks.php');


/*
This is no longer needed since I've moved auth into a library that gets autoloaded
$hook['pre_controller'] = array(
	'class'    => 'Authenticate',
	'function' => 'partner',
	'filename' => 'authenticate.php',
	'filepath' => 'hooks'
);
*/
