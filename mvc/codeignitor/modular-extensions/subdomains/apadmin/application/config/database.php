<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once(CUSTOM_PATH.'config/database.php');

#$dbhost = ($_SERVER['SERVER_ADDR']=='127.0.0.1') ? 'clusterdb1.brainhost.com' : 'dbhost';

$ap_dbhost     = 'db1.allphasehosting.com';

$active_group  = 'funnel';
$active_record = TRUE;

$db['funnel']  = $db['default'];
$db['funnel']['hostname'] = $ap_dbhost;
$db['funnel']['username'] = 'infrastr_funel';
$db['funnel']['password'] = 'r2+ap#uZa78+r@';
$db['funnel']['database'] = 'infra_funnel';