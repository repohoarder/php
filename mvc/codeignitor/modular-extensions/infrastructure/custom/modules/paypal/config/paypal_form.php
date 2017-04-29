<?php

$config['paypal_form_production'] = array(
	'payment_url'   => 'https://www.paypal.com/us/cgi-bin/webscr',

	// 'business_acct' => 'corey@brainhost.com', 
	'business_acct' => 'ryan.niddel@brainhost.com',

	'logo'          => 'http://setup.brainhost.com/resources/brainhost/img/brainhost_big_wreck_tangle.png',
	// 'ipn_url'    => 'https://my.brainhost.com/ipn/paypal.php',
	'ipn_url'       => 'http://setup.brainhost.com/paypal/ipn',
	'return_url'    => 'https://orders.brainhost.com/paypal/returned/',
	'cancel_url'    => 'https://orders.brainhost.com/paypal/returned/',
);

$config['paypal_form_sandbox'] = array_merge(
	$config['paypal_form_production'],
	array(
		'payment_url'   => 'https://www.sandbox.paypal.com/us/cgi-bin/webscr',
		'business_acct' => 'matt.t_1349464808_biz@brainhost.com',
	)
);

$config['paypal_uber_ipn']            = 'https://my.brainhost.com/ipn/paypal.php';

$config['paypal_discount_percentage'] = 15;


$config['paypal_ipn_notify'] = array(
	'nena.abdullah@brainhost.com',
	'travis.loudin@brainhost.com',
	'matt.thompson@brainhost.com',
	'hana.lewis@brainhost.com',
	'chris.schlosser@brainhost.com',
	'john.davis@brainhost.com'
);