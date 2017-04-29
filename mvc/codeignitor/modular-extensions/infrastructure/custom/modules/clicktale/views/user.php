<?php

foreach ($users AS $key => $value):
	// show link to that user's stats
	echo '<p><a href="/clicktale/show_user/'.$value['Session'].'">'.$value['Session'].'</a></p>';
endforeach;