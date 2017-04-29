<?php

// location of subdirectory
$config['subdir']	= '/admin';

// if we are coding locally - then we don't append the subdirectory
if ($_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '69.175.70.227'):

	$config['subdir']	= '';

endif;