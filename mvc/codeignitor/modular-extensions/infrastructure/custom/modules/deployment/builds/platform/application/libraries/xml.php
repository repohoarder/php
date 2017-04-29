<?php 

class Xml
{
	public function to_array($xml)
	{
		return json_decode(json_encode((array) simplexml_load_string($xml)), 1);
	}
	
	public function array_to_xml($arr,$xml){
	    foreach ($arr as $k => $v) {
	    	// if $k is numeric, then return 'item' instead (numeric nodes will throw errors)
	    	$k = (is_numeric($k)) ? 'item' : $k;
	    	
	    	// if $v is an object, convert it to an array
	    	$v 	= is_object($v)? (array)$v: $v;

	    	// either re-run the function or add the child node
	        is_array($v)? $this->array_to_xml($v, $xml->addChild($k)): $xml->addChild($k, $v);
	    }
	    return $xml;
	}
}

