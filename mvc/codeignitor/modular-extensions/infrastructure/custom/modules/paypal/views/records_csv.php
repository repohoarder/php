<?php

$headers = $all_columns;

$result = $response['data']['records'];

$handle = tmpfile();

fputcsv($handle, $headers);

foreach ($result as $row):

	fputcsv($handle, $row);

endforeach;

rewind($handle);

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename="paypal_'.date('U') . '.csv"'); 

fpassthru($handle); // outputs the tmp file
fclose($handle); 