<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// A little rough and ready
// @coldclimate - 17th Oct 2011


/*
 * Example usage
 * 
 * public function test(){
		$this->load->library( 'postmark_spam' );
		var_dump($this->postmark_spam->filter("example email text","long"));
	}
 *
 *
 */
class Spam {

    function Postmark_spam()
    {
        log_message('debug', 'Postmark Spam Class Initialized');

    }


    function filter($email, $options)
	{
		if (empty($email) || empty($options)){
			return false;
		}

        if (!function_exists('curl_init'))
        {

            if(function_exists('log_message'))
            {
                log_message('error', 'Postmark Spam Class - curl not enabled');
            }

            return false;

        }
		$headers = array(
			'Accept: application/json',
			'Content-Type: application/json'
		);

		$encoded_data = json_encode(array('email'=>$email, 'options'=>$options));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://spamcheck.postmarkapp.com/filter');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		if (curl_error($ch) != '') {
			log_message('ERROR','Postmark Spam error with: '.curl_error($ch));
			return false;
		}

		return json_decode($return);
	}


}
