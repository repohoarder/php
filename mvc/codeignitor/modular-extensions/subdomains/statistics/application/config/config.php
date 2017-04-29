<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(CUSTOM_PATH.'config/config.php');

$config['modules_locations'][APPPATH.'modules/'] = '../modules/';


$config['sess_use_database']	=($_SERVER['SERVER_ADDR']=='127.0.0.1')? FALSE : FALSE;