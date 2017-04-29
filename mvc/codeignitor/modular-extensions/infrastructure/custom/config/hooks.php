<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// This hook redirects user back to login if they are not auth'd in an area in which you need proper credentials
$hook['pre_controller'][] = array(
	'class'    => 'Login',
	'function' => 'check',
	'filename' => 'login.php',
	'filepath' => 'hooks'
);

// This hook sets language config item
$hook['pre_controller'][] = array(
	'class'    => 'Set',
	'function' => 'language',
	'filename' => 'set.php',
	'filepath' => 'hooks'
);










/* End of file hooks.php */
/* Location: ./application/config/hooks.php */