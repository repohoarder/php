<?php

class One_clicks extends MX_Controller {

	private 
		$_response = array(
			'success' => 0,
			'error'   => array(),
			'data'    => array()
		);


	function index()
	{

		header('Content-type: application/json');
		$response = json_encode($this->_response);

		if ($this->input->get('callback')):

			$response = $this->input->get('callback').'('.$response.')';

		endif;

		echo $response;

	}

	function get_banner_code()
	{

		$required = array(
			'type',
			'current_url'
		);

		$provided = $this->input->get_post(NULL, TRUE);
		$provided = (is_array($provided) ? $provided : array());
		
		$params   = array_filter(array_intersect_key(
			$provided,
			array_combine($required, array_fill(0,count($required),NULL))
		));

		if (count($params) < count($required)):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Missing required type or current_url'),
				'data'    => array()
			);

			return $this->index();

		endif;
		
		$type_func = '_get_banner_'.$params['type'];

		if (is_callable(array($this, $type_func))):

			$extra  = array_diff_key($provided, $params);
			$extra  = (is_array($extra) ? $extra : array());

			$params['ip_address'] = $_SERVER['REMOTE_ADDR'];

			$params = array_merge($params, $extra);

			return $this->$type_func($params);

		endif;

		$this->_response = array(
			'success' => 0,
			'error'   => array('Unable to load type function'),
			'data'    => array()
		);

