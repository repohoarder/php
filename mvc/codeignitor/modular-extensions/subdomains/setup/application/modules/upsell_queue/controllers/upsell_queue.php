<?php

class Upsell_queue extends MX_Controller {

	function page($type = 'all', $page_num = 1)
	{

		$this->index($type, $page_num);
	}

	function index($type = 'all', $page_num = 1)
	{

		$this->load->helper('url');

		$offset = ($page_num - 1) * 30;
		$limit  = 30;

		$params = array(
			'type'   => ($type == 'all') ? '' : $type,
			'offset' => $offset,
			'limit'  => $limit
		);

		$resp = $this->platform->post('ubersmith/fulfillment/get_packs', $params);

		$data = array(
			'errors' => $resp['error'],
			'rows'   => (isset($resp['data']['packs'])) ? $resp['data']['packs'] : array(),
			'type'   => $type,
			'page'   => $page_num
		);

		$this->load->view('queue', $data);

	}

	function process()
	{
		$resp = $this->platform->post('ubersmith/fulfillment/run', array());

		return $this->index();
	}

	function close($pack_id, $queue_id)
	{
		$params = array(
			'pack_id'  => $pack_id,
			'queue_id' => $queue_id
		);

		$resp = $this->platform->post('ubersmith/fulfillment/close_queue', $params);

		echo '<h1>Parameters</h1><br/>';
		var_dump($params);
		echo '<hr/>';
		echo '<h1>Response</h1><br/>';
		var_dump($resp);

	}

	function step($pack_id, $queue_id, $step)
	{

		$params = array(
			'pack_id'  => $pack_id,
			'queue_id' => $queue_id,
			'step'     => $step
		);

		$resp = $this->platform->post(
			'ubersmith/fulfillment/do_step', 
			$params
		);


		echo '<h1>Parameters</h1><br/>';
		var_dump($params);
		echo '<hr/>';
		echo '<h1>Response</h1><br/>';
		var_dump($resp);

	}


}