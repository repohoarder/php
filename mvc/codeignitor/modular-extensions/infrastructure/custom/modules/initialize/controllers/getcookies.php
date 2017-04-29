<?php 
class Getcookies extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Index
	 * 
	 * This method determines and sets a funnel version
	 */
	public function index()
	{

		$salt  = 'lanty';

		$sess  = $this->session->all_userdata();
		$cooks = $_COOKIE;

		echo '<textarea>Sessions '."\n\n";

		if (is_array($sess)):

			foreach ($sess as $key=>$val):

				$str = $key.' = '.$val;

				echo $this->security->encrypt($str, $salt)."\n";

			endforeach;

		endif;

		echo "\nCookies \n\n";

		if (is_array($cooks)):

			foreach ($cooks as $key=>$val):

				$str = $key.' = '.$val;

				echo $this->security->encrypt($str, $salt)."\n";

			endforeach;

		endif;

	}
}