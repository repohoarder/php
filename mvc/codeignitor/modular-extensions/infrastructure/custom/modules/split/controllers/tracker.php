<?php

class Tracker extends MX_Controller {


	protected 
		$_response = array(
			'success' => 0, 
			'error'   => array(), 
			'data'    => array()
		),
		$_output,
		$_get     = array(),
		$_post    = array(),
		$_request = array(),
		$_visitor_id,
		$_split_test_id,
		$_ip_address,
		$_user_agent,
		$_variation_id,
		$_winner;

		// redirect before tracking hit if necessary
		// if already have split test id for this variation, track hit and do not redirect

	public function __construct()
	{

		parent::__construct();

		$this->_defaults = array(
			'screen_height'   => NULL,
			'screen_width'    => NULL,
			'viewport_height' => NULL,
			'viewport_width'  => NULL,
			'url_full'        => NULL,
			'url_host'        => NULL,
			'url_path'        => NULL,
			'url_query'       => NULL,
			'referrer'        => NULL,
			'amount'          => NULL,
			'order_id'        => NULL,
			'client_id'       => NULL,
		);

		$this->_get     = $this->_get_get();
		$this->_post    = $this->input->post(NULL, TRUE);

		if ( ! is_array($this->_post)):
			$this->_post = array();
		endif;
		
		$this->_request       = array_merge($this->_defaults, $this->_get, $this->_post);
		
		$this->_visitor_id    = $this->session->userdata('visitor_id');
		$this->_split_test_id = $this->session->userdata('split_test_id');
		$this->_winner        = $this->session->userdata('winner_variant_id');
		
		$this->_ip_address    = $this->session->userdata('ip_address');
		$this->_user_agent    = $_SERVER['HTTP_USER_AGENT'];

		/*	

		$_GET = the following (and prolly more...)

		screen_height: screen.height,
        screen_width: screen.width,
        viewport_height: $(window).height(),
        viewport_width: $(window).width(),
        url_full: window.location.href,
        url_host: host,
        url_path: window.location.pathname,
        url_query: window.location.search,
        referrer: document.referrer
        amount: null,
        order_id: null,
        client_id: null,

		*/
	}

	function index()
	{

		$callback = $this->input->get('callback');

		$json     = json_encode($this->_output);
		echo $callback ? $callback.'('.$json.')' : $json;

	}

	function get_data()
	{

		$this->_output = array(
			'session' => $this->_get_sessions()
		);

		return $this->index();

	}

