<?php
/***************************************************************************
 *                               show_ad.php
 *                            -------------------
 *   begin                : Thursday, March 24, 2011
 *   author               : Jeff Spicher
 *   copyright            : (C) 2011 Upright Publishing
 *   email                : jeff@brainhost.com
 *
 ***************************************************************************/

//header('Content-type: application/x-javascript');

$size = strip_tags($size);
$sizes_array = array('300x250', '120x600', '160x600', '468x60', '728x90', '160x600');

if(!in_array($size, $sizes_array)){
	$size = '300x250';
}


/*
//adversal ads
$ads['300x250'][] = '<SCRIPT SRC="http://go.adversal.com/ttj?id=1272027&size=300x250&promo_sizes=250x250,200x200,180x150&promo_alignment=center" TYPE="text/javascript"></SCRIPT>';

$ads['728x90'][] = '<SCRIPT SRC="http://go.adversal.com/ttj?id=1272027&size=728x90&promo_sizes=468x60,320x50,300x50,216x36&promo_alignment=center" TYPE="text/javascript"></SCRIPT>';

$ads['468x60'][] = '<SCRIPT SRC="http://go.adversal.com/ttj?id=1272027&size=468x60&promo_sizes=320x50,300x50,216x36&promo_alignment=center" TYPE="text/javascript"></SCRIPT>';

$ads['160x600'][] = '<SCRIPT SRC="http://go.adversal.com/ttj?id=1272027&size=160x600&promo_sizes=120x600&promo_alignment=center" TYPE="text/javascript"></SCRIPT>';

$ads['120x600'][] = '<SCRIPT SRC="http://go.adversal.com/ttj?id=1272027&size=160x600&promo_sizes=120x600&promo_alignment=center" TYPE="text/javascript"></SCRIPT>';


//cpx interactive ads
$ads['300x250'][] = '<!-- BEGIN JS TAG - [brainhost.com] - Default < - DO NOT MODIFY --><SCRIPT SRC="http://ads.cpxinteractive.com/ttj?id=1273896&size=300x250&referrer=brainhost.com" TYPE="text/javascript"></SCRIPT><!-- END TAG -->';

$ads['728x90'][] = '<!-- BEGIN JS TAG - [brainhost.com] - Default < - DO NOT MODIFY --><SCRIPT SRC="http://ads.cpxinteractive.com/ttj?id=1273896&size=728x90&referrer=brainhost.com" TYPE="text/javascript"></SCRIPT><!-- END TAG -->';

$ads['160x600'][] = '<!-- BEGIN JS TAG - [brainhost.com] - Default < - DO NOT MODIFY --><SCRIPT SRC="http://ads.cpxinteractive.com/ttj?id=1273896&size=160x600&referrer=brainhost.com" TYPE="text/javascript"></SCRIPT><!-- END TAG -->';

$ads['120x600'][] = '<!-- BEGIN JS TAG - [brainhost.com] - Default < - DO NOT MODIFY --><SCRIPT SRC="http://ads.cpxinteractive.com/ttj?id=1273896&size=160x600&referrer=brainhost.com" TYPE="text/javascript"></SCRIPT><!-- END TAG -->';
*/


$ads['468x60'][] = '<SCRIPT SRC="http://go.adversal.com/ttj?id=1272027&size=468x60&promo_sizes=320x50,300x50,216x36&promo_alignment=center" TYPE="text/javascript"></SCRIPT>';
// $ads['160x600'][] = '<SCRIPT SRC="http://go.adversal.com/ttj?id=1272027&size=160x600&promo_sizes=120x600&promo_alignment=center" TYPE="text/javascript"></SCRIPT>';
// $ads['120x600'][] = '<SCRIPT SRC="http://go.adversal.com/ttj?id=1272027&size=160x600&promo_sizes=120x600&promo_alignment=center" TYPE="text/javascript"></SCRIPT>';

//cpx interactive ads
$ads['300x250'][] = '<!-- BEGIN JS TAG - [brainhost.com] - Default < - DO NOT MODIFY --><SCRIPT SRC="http://ads.cpxinteractive.com/ttj?id=1273896&size=300x250&referrer=brainhost.com" TYPE="text/javascript"></SCRIPT><!-- END TAG -->';
$ads['728x90'][] = '<!-- BEGIN JS TAG - [brainhost.com] - Default < - DO NOT MODIFY --><SCRIPT SRC="http://ads.cpxinteractive.com/ttj?id=1273896&size=728x90&referrer=brainhost.com" TYPE="text/javascript"></SCRIPT><!-- END TAG -->';
$ads['160x600'][] = '<!-- BEGIN JS TAG - [brainhost.com] - Default < - DO NOT MODIFY --><SCRIPT SRC="http://ads.cpxinteractive.com/ttj?id=1273896&size=160x600&referrer=brainhost.com" TYPE="text/javascript"></SCRIPT><!-- END TAG -->';
$ads['120x600'][] = '<!-- BEGIN JS TAG - [brainhost.com] - Default < - DO NOT MODIFY --><SCRIPT SRC="http://ads.cpxinteractive.com/ttj?id=1273896&size=160x600&referrer=brainhost.com" TYPE="text/javascript"></SCRIPT><!-- END TAG -->';

echo $ads[$size][array_rand($ads[$size], 1)];

