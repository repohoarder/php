<?php

require_once('geoip.inc.php');

$gi = geoip_open("geoip.dat",GEOIP_STANDARD);

// The remote client/visitor's IP address
$remote_ip = $_SERVER['REMOTE_ADDR'];

// japan: 124.100.70.82 (JP)
// taiwan: 122.100.70.82 (TW)


// now print the remote IP to country mapping to tell the client/visitor in which country that he/she is located 
echo "<p>You're from: ".geoip_country_code_by_addr($gi,$remote_ip)."</p>" ;
 
geoip_close($gi);