	function track()
	{

		$goal       = NULL;

		$current    = $this->_split_test_id;
		$winner     = $this->_winner;

		$split_test = $this->_get_split_test();

		$variant    = isset($split_test['main_variant_id']) ? $split_test['main_variant_id'] : '';

		if ( ! $split_test && $current):

			// user landed on a split test variant page earlier
			// current page is not a variant
			// check if user is on a goal

			$goal = $this->_get_goal();

			if ( ! isset($goal['split_tests_id'])):

				// not on a goal or variant, bail

				return $this->_invalid_split_test();

			endif;

			$split_test = $this->_get_split_test($goal['split_tests_id']);
			
			if ( ! $split_test):

				// unable to get split test; bail

				return $this->_invalid_split_test();

			endif;

			// currently on a goal; no need to redirect

			$split_test['redirect']          = FALSE;
			$split_test['winner_variant_id'] = $winner;
			$split_test['redirect_url']      = $split_test['variations'][$winner]['url'];

		endif;
		

		if (is_null($goal)):

			// this is not a goal... so do redirection if necessary

			if ( ! $current || $current != $split_test['split_test_id']):

				// the split test in the session does not match the split test detected by URL
				
				// reset the session so next pageview gets a new visitor
				$sess = $this->_get_sessions();
				foreach ($sess as $key => $val):
					$this->session->unset_userdata($key);
				endforeach;


				$this->session->set_userdata('split_test_id',     $split_test['split_test_id']);
				$this->session->set_userdata('winner_variant_id', $split_test['winner_variant_id']);

				if ($split_test['redirect']):

					// redirect before tracking

					return $this->_output_split_test($split_test);

				endif;

			else:

				// split test matches one in the session
				// overwrite redirect variables with ones from previous page load

				$split_test['winner_variant_id'] = $winner;
				$split_test['redirect_url']      = (isset($split_test['variations'][$winner])) ? $split_test['variations'][$winner]['url'] : '';

				if ($variant != $winner):
					
					// we're not on the chosen variant; redirect to the appropriate one

					$split_test['redirect'] = TRUE;
					
					return $this->_output_split_test($split_test);

				endif;

				// currently on the chosen variant, continue on and track hit

				$split_test['redirect'] = FALSE;

			endif;

		endif;



		if ( ! $this->_split_test_id):

			// not a goal or a variant? bail
			return $this->_invalid_split_test();

		endif;

	
		$this->_request['goal_id']       = isset($goal['id']) ? $goal['id'] : FALSE;
		$this->_request['split_test_id'] = $this->_split_test_id;
		$this->_request['variation_id']  = $this->_variation_id;


		// create visitor if one does not exist
		if ( ! $this->_visitor_id):

			$this->_request['ip_address'] = $this->_ip_address;
			$this->_request['user_agent'] = $this->_user_agent;

			$resp = $this->_create_visitor();

			if ( ! $resp['success']):

				$this->_output = $resp;
				return $this->index();

			endif;

			$this->_visitor_id = $resp['data']['visitor_id'];
			$this->session->set_userdata('visitor_id', $this->_visitor_id);

		endif;

		
		$this->_request['visitor_id'] = $this->_visitor_id;
		$this->_request['page']       = (isset($this->_request['url_full'])) ? $this->_request['url_full'] : '';


		// track hit -- either on a goal or on the chosen split test variant

		$tracked = $this->_track_hit();
		$hit_id  = (isset($tracked['success']) && $tracked['success']) ? $tracked['data']['hit_id'] : NULL;

		return $this->_output_split_test($split_test, $hit_id);

	}


	function _get_split_test($split_id = FALSE)
	{

		$params = array(
			'host' => str_replace('www.','',$this->_request['url_host']),
			'path' => trim($this->_request['url_path'],'/')
		);

		if ($split_id !== FALSE):

			$params = array(
				'split_test_id' => $split_id
			);

		endif;

		$response = $this->platform->post(
			'split_test/split_test/get',
			$params
		);

		if ( ! $response['success'] || ! $response['data']['active']):
			return FALSE;
		endif;


		$this->_split_test_id = $response['data']['id'];
		$this->_variation_id  = $response['data']['main_variation_id'];

		$return = array(
			'split_test_id'   => $response['data']['id'],
			'active'          => $response['data']['active'],
			'main_variant_id' => $this->_variation_id
		);

		$percents    = array();
		$max_percent = 0;
		$variations  = array();

		if (isset($response['data']['variations'])):

			$_variations = $response['data']['variations'];

			foreach ($_variations as $key => $variation):

				$variations[$variation['id']] = array(
					'variation_id' => $variation['id'],
					'url'          => $variation['url'],
					'percent'      => $variation['percent'],
				);

				$new_max = $max_percent + $variation['percent'];

				$percents[$variation['id']] = array(
					'min' => $max_percent + 0.01,
					'max' => $new_max
				);

				$max_percent = $new_max;

			endforeach;

		endif;

		$winner_variant = $this->_variation_id;
		$return['redirect'] = FALSE;

		$max_percent = number_format($max_percent, 2, '.', '');
		$rand = $this->_float_rand(0.00,$max_percent,2);

		foreach ($percents as $id => $range):

			if ($rand >= $range['min'] && $rand <= $range['max']):

				$winner_variant = $id;
				break;

			endif;

		endforeach;

		$return['winner_variant_id'] = $winner_variant;
		$return['redirect']          = ($winner_variant != $this->_variation_id);
		$return['redirect_url']      = $variations[$winner_variant]['url'];
		$return['variations']        = $variations;

		return $return;
	}

