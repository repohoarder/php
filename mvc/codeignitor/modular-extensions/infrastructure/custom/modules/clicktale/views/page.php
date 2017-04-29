<?php

foreach ($pages AS $key => $value):
	// show link to that user's stats
	echo '<p><a href="/clicktale/show_page/?URL='.$value['URL'].'">'.$value['URL'].'</a></p>';
endforeach;