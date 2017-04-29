<?php

// location of subdirectory
$config['subdir']	= '/setup';

// if we are coding locally - then we don't append the subdirectory
if ($_SERVER['SERVER_ADDR'] == '127.0.0.1'):

	$config['subdir']	= '';

endif;