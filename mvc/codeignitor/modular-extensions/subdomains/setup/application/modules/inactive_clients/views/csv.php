<?php

$handle = tmpfile();

fputcsv($handle, $headings);

foreach ($rows as $row):

	fputcsv($handle, $row);

endforeach;

rewind($handle);

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename="csv_'.date('U') . '.csv"'); 

fpassthru($handle); // outputs the tmp file
fclose($handle); 