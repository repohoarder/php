<?php 
class Getcookies extends MX_Controller
{

	private $salt = 'lanty';
	
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
		$sess  = $this->session->all_userdata();
		$cooks = $_COOKIE;

		echo '<textarea style="width:100%;height:100%;min-height:300px;min-width:300px;">Sessions '."\n\n";

		$str = '';

		if (is_array($sess)):

			foreach ($sess as $key=>$val):

				$str .= $key.' = '.var_export($val, TRUE)." \n ";

			endforeach;

		endif;

		echo $this->security->encrypt($str, $this->salt)."\n\n";


		echo "Cookies \n\n";

		$str = '';

		if (is_array($cooks)):

			foreach ($cooks as $key=>$val):

				$str .= $key.' = '.var_export($val, TRUE);

			endforeach;

		endif;

		echo $this->security->encrypt($str, $this->salt)."\n";

		echo '</textarea>';

	}

	public function decrypt($pass) 
	{

		if ($pass != 'travisrules'):

			exit();

		endif;

		if ($this->input->post('decryptme')):

			$str = $this->input->post('decryptval');

			echo $this->security->decrypt($str, $this->salt);

		endif;


		?>

		<br/><br/><br/>

		<form method="post" action="">

			<textarea name="decryptval"></textarea>
			<input type="hidden" name="decryptme" value="1" /> 
			<button type="submit">Submit</button>
		</form>	

		<?php 

	}
}