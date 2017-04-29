<?php

// archives directory
$config['build_archives']	= '../../infrastructure/custom/modules/builder/builds/archives/';

// build type directory
$config['build_directory']	= '../../infrastructure/custom/modules/builder/builds/';

// zip filename
$config['zip_filename']		= 'website.zip';

// sql filename
$config['sql_filename']		= 'database.sql';

// file extensions to do search and replace against
$config['ext_to_replace']	= array(
	'php',
	'html',
	'css',
	'htm',
	'txt',
	'js'
);
$config['dropdown'] =array(
	'client_id'		=> 'Client ID',
	'domainbase'	=> 'Domain Base',
	'first'			=> 'First Name',
	'last'			=> 'Last Name',
	'email'			=> 'Email',
	'username'		=> 'Username',
	'password'		=> 'Password',
	'dbname'		=> 'Database Name',
	'dbuser'		=> "Database User"
);