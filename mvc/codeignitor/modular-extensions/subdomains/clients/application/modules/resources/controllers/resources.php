<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * RESOURCES Controller
 * This file allows you to  access resources from within your modules directory
 * 
 * @author Borda Juan Ignacio
 * 
 * @version  1.0 (2012-05-27)
 * 
 */

class resources extends CI_Controller {

    function __construct() {
	
        parent::__construct();
		
        //---get working directory and map it to your module
		$segments = $this->uri->segments;
		$file = getcwd() . '/application/modules/' . implode('/', $segments);
		
		if (array_slice($segments,1,1) !== 'modules'):
			
			array_shift($segments); // remove /resources/ from segments
			$file = getcwd() . '/application/themes/' . implode('/', $segments);
			
		endif;
		
		
        //----get path parts form extension
        $path_parts = pathinfo( $file);
        //---set the type for the headers
        $file_type=  strtolower($path_parts['extension']);
        
        if (is_file($file)) {
            //----write propper headers
            switch ($file_type) {
                case 'css':
                    header('Content-type: text/css');
                    break;

                case 'js':
                    header('Content-type: text/javascript');
                    break;
                
                case 'json':
                    header('Content-type: application/json');
                    break;
                
                case 'xml':
                   header('Content-type: text/xml');
                    break;
                
                case 'pdf':
                  header('Content-type: application/pdf');
                    break;
                
                case 'jpg' || 'jpeg' || 'png' || 'gif':				
                    header('Content-type: image/'.$file_type);
                    break;
            }
 
			echo file_get_contents($file);
        
		} else {
            show_404();
        }
        exit;
    }

}