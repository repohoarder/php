<?php

# http://stackoverflow.com/questions/4102777/php-random-shuffle-array-maintaining-key-value#comment4416193_4102803
function shuffle_assoc_array($array) 
{ 

	if ( ! is_array($array)):

		return FALSE; 

	endif; 

	$keys = array_keys($array); 
	shuffle($keys); 

	$random = array(); 

	foreach ($keys as $key):

		$random[$key] = $array[$key]; 

	endforeach;

	return $random; 
} 


# http://stackoverflow.com/a/9593794
function intval_array($array)
{
	return array_filter(array_map('intval',$array));
}


# http://stackoverflow.com/a/4254008
function is_assoc_array($array)
{
	if ( ! is_array($array)):

		return FALSE;

	endif;

	return (bool)count(array_filter(array_keys($array), 'is_string'));

}