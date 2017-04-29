<?php 

## Hack to get the offers controller to work within this module
// initialize variables
$is_offers 	= FALSE;

$uri 		= $_SERVER['REQUEST_URI'];
$exp 		= explode('/',$uri);

// see if we are trynig to load offers module
if (isset($exp['2']) AND $exp['2'] == 'offers')	$is_offers = TRUE;
## End hack to get offers controller to work within this module


// see if we are attempting to load the offers controller
if ( ! $is_offers):

	// we are attempting to load bonus controller
	$route['bonus/(:any)']	= 'bonus/index/$1';

else:

	// see if we are just initializing one-click process or redirecting to the page
	if (isset($exp['3']) AND $exp['3'] == 'init'):

		// we are attempting to load offers controller
		$route['bonus/offers/init/(:any)']	= 'offers/init/$1/$2/$3/$4/$5';

	else:

		// we are attempting to load offers controller
		$route['bonus/offers/(:any)']	= 'offers/index/$1/$2/$3/$4/$5';

	endif;

endif;