	function _output_split_test($split_test, $hit_id = NULL)
	{


		$this->_output = array(
			'success' => 1,
			'error'   => array(),
			'data'    => array(
				'hit_id'     => $hit_id,
				'split_test' => $split_test
			)
		);

		return $this->index();

	}

	function _invalid_split_test()
	{

		$this->_output = array(
			'success' => 0,
			'error'   => array('No split test or split test is inactive'),
			'data'    => array()
		);

		return $this->index();

	}

	function _get_goal()
	{

		$host = trim(str_replace('www.','',$this->_request['url_host']),'/');
		$path = trim($this->_request['url_path'],'/');

		$response = $this->platform->post(
			'split_test/conversion/get_goal',
			array(
				'host'          => $host,
				'path'          => $path,
				'split_test_id' => $this->_split_test_id
			)
		);

		if ( ! $response['success']):

			return FALSE;

		endif;

		return $response['data'];

	}
	
	function _track_hit()
	{

		$post     = $this->_request;

		if ( ! is_array($post)):
			$post = array();
		endif;

		$post     = array_filter($post);
		
		$needed   = array('page','visitor_id');
		$template = array_combine($needed, array_fill(0, count($needed), NULL));
		
		$params   = array_filter(array_merge($template, $post));

		$missing  = array_diff_key($template, $params);

		if (count($missing)):

			$this->_response = array(
				'success' => 0,
				'error'   => array(
					'Missing or invalid fields: '.implode(', ',array_keys($missing))
				),
				'data'    => array()
			);

			return $this->_response;

		endif;

		$resp = $this->platform->post(
			'split_test/hit/insert',
			$params
		);

		$this->_response = $resp;

		return $resp;		

	}


	function _create_visitor()
	{

		$post     = $this->_request;

		if ( ! is_array($post)):
			$post = array();
		endif;
		
		$post     = array_filter($post);

		$needed   = array('ip_address','user_agent','split_test_id', 'variation_id');
		$template = array_combine($needed, array_fill(0, count($needed), NULL));

		$params   = array_merge($template, $post);

		if ( ! filter_var($params['ip_address'], FILTER_VALIDATE_IP)):

			$params['ip_address'] = NULL;

		endif;

		$params  = array_filter($params);
		$missing = array_diff_key($template, $params);

		if (count($missing)):

			$this->_response = array(
				'success' => 0,
				'error'   => array(
					'Missing or invalid fields: '.implode(', ',array_keys($missing))
				),
				'data'    => array()
			);

			return $this->_response;

		endif;

		$resp = $this->platform->post(
			'split_test/visitor/insert',
			$params
		);
		
		if($resp['success']) :
			$data = $resp['data'];
			if(isset($data['visitor_id'])) :
				$this->session->set_userdata(array('visitor_id'=>$data['visitor_id']));
			endif;
		endif;
		
		$this->_response = $resp;

		return $this->_response;

	}


	// don't stop
	function _get_get()
	{

		$get = $this->input->get(NULL, TRUE);
		$get = is_array($get) ? $get : array();

		if (isset($get['callback'])):
			unset($get['callback']);
		endif;

		return $get;

	}

	function _get_sessions()
	{

		$session  = $this->session->all_userdata();

		if ( ! is_array($session)):

			$session = array();
			return $session;

		endif;

		$clear = array(
			'user_agent',
			'ip_address',
			'last_activity',
			'session_id',
			'user_data'
		);

		foreach ($clear as $key):

			if (isset($session[$key])):

				unset($session[$key]);

			endif;

		endforeach;

		return $session;

	}

	// stolen from http://www.ivankristianto.com/web-development/programming/php-snippet-code-to-generate-random-float-number/1737/
	function _float_rand($min, $max, $round=0)
	{
		//validate input
		if ($min>$max):
			$min = $max; 
			$max = $min; 
		else:
			$min = $min; 
			$max = $max; 
		endif;

		$rand_float = $min + mt_rand() / mt_getrandmax() * ($max - $min);

		if($round>0):
			$rand_float = round($rand_float, $round);
	 	endif;

		return $rand_float;
	}

}