		return $this->index();

	}

	function _get_banner_cpanel_lightning($params)
	{

		$width  = '221';
		$height = '277';

		unset($params['callback']);
		unset($params['_']);

		$this->_response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array(
				'display_ad' => TRUE,
				'width'      => $width,
				'height'     => $height,
				'ad'         => array(
					'
						<style type="text/css">
							.vertical-offset {position:absolute !important;}
						</style>
						<a class="modal_iframe_link" href="http://www.desktoplightning.com/brainhost.i_cpanel1_1" style="display:block;width:'.$width.'px;height:'.$height.'px;margin:0 0 15px;">
							<img src="'.site_url('/resources/modules/one_clicks/assets/cpanel/img/desktop_lightning.jpg').'" alt="" style="display:block;width:'.$width.'px;height:'.$height.'px" />
						</a>
					'
				),
				'params'     => $params
			)
		);

		return $this->index();
	}

	function _get_banner_wordpress_dashboard($params)
	{

		if ( ! isset($params['username']) || ! $params['username']):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Missing cpanel username'),
				'data'    => array()
			);

			return $this->index();

		endif;


		if ($params['username'] == 'admin'):
			$params['username'] = 'mjbravmg';
		endif;


		$resp = $this->platform->post(
			'/ubersmith/client/get/',
			array(
				'name'     => 'username',
				'username' => trim($params['username'])
			)
		);

		if ( ! $resp['success']):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Unable to get client info'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$source    = 'wpbase2';
		$client_id = $resp['data']['clientid'];

		if (intval($client_id) < 1000):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Invalid client ID returned'),
				'data'    => array()
			);

			return $this->index();

		endif;
		
		$winner    = $this->_get_banner_to_show($client_id);
		
		$resp      = $this->platform->post(
			'sales_funnel/oneclicks/get_which_banner',
			array(
				'client_id' => $client_id,
				'slug'      => $winner,
				'source'    => $source
			)
		);

		$version   = (rand(0,1) ? 'green' : 'yellow');

		if ($resp['success']):
			$version = $resp['data']['version'];
		endif;

		
		// track banner view
		$resp = $this->platform->post(
			'/sales_funnel/oneclicks/track_link_view',
			array(
				'client_id' => $client_id,
				'source'    => $source,
				'version'   => $version,
				'slug'      => $winner
			)
		);
		
		$link      = site_url('/bonus/offers/init/'.$winner.'/1/1/'.$client_id).'?clicksrc='.$source;
		$img       = site_url('/resources/modules/one_clicks/assets/wpadmin/img/'.$version.'/'.$winner.'.jpg');

		$width     = 650;
		$height    = 120;

		unset($params['callback']);
		unset($params['_']);

		// return banner code
		$this->_response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array(
				'display_ad' => TRUE,
				'width'      => $width,
				'height'     => $height,
				'ad'         => array(
					'
					<a href="'.$link.'" style="width:'.$width.'px; height:'.$height.'px;display:block;">
						<img src="'.$img.'" style="width:'.$width.'px; height:'.$height.'px;display:block;" alt=""/>
					</a>
					'
				),
				'params'     => $params
			)
		);


		return $this->index();
	}

	function _get_banner_cpanel_modal($params)
	{

		if ( ! isset($params['username']) || ! $params['username']):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Missing cpanel username'),
				'data'    => array()
			);

			return $this->index();

		endif;

		if ($this->input->cookie('desktop_lightning')):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Already shown desktop lightning'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$cookie = array(
		    'name'   => 'desktop_lightning',
		    'value'  => '1',
		    'expire' => '43200',
		);

		$this->input->set_cookie($cookie); 

		$width  = '960';
		$height = '600';


		unset($params['callback']);
		unset($params['_']);

		$this->_response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array(
				'display_ad' => TRUE,
				'width'      => $width,
				'height'     => $height,
				'ad'         => array(
					'
					<style type="text/css">
						.vertical-offset {position:absolute !important;}
					</style>
					<iframe src="http://brainhost.com/ads/lightning.php" width="'.$width.'" height="'.$height.'" frameborder="0"></iframe>
					'
				),
				'params'     => $params
			)
		);

		return $this->index();
	}

	function _get_uber_plan_id($slug)
	{

		$resp = $this->platform->post(
			'ubersmith/package/get_plan_id',
			array(
				'slug' => $slug
			)
		);

		if ( ! $resp['success']):

			return FALSE;

		endif;

		return $resp['data'];

	}

	function _client_has_plan($client_id, $plan_id)
	{

		$resp = $this->platform->post(
			'ubersmith/client/has_plan',
			array(
				'client_id' => $client_id,
				'plan_id'   => $plan_id
			)
		);

		return ($resp['success'] && $resp['data']['has_plan']);

	}

	function _get_banner_to_show($client_id)
	{

		$repeat_ads = array( // ads that dont care if user already has service
			'traffic'
		);

		$single_ads = array( // ads that can't be re-purchased
			// 'cloudhosting',
			// 'search_engine_submission', // doesn't have price in funnel 1
			// 'spam'                      // no page			
		);

		foreach ($single_ads as $key => $single_ad):

			$plan_id = $this->_get_uber_plan_id($single_ad);

			if ( ! $plan_id):

				unset($single_ads[$key]);
				continue;

			endif;

			$has_plan = $this->_client_has_plan($client_id, $plan_id);

			if ($has_plan):

				unset($single_ads[$key]);
				continue;

			endif;

		endforeach;

		$survivors = array_merge($repeat_ads, $single_ads);
		shuffle($survivors);
		
		$winner    = array_shift($survivors);

		return $winner;

	}

	function _get_banner_cpanel_ad($params)
	{

		if ( ! isset($params['username']) || ! $params['username']):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Missing cpanel username'),
				'data'    => array()
			);

			return $this->index();

		endif;

		// get client_id from cpanel username
		$resp = $this->platform->post(
			'/ubersmith/client/get/',
			array(
				'name'     => 'username',
				'username' => trim($params['username'])
			)
		);

		if ( ! $resp['success']):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Unable to get client info'),
				'data'    => array()
			);

			return $this->index();

		endif;

		$source     = 'cpanel_ad';
		$client_id  = $resp['data']['clientid'];
		

		$winner = $this->_get_banner_to_show($client_id);


		$resp = $this->platform->post(
			'sales_funnel/oneclicks/get_which_banner',
			array(
				'client_id' => $client_id,
				'slug'      => $winner,
				'source'    => $source
			)
		);

		$version   = (rand(0,1) ? 'green' : 'yellow');

		if ($resp['success']):
			$version = $resp['data']['version'];
		endif;

		
		// track banner view
		$resp = $this->platform->post(
			'/sales_funnel/oneclicks/track_link_view',
			array(
				'client_id' => $client_id,
				'source'    => $source,
				'version'   => $version,
				'slug'      => $winner
			)
		);
		

		$link      = site_url('/bonus/offers/init/'.$winner.'/1/1/'.$client_id).'?clicksrc='.$source;
		$img       = site_url('/resources/modules/one_clicks/assets/cpanel/img/'.$version.'/'.$winner.'.jpg');

		$width     = 219;
		$height    = 108;

		unset($params['callback']);
		unset($params['_']);

		// return banner code
		$this->_response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array(
				'display_ad' => TRUE,
				'width'      => $width,
				'height'     => $height,
				'ad'         => array(
					'
					<a href="'.$link.'" style="width:'.$width.'px; height:'.$height.'px;display:block;border:1px solid #000;padding-bottom:10px">
						<img src="'.$img.'" style="width:'.$width.'px; height:'.$height.'px;display:block" alt=""/>
					</a>
					'
				),
				'params'     => $params
			)
		);


		return $this->index();
	}

	function _get_banner_uber($params)
	{

		if ( ! isset($params['client_id']) || ! $params['client_id']):

			$this->_response = array(
				'success' => 0,
				'error'   => array('Missing client ID'),
				'data'    => array()
			);

			return $this->index();

		endif;


		$source    = 'uber2';
		$client_id = $params['client_id'];
		
		$winner    = $this->_get_banner_to_show($client_id);
	
		// track banner view
		$resp = $this->platform->post(
			'/sales_funnel/oneclicks/track_link_view',
			array(
				'client_id' => $client_id,
				'source'    => $source,
				'slug'      => $winner
			)
		);
		

		$link      = site_url('/bonus/offers/init/'.$winner.'/1/1/'.$client_id).'?clicksrc='.$source;
		$img       = site_url('/resources/modules/one_clicks/assets/uber/img/upsells/'.$winner.'.png');
		$bg        = site_url('/resources/modules/one_clicks/assets/uber/img/bar.png');

		$width     = 844;
		$height    = 45;

		unset($params['callback']);
		unset($params['_']);

		// return banner code
		$this->_response = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array(
				'display_ad' => TRUE,
				'width'      => $width,
				'height'     => $height,
				'ad'         => array(
					'
					<div style="height:'.$height.'px;background:#768922 url('.$bg.') left top repeat-x;border-bottom: 2px solid #116F8A;-webkit-box-shadow: inset 0px -4px 3px 0px 333333;box-shadow: inset 0px -4px 3px 0px 333333;">
						<a href="'.$link.'" style="width:'.$width.'px; height:'.$height.'px;display:block;margin:0 auto;">
							<img src="'.$img.'" style="width:'.$width.'px; height:'.$height.'px;display:block" alt=""/>
						</a>
					</div>
					'
				),
				'params'     => $params
			)
		);


		return $this->index();
	}	

}