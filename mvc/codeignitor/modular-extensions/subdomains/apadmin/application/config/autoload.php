<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(CUSTOM_PATH.'config/autoload.php');

$autoload['config'][] 		= 'platform';
$autoload['config'][] 		= 'subdirectory';
$autoload['libraries'][]	= 'partners';
$autoload['libraries'][]	= 'authenticate';
$autoload['libraries'][]	= 'pageauth';
$autoload['libraries'][]	= 'menu';
$autoload['libraries'][]	= 'champion';