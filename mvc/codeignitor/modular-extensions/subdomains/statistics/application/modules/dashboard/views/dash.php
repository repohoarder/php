<?php

$dash_reports = array(
	'avg_cust_val',
	//'sales_counts',
	'total_gross_rev',
	//'total_visits',
	//'sites_built', 
	//'pay_by_dept'
);


parse_str($_SERVER['QUERY_STRING'],$qry_str);

$omit = array();

if (isset($qry_str['omit'])):

	$omit = $qry_str['omit'];

	if ( ! is_array($omit)):

		$omit = array($omit);

	endif;

endif;

foreach ($dash_reports as $dreport):

	if ( ! in_array($dreport,$omit)):

		echo Modules::run($dreport, 'all_brands');

	endif;

endforeach;

