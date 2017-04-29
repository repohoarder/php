<?php

$handle = tmpfile();

if (isset($headers) && is_array($headers)):

	fputcsv($handle, $headers);

endif;

if (isset($rows) && is_array($rows)):

	foreach ($rows as $row):

		fputcsv($handle, $row);

	endforeach;

endif;

rewind($handle);

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename="report_' . date('U') . '.csv"'); 

fpassthru($handle); // outputs the tmp file

fclose($handle); 