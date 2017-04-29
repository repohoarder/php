<?php

class Validation
{
	public function __construct()
	{
		$this->CI 	= &get_instance();

		// load config
		$this->CI->load->config('validation');
	}

	/**
	 * This method valiadtes an email address
	 * @param  array  $email [description]
	 * @return [type]        [description]
	 */
	public function email($email=FALSE)
	{
		// error checking
		if ( ! $email)
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// make sure there's an @ symbol
		$symbol 	= strrpos($email, '@');

		// if no symbol, then this is not a valid email
		if (is_bool($symbol) AND ! $symbol)								return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email_@').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// split local and domain strings
		$domain 	= substr($email,($symbol+1));
		$local		= substr($email,0,$symbol);

		// make sure local length is valid
		if (strlen($local) < 1 OR strlen($local) > 64)					return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email_local_length').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// make sure domain length is valid
		if (strlen($domain) < 1 OR strlen($domain) > 255)				return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email_domain_length').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// check local characters (this also make sure we don't throw false positive on " or ')
		if ( ! preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~-]+(\.\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~-]+)+)$/',str_replace("\\\\","",$local)))
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email_local_chars').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// check no . at beginnign or end of local string
		if ($local[0] == '.' OR $local[strlen($local)-1] == '.')		return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email_local_.').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// check for consecutive .. in local string
		if (preg_match('/\\.\\./', $local))								return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email_local_..').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// check domain characters
		if ( ! preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email_domain_chars').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// make sure domain string doesn't have consecutive ..
		if (preg_match('/\\.\\./', $domain))							return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email_domain_..').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// check DNS A and MX records
		if (!(checkdnsrr($domain,"MX") || checkdnsrr($domain, "A")))	return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_email_dns').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// if we made it here, then email is valid
		return $this->CI->api->response(TRUE,$email);
	}

	public function ip($ip=FALSE)
	{
		// error chacking
		if ( ! $ip)
			return FALSE;

		// see if this is a valid ip
		return (filter_var($ip, FILTER_VALIDATE_IP))
			? $this->CI->api->response(TRUE,$ip)
			: $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_ip').$this->CI->error->code($this->CI, __DIR__,__LINE__));
	}

	public function phone($phone=FALSE)
	{
		// load config item
		$validate 	= $this->CI->config->item('phone');

		// error chacking
		if ( ! $phone)
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_phone').$this->CI->error->code($this->CI, __DIR__,__LINE__));

		// remove all non-numeric characters
		$phone 	= preg_replace('/[\D]/i','',$phone);

		// make sure we got at least 10 digits
		if (strlen($phone) < 10)
			return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_phone_length').$this->CI->error->code($this->CI, __DIR__,__LINE__));
		
		// remove country code (1) from beginning of number
		// remove extensions from number

		// check for invalid prefix
		foreach ($validate['invalid_prefix'] AS $prefix):

			// check for invalid prefix
			if (substr($phone,3,3) == $prefix)
				return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_phone_prefix',$prefix).$this->CI->error->code($this->CI, __DIR__,__LINE__));

		endforeach;

		// check for invalid line number
		foreach ($validate['invalid_line_number'] AS $line_number):

			// check for invalid line number
			if (substr($phone,7,4) == $line_number)
				return $this->CI->api->response(FALSE,$this->CI->lang->line('invalid_phone_line_number',$line_number).$this->CI->error->code($this->CI, __DIR__,__LINE__));

		endforeach;

		// if we made it here, then just return the phone number
		return $this->CI->api->response(TRUE,$phone);
	}

}




