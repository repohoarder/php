<?php 
class Test extends CI_Controller
{
	function index()
	{
		echo APPPATH.'<br>'.CUSTOM_PATH;//'test';//$this->api->error_code($this, __DIR__,__LINE__);
	}
}
?>