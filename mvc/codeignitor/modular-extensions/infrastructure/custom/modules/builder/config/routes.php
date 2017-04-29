<?php 

$route['^builder/install/((?!rebuild_partners).*)'] = 'install/index/$1';
$route['builder/edit/(:any)'] = 'edit/index/$1';

