<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// GetResponse API URL
$getresponse_api	= 'http://api.getresponse360.com/brainhost';


$weblumps 	= array(
	'brazil'				=> 'M',
	'brazil_buyers'			=> 'g'
);

## Brain Host Lists
$bh_lists	= array(
	'affiliate_sign_up'		=> 'pkZr',
	'clients'				=> 'VAQy',
	'signup'				=> 'pIL9',
	'affiliate_sales'		=> 'p9WI',
	'renewals'              => 'p0A3',
	'refunded'              => 'piFd',
	'declines'              => 'VAQK',
	'new_partials'          => 'pvpN',
	'small_business'		=> 'pD8g',
	'essent_partials'		=> 'psRd',
	'domain_renewals_bh'    => 'nifO',
	'hosting_renewals_bh'   => 'nifu',
	'refunded'				=> 'piFd',
	'brazil_pagseguro'      => 'nmVK'
	/*
	'par_signups' => 
		GetResponse account: marketingsuccess2
		campaign: marketingsuccess2
	*/
);


$mkt_lists = array(
	'par_signups'           => 'p7Vp',
	'smms'					=> 'nKM4'
);

## Brain Host Lists
$brazil_lists	= array(
	'affiliate_sign_up'		=> 'pGjP',
	'clients'				=> 'pGAB',
	'brazil_pagseguro'      => 'nmVK'
);

## Purely Hosting Lists
$ph_lists	= array(
	'affiliate_sign_up'		=> 'pFFt',
	'clients'				=> 'VROo',
	'domain_renewals'		=> 'nK9j',
	'hosting_renewals'		=> 'nK9Z',
	'refunded'				=> 'VROP'
);

## All Phase Lists
$ap_lists	= array(
	'clients'				=> 'pEBU'
);

$smms_lists = array(
	'smms_drop'=>'nPR8'
);


$config['weblumps.com']			= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> 'a1ba4d5edbed75ba7edaea15479239b9',	// http://www.gerenciamidiassociais.com/
	'lists'		=> $weblumps
);

$config['www.weblumps.com']			= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> 'a1ba4d5edbed75ba7edaea15479239b9',	// http://www.gerenciamidiassociais.com/
	'lists'		=> $weblumps
);
















// Brain Host - Cronjobs
$config['smms']			= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> 'b8f5ab82aff954e2b3e9a7a05cb63fa9',	// Brain Host (brainhost_vip) key
	'lists'		=> $smms_lists
);
// Brain Host - Cronjobs
$config['brainhost_crons']			= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> '940f11d62495b78ba96726861131392f',	// Brain Host (brainhost_vip) key
	'lists'		=> $bh_lists
);

// Brain Host - Domain Only Funnel
$config['domains.brainhost.com']	= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> '940f11d62495b78ba96726861131392f',	// Brain Host (brainhost_vip) key
	'lists'		=> $bh_lists
);

// Brain Host - Sales Funnel
$config['orders.brainhost.com']		= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> '940f11d62495b78ba96726861131392f',	// Brain Host (brainhost_vip) key
	'lists'		=> $bh_lists
);

// Brain Host - Admin
$config['admin.brainhost.com']	= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> '940f11d62495b78ba96726861131392f',	// Brain Host (brainhost_vip) key
	'lists'		=> $bh_lists
);

// Brain Host - Affiliate System
$config['affiliate.brainhost.com']	= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> '940f11d62495b78ba96726861131392f',	// Brain Host (brainhost_vip) key
	'lists'		=> $bh_lists
);

// Brain Host - Client Central Interface
$config['clients.brainhost.com']	= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> '940f11d62495b78ba96726861131392f',	// Brain Host (brainhost_vip) key
	'lists'		=> $bh_lists
);

// Brain Host - Client Central Interface
$config['setup.brainhost.com']	= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> 'b8f5ab82aff954e2b3e9a7a05cb63fa9',	// marketingsuccess2 key
	'lists'		=> $mkt_lists
); 

// Brain Host - Client Central Interface
$config['infrastructure.brainhost.com']	= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> '940f11d62495b78ba96726861131392f',	// Brain Host (brainhost_vip) key
	'lists'		=> $bh_lists
);

// Purely Hosting - Cronjobs
$config['purelyhosting_crons']			= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> 'c55456a538b5161cc2ba3a98e317865b',	// Purely Hosting (purelyhosting) Key
	'lists'		=> $ph_lists
);

// Purely Hosting - Sales Funnel
$config['orders.purelyhosting.com']		= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> 'c55456a538b5161cc2ba3a98e317865b',	// Purely Hosting (purelyhosting) Key
	'lists'		=> $ph_lists
);

// Purely Hosting - Sales Funnel
$config['setup.purelyhosting.com']		= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> 'c55456a538b5161cc2ba3a98e317865b',	// Purely Hosting (purelyhosting) Key
	'lists'		=> $ph_lists
);

// Purely Hosting - Affiliate System
$config['affiliate.purelyhosting.com']	= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> 'c55456a538b5161cc2ba3a98e317865b',	// Purely Hosting (purelyhosting) Key
	'lists'		=> $ph_lists
);


// All Phase - Sales Funnel
$config['infrastructure.hostingaccountsetup.com']	= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> '940f11d62495b78ba96726861131392f',	// Brain Host (brainhost_vip) key
	'lists'		=> $ap_lists
);

// Brain Host Brazil - Affiliate System
$config['affiliate.brainhost.com.br']	= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> '940f11d62495b78ba96726861131392f',	// Brain Host (brainhost_vip) key
	'lists'		=> $brazil_lists
);

// Brain Host Brazil - Affiliate System
$config['orders.brainhost.com.br']	= array(
	'url' 		=> $getresponse_api,
	'api_key'	=> '940f11d62495b78ba96726861131392f',	// Brain Host (brainhost_vip) key
	'lists'		=> $brazil_lists,
	'database'  => 'brazil_orders'
);

