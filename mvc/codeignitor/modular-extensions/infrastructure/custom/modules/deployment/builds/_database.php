<?php

## this file will install the database


// make sure there's a database file to import
if ( ! is_file('database.sql')):

        echo '<p style="color:red;font-weight:bold;">Unable to find the SQL file to import.</p>';
        exit;

endif;


// initialize variables
$database       = $_REQUEST['database'];
$hostname       = $_REQUEST['hostname'];
$username       = $_REQUEST['username'];
$password       = $_REQUEST['password'];

// connect to database
$link           = mysqli_connect($hostname,$username,$password) or die('Unable to connect to database: '.mysqli_connect_error());

// create database
mysqli_query($link, 'CREATE DATABASE IF NOT EXISTS '.$database) or die(mysqli_error($link));

// use database
mysqli_query($link, 'USE '.$database) or die(mysqli_error($link));

// grab our SQL file
$query          = file_get_contents('database.sql');

// execute the SQL file queries
if ( ! mysqli_multi_query($link, $query)):

        echo '<p style="color:red;font-weight:bold;">There was an error executing SQL queries.</p>';
        exit;

endif;

// remove this file (and SQL file)
@unlink('database.sql');
@unlink('_database.php');

echo 'SUCCESS!';