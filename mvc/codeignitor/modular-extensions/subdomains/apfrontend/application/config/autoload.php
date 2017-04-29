<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(CUSTOM_PATH.'config/autoload.php');

$autoload['config'][] = 'platform';
$autoload['config'][] = 'partner';

// autoload authenticate library (to auth logged in user)
//$autoload['libraries'][] = 'authenticate';