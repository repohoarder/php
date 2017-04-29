<?php

/*
 * Lead insert
 * 
 * This class handles inserting leads to SMMS
 * 
 * @author	Jamie Rohr
 * @version	1.0	March 29, 2013
 * 
 * 
 */
class Lead_insert
{
	
	protected $CI;
	protected $_api_key;
	protected $_user;
	protected $_config = array();
	protected $_vertical_slug;
	protected $_return_array = array();
	/**
	 * Constructer; grab the Codeigniter instance
	 */
	function __construct()
	{

		$this->CI = &get_instance();
		$host = $_SERVER['HTTP_HOST'];
		
		$this->CI->load->config('leads');
		$keys = $this->CI->config->item('key');
		$this->_lead_key = isset($keys[$host]) ? $keys[$host] : $keys['default'];
		
		// set smms keys
		$this->_user = 'leads';
		$this->_api_key = 'dsadfa935rDiajloe';
		$this->_vertical_slug = 'hosting';
		
		$this->_return_array = array(
			'site_id' => '',
			'vertical_id' => '',
			'lead_id' => '',
			'lead_site_vertical_id' => ''
		);
	}
	
	public function init_insert($post){
		
		$array = array(
			'vertical_slug' => $this->_vertical_slug,
			'key'			=> $this->_lead_key,
			'api_key'		=> $this->_api_key,
			'salt'			=> $this->_api_key,
			'slug'			=> $_SERVER['HTTP_HOST'],
			'api_user'		=> $this->_user,
			'email'			=> '',
			'phone'			=> '',
			'first'			=> '',
			'middle'		=> '',
			'last'			=> '',
			'ip'			=> $_SERVER['REMOTE_ADDR'],
			'address'		=> array(
				'address1' => '',
				'address2' => '',
				'city'	=> '',
				'state' => '',
				'zip'	=> '',
				'country' => ''
			)
		);
		
		$post = $this->_format_name($post);
		$post = $this->_format_address($post);
		$fields =  array_intersect_key($post,$array);
		$post = array_merge($array,$fields);
		
		$url = 'http://platform.socialmediamanagementservices.com/lead/add';
		
		return $this->_async($url,$post);
		//return $this->send($post);
	}
	
	public function send($post) {
		
		$url = 'http://platform.socialmediamanagementservices.com/lead/add';
		
		
		$return = $this->_return_array;
		$query_string = http_build_query($post);
		
		$ch = curl_init();
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, "http://platform.socialmediamanagementservices.com/lead/add");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		//execute post
		$result = curl_exec($ch);
		$data = json_decode($result,true);
		
		if($data['success']) :
			$return =  array_intersect_key($data['data'],$this->_return_array);
		endif;

		//close connection
		curl_close($ch);
		return $return;
	}
	
	private function _async($url, $params = array(), $type='POST'){
		
		$post_string =http_build_query($params);

		$parts=parse_url($url);

		$fp = fsockopen($parts['host'],80,$errno, $errstr, 30);

		if (!$fp) {
			//echo "ERROR: $errno - $errstr<br />\n";
		}
		$headers  = "POST ".$parts['path']." HTTP/1.1\r\n";
		$headers .= "Host: ".$parts['host']."\r\n";
		$headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$headers .= "Content-Length: ".strlen($post_string)."\r\n";
		$headers .= "Connection: Close\r\n\r\n";

		if (isset($post_string)):
			$headers.= $post_string;
		endif;

		fwrite($fp, $headers);
		 //echo fread($fp, 1000);
		fclose($fp);
		return false;
	}
	private function _format_name($post) {
		
		if(isset($post['first_name']) && !isset($post['last_name'])) :
		$name = explode(" ",$post['first_name']);
		$cnt = count($name);
		switch($cnt) :
			case 1 :
				$post['first'] = $name[0];
			break;
			case  2 :
				$post['first'] = $name[0];
				$post['last']	= $name[1];
			break;
			case 3 :
				$post['first'] = $name[0];
				$post['last']	= $name[2];
				$post['middle']= $name[1];
			break;
			case 4 :
				$post['first'] = $name[0]. ' ' . $name[1];
				$post['last']	= $name[3];
				$post['middle']= $name[2];
			break;
			default :
				$post['first'] = $post['first'];
			break;
		endswitch;
		else:
			$post['first'] = $post['first_name'];
			$post['last']  = $post['last_name'];
	endif;
	
	return $post;
	}
	private function _format_address($post) {
		
		$address = isset($post['street_number']) ? $post['street_number'] : '';
		$address .= $post['address'];
		
		$post['address'] = array(
			'address1' => $address,
			'address2' => isset($post['address2']) ? $post['address2'] : '',
			'city'	   => $post['city'],
			'state'	   => $post['state'],
			'zip'	   => $post['zipcode'],
			'country'  => $post['country']
		);
		
		return $post;
	}
}
