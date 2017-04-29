<?php

class Automation extends MX_Controller
{
	public function order_pay_step()
	{
		$resp = $this->platform->post('ubersmith/automation/order_pay_step',array());
		var_dump($resp);
	}
}