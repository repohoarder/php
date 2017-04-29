<?php

$config['products'] = array(
	'Domain Products'   => array(
		'Addon Domain'     => 'addon_domain',
		'Domain Insurance' => 'domain_insurance'
	),
	'Hosting Products'  => array(
		'Double Hosting' => 'double_hosting',
	),
	'Business Products' => array(
		'Business Domain'  => 'business_domain',
	),
	'Website Traffic Products' => array(
		'Traffic Package'  => 'traffic',
		'SEO Package'      => 'seo_package',
	),
	'Bundles'           => array(
		'Platinum Package' => 'platinum_package',
	),
);

$config['order_confirmation'] = array(
	'No Confirmation Page'  => 'completed',
	'Add Confirmation Page' => 'confirmation' 
);

$config['tied_products'] = array( // bundles pages together based on specific action or type of action
	'traffic' => array(
		'double_traffic' => 'add_service' // add_service, no_service, or action_id
		/*
		add_service means the action adds a service, or a "yes button"
		no_service means user chose not to add service, or "no button"
		a numeric action id will instead tie to a specific page
		*/
	),
	'platinum_package'   => array(
		'platinum_package_discount' => 'no_service'
	)
);


$config['max_products'] = 7;


/*
$config['products'] = array(
	'Domain Products' => array(
		'Addon Domain'     => 'addon-domain',
		'Domain Privacy'   => 'domain-privacy',
		'Domain Insurance' => 'domain-insurance',
	),
	'Business Products' => array(
		'Business Domain'  => 'business-domain',
		'Customized Email' => 'customized-email',
		'Shopping Cart'    => 'shopping-cart',
		'Internet Fax'     => 'internet-fax',
	),
	'Website Creation Products' => array(
		'Mobile Website'  => 'mobile-website',
		'Web Builder'     => 'website-builder',
		'Content Writing' => 'content-writing',
		'Custom Videos'   => 'custom-videos',
	),
	'Marketing Products' => array(
		'Marketing Suite' => 'marketing-master-suite',
		'Email Marketing' => 'email-marketing',
	),
	'Website Traffic Products' => array(
		'Traffic Package'          => 'traffic-package',
		'SEO Package'              => 'seo-package',
		'Google Places'            => 'google-local-places',
		'Search Engine Submission' => 'search-engine-submission',
	),
	'Core Products' => array(
		'Dedicated Service Rep.' => 'dedicated-representative',
		'Hosting'                => 'hosting',
		'Weblock'                => 'weblock',
	),
	'Bundles' => array(
		'Search Engine Package' => 'search-engine-package',
		'Domain Essentials'     => 'domain-essentials',
		'Premium Package'       => 'premium-package',
		'Security Package'      => 'security-package'
	)
);
*/