<?php 

// set test invoice id (for thank you page)
$config['test_invoice_id']		= '11111';

// set test client id
$config['test_client_id']		= '101640';

// set test invoice items (for thank you page)
$config['test_invoice_items']	= array(
	array(
		'desserv'	=> 'Brain Host Unlimited Special Package (bhaff)',
		'period'	=> '1',
		'cost'		=> '14.95'
	),
	array(
		'desserv'	=> 'Brain Host Unlimited Special Package--Setup Fee (setp)',
		'period'	=> '0',
		'cost'		=> '20.00'
	),
	array(
		'desserv'	=> 'Core: google.com (domain)',
		'period'	=> '12',
		'cost'		=> '14.95'
	),
	array(
		'desserv'	=> 'Domain Privacy (domainp)',
		'period'	=> '12',
		'cost'		=> '11.95'
	)
);

// set test invoice credits (for thank you page)
$config['test_invoice_credits']	= array(
	array(
		'reason'	=> 'PayPal 15% OFF',
		'amount'	=> '-12.24'
	)
);

// set test invoice amount (for thank you page)
$config['test_invoice_amount']	= '49.61';

// set test client id (for thank you page)
$config['test_invoice_amount']	= '11111';

// set array of periods
$config['periods']				= array(
	'0'		=> 'one-time fee',
	'1' 	=> 'monthly',
	'6'		=> 'semiannual',
	'12'	=> 'annual',
	'24'	=> 'biennial',
	'36'	=> 'triennial',
	'48'	=> 'quadrennial'
);

?>