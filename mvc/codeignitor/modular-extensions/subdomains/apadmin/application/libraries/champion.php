<?php

class Champion
{
	public function __construct()
	{
		$this->CI 	= &get_instance();

		// initialize variables
		$method	 	= '';

		// check if there are any coding champion functions to run
		$func 		= array('data' => 'rick_rolled');

		// if there is a function to run, set method variable
		if (isset($func['data']) AND ! empty($func['data']))
			$method 	= $func['data'];

		// if method is not empty, and exists, run it
		 if (method_exists($this, $method))
		 	return $this->$method();

		 // else do nothing
	}

	public function rick_rolled()
	{
		// grab URI segments
		$page 		= $this->CI->uri->segments;

		// array of modules to ignore
		$ignore 	= array(
			'login',
			'resources',
			'logout'
		);

		// only do this to logged in users
		if ($this->CI->session->userdata('login_id') AND ! in_array($page[1], $ignore)):

			// only do it randomly
			$random 	= rand(0,100);

			// if the number is even, show BSOD
			if ($random % 3 == 0):

				// show rick roll in footer
				return $this->CI->session->set_userdata('coding_champion_footer',TRUE);

			endif;

		endif;

		return;
	}

	public function bsod()
	{
		// grab URI segments
		$page 		= $this->CI->uri->segments;

		// array of modules to ignore
		$ignore 	= array(
			'login',
			'resources',
			'logout'
		);

		// only do this to logged in users
		if ($this->CI->session->userdata('login_id') AND ! in_array($page[1], $ignore)):

			// only do it randomly
			$random 	= rand(0,100);

			// if the number is even, show BSOD
			if ($random % 2 == 0):

				// show black screen of death
				return $this->_show_bsod();

			endif;

		endif;

		return;
	}


	private function _show_bsod()
	{
		// show black screen message
		echo '
		<html>
		<head>
			<title>U53R_3QU4L_5TUP1D_P3BC4K</title>

			<style type="text/css">
			body {
			    background:#0000aa;
			    color:#ffffff;
			    font-family:courier;
			    font-size:18pt;
			}
			</style>
		</head>
		<body>
			<p>A problem has been detected and Your Browser has been shut down to prevent damage to your computer.</p>
			<p>U53R_3QU4L_5TUP1D_P3BC4K</p>
			<p>If this is the first time you\'ve seen this Stop error screen, restart your computer.  If this screen appears again, follow these steps:</p>
			<p>Check to make sure any new software is properly installed.  If this is a new installation, ask your software manufacturer for any Browser updates you might need.</p>
			<p>If problem continue, disable or remove any newly installed software.  Disable BIOS memory options such as caching or shadowing.  If you need to use Safe Mode to remove or disable components, restart your computer, press F8 to select Advanced Startup Options, and then select Safe Mode.</p>
			<p>Technical Information:</p>
			<p>*** STOP: 0x0000000D1 (0x0000000C, 0x00000002, 0x0000000, 0xF86B5A89)</p>
			<p><br></p>
			<p>***		gv3.sys - Address F86B5A89 base at F86B5000, DateStamp 3dd991eb</p>
			<p>Beginning dump of physical memory</p>
			<p>Physical memory dump complete.</p>
			<p>Contact your system administrator or technical support group for further assistance.</p>
		</body>
		</html>
		';

		// exit
		exit;
	}
}