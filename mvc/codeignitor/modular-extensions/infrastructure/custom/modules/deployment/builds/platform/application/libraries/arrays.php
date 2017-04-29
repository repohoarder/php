<?php

/**
 * Array
 * 
 * This class handles array manipulation
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @method boolean	in_array_r	// This method checks to see if an item is in a mutlidimensional array
 * 
 */
class Arrays
{

    /**
     * This method check to see if an item is in a mutlidimensional array
     * @param  [type]  $needle   [description]
     * @param  [type]  $haystack [description]
     * @param  boolean $strict   [description]
     * @return [type]            [description]
     */
    public function _in_array_r($needle, $haystack, $strict = false) 
    {
        // iterate items in haystack
        foreach ($haystack as $item):
            // see if the needle is in this haystack item
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->_in_array_r($needle, $item, $strict))):
                return true;
            endif;
        endforeach; // end iterating through haystack items

        return false;
    }

}