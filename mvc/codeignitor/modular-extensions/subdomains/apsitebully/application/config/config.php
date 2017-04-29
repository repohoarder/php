<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(CUSTOM_PATH.'config/config.php');

$config['modules_locations'][APPPATH.'modules/'] = '../modules/';

$config['sess_cookie_name']		= 'yggdrasil';
$config['sess_expiration']		= 7200; # 2 hours
$config['sess_expire_on_close']	= TRUE;
$config['sess_encrypt_cookie']	= TRUE;
$config['sess_use_database']	= TRUE;
$config['sess_table_name']		= 'sess_brainhost';
$config['sess_match_ip']		= FALSE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update']	= 900; # 15 minutes