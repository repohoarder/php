<?php 

$route['statistics/sales/(:any)']				= 'sales/index/$1/$2/$3';
$route['statistics/visitors/(:any)']			= 'visitors/index/$1/$2/$3';
$route['statistics/estimatedrevenue/(:any)']	= 'estimatedrevenue/index/$1';
$route['statistics/tickets/(:any)']				= 'tickets/index/$1/$2/$3';
$route['statistics/calls/(:any)']				= 'calls/index/$1/$2/$3';