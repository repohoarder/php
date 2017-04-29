<?php

foreach ($dates AS $key => $value):
	// show link to that user's stats
	echo '<p><a href="/clicktale/show_date/'.$value['CYear'].'-'.$value['CMonth'].'-'.$value['CDay'].'">'.$value['CYear'].'-'.$value['CMonth'].'-'.$value['CDay'].'</a></p>';
endforeach;