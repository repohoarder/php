<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Security extends CI_Security {
	
	function _get_ivs() {
	
		return mcrypt_get_iv_size( MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB );
		
	}
	
	function _get_iv() {
		
		return mcrypt_create_iv( $this->_get_ivs(), MCRYPT_DEV_URANDOM );
		
	}
	
	function encrypt($text, $salt) {
		
		$data = mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $salt, $text, MCRYPT_MODE_ECB, $this->_get_iv() );
        return base64_encode( $data );
		
	}
	
	function decrypt($text, $salt) {
		
		$text = base64_decode( $text );
        return trim(mcrypt_decrypt( MCRYPT_RIJNDAEL_128, $salt, $text, MCRYPT_MODE_ECB, $this->_get_iv() ));
		
	}

}