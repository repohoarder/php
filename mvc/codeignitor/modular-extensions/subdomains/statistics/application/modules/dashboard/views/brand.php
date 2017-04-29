<?php


foreach ($reports as $report):

	echo Modules::run($report, $brand);

endforeach;