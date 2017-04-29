<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * RESOURCES Controller
 * This file allows you to access resources from within your modules directory or themes folders 
 */

class resources extends CI_Controller {

    function __construct() {
	
        parent::__construct();
		
        //---get working directory and map it to your module
		$segments = $this->uri->segments;
		
        array_shift($segments);

        $file = CUSTOM_PATH . implode('/', $segments);

		if ($segments[0] != 'modules'):

			$file = CUSTOM_PATH.'themes/' . implode('/', $segments);

            if ( ! is_file($file)):

                $file = APPPATH.'themes/' . implode('/', $segments);

            endif;
		
		elseif ( ! is_file($file)):

			$file = str_replace(CUSTOM_PATH, APPPATH, $file);
		
		endif;
		
        //----get path parts form extension
        $path_parts = pathinfo( $file);
        //---set the type for the headers
        $file_type = strtolower($path_parts['extension']);

        if (is_file($file)) {

            $header = FALSE;

            switch ($file_type) :

                case 'css':
                    $header = 'text/css';
                    break;

                case 'js':
                    $header = 'text/javascript';
                    break;
                
                case 'json':
                    $header = 'application/json';
                    break;
                
                case 'xml':
                    $header = 'text/xml';
                    break;
                
                case 'pdf':
                    $header = 'application/pdf';
                    break;

                case 'flv':
                    $header = 'video/flv';
                    break;

                case 'jpg':
                    $header = 'image/jpeg';
                    break;

                case 'jpeg':
                    $header = 'image/jpeg';
                    break;
                
                case 'png':
                    $header = 'image/png';
                    break;

                case 'gif':
                    $header = 'image/gif';
                    break;

                case 'flv':
                    $header = 'video/flv';
                    break;

                case 'swf':
                    $header = 'application/x-shockwave-flash';        
                    break;

                default:
                    break;

            endswitch;

            if ($header):

                $contents = @file_get_contents($file);

                if ($contents):

                    header('Content-type: '   . $header);
                    header('Content-Length: ' . @filesize($file));

                    echo $contents;
                    exit();

                endif;

            endif;
        
		}

        // show_404();
        header('HTTP/1.0 404 Not Found');
        echo 'Not found';
        exit;
    }





}