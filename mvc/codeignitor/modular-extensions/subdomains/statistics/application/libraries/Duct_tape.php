<?php

class Duct_tape {


	function fix_series_gaps($rdata)
	{

		//return $rdata;

		$all_dates = array();
		$new_data = array();

		# Loop all data
		foreach ($rdata as $name => $series):

			# Loop all series to determine the dates in the series
			foreach ($series as $series_key => $series_array):

			
				if ((int)$series_array['month']<10)
				{
					$series_array['month']='0'.$series_array['month'];
				}
				if ((int)$series_array['day']<10)
				{
					$series_array['day']='0'.$series_array['day'];
				}
				# Get the current date as a sortable integer
				$item_date  = $series_array['year'] . $series_array['month'] . $series_array['day'];

				# Get the date array for the current series
				$date_array = $series_array;
				unset($date_array['amount']);

				# Assign a searchable array key to the current series date
				$new_data[$name][$item_date] = $series_array;

				# Associate the searchable array key with the date array
				$all_dates[$item_date]       = $date_array;

			endforeach;

		endforeach;
		ksort($all_dates);
		
		# Loop all dates available in all series
		foreach ($all_dates as $d => $date_array):

			# Loop all series
			foreach ($new_data as $name => $items):

				if (count($items) == count($date_array)):

					//continue;

				endif;

				# Check if the current series has the current date item
				if ( ! array_key_exists($d, $new_data[$name])):

					# Insert an empty array amount into the current series
					$new_data[$name][$d]           = $date_array;
					$new_data[$name][$d]['amount'] = 0;

				endif;

			endforeach;

		endforeach;

		# Loop every series once more
		foreach ($new_data as $name => $items):

			# Sort based on searchable array key
			ksort($new_data[$name]);

		endforeach;


		# Set the data to the new sorted values
		$rdata = $new_data;

		# Memory kindness: clear the massive duplicated array
		unset($new_data);
		unset($all_dates);

		return $rdata;

	}


}