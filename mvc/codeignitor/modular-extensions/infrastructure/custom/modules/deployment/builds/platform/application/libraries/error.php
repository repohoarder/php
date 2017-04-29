<?php

/*
 * Error
 * 
 * This class formats errors into a standard format
 * 
 * @author	John Thompson	<thompson2091 @ gmail.com>
 * @version	1.0	August 1,2012
 * 
 * @method string	code(object $obj, string $dir, string $line)
 * 
 */
class Error
{

	/*
	 * API Error Code
	 * 
	 * This method creates our standard API Error Code
	 * 
	 * @author	John Thompson	<thompson2091 @ gmail.com>
	 * 
	 * @access	public
	 * 
	 * @example	code($this,__DIR__,__LINE__)
	 * 
	 * @param	object	$obj	This is the object of the file calling this function
	 * @param	string	$dir	The directory of the file we are calling this function from
	 * @param	int		$line	This is the line number we are calling this function from
	 * 
	 * @return	json
	 */	
	public function code($obj,$dir,$line)
	{		
		// get this objects module
		$module	= strtoupper(substr($obj->router->fetch_module(),0,3));
		
		// if module is empty, then we are in the core application
		$module	= (empty($module) OR $module == '')
			? "COR"
			: $module;
		
		// get this objects directory (library, controller, etc..)
		$dir	= $this->_get_cwd($dir);
		
		// get this objects file name
		$file	= strtoupper(substr($obj->router->fetch_class(),0,3));
		
		// return the formatted code (according to our API standards)
		return ' ['.$module.'_'.$dir.'_'.$file.'_'.$line.'] ';
	}
	
	/*
	 * API Get Current Working Directory
	 * 
	 * This method formats a directory path into our standard API directory CODE (3 characters all upper)
	 * 
	 * @author	John Thompson	<thompson2091 @ gmail.com>
	 * 
	 * @access	private
	 * 
	 * @example	_get_cwd('/modules/ubersmith/contollers/client.php')
	 * 
	 * @param	string	$path	This is the directory path we are going ot attempt to find the CODE for
	 * 
	 * @return	json
	 */	
	private function _get_cwd($path)
	{
		// initialize variables
		$directories	= array(	// directories we are looking for
			'controllers'	=> 'CON',
			'libraries'		=> 'LIB',
			'models'		=> 'MOD',
			'views'			=> 'VIE'
		);
		
		// iterate through directories we are looking for
		foreach($directories AS $dir => $code):
			if(strpos($path,$dir) !== FALSE):
				return $code;
			endif;
		endforeach;
		
		return "UNKNOWN";
	}
	
}

?>