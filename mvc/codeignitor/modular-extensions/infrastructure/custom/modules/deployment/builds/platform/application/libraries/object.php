<?php

/*
 * Object
 * 
 * This class formats objects.
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	July 28,2012
 * 
 * @package Format
 * 
 * @method array	obj_to_arr(object|array $obj)
 * 
 */
class Object
{

	/*
	 * Formats an object into an array
	 * 
	 * This method will take an object and convert it into an array
	 * 
	 * @author	John Thompson	<thompson2091 @ gmail.com>
	 * 
	 * @access	public
	 * 
	 * @example	obj_to_arr($my->object)
	 * 
	 * @param	object|array	$obj	The object|array to be converted into an array 
	 * 
	 * @return	array
	 */
	public function to_array($obj)
	{
		// initialize variables
		$arr	= array();
		
		// see if value is an object
        $arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
        
        // if the value is an array OR an object, continue
        if (is_array($arrObj) OR is_object($arrObj)):
        
        	// iterate each array|object
	        foreach ($arrObj as $key => $val):
	        	
	        	// if value is still an array or an object, recursively call this function to run again
	            $val = (is_array($val) OR is_object($val)) ? $this->obj_to_arr($val) : $val;
	            
	            // value is no longer array nor object, set return array to this key => value
	            $arr[$key] = $val;
	            
	        endforeach;
	        
	    else:
	    	// value is neither array nor object, return
	    	return $arrObj;
        endif;
        
        // return the array
        return $arr;
	}
}

?>