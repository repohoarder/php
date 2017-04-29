<?php

class Services {

	var $_terms = array(
		'one-time'     => 0,
		'monthly'      => 1,
		'semiannually' => 6,
		'yearly'       => 12,
		'biennially'   => 24,
		'triennially'  => 36,
		'quadrenially' => 48
	);

	var $_country = 'US';

	var $_service_options_template = array(
		'name'     => NULL,
		'parent'   => NULL,
		'children' => array(),
		'metadata' => array(),
		'username' => NULL,
		'password' => NULL,
		'server'   => NULL
	);

	var $_ci = NULL;

	function __construct()
	{

		$this->_ci = &get_instance();

	}


	/**
	 * Restricts an array to only contain items with a correlating array key in $_service_options_template.
	 * @param  array $service_options Array of given service options to match against the $_service_options_template template.
	 * @return array                  Cleaned $service_options array.
	 */
	private function _clean_service_options_template($service_options)
	{

		if ( ! is_array($service_options)) :

			$service_options = array();

		endif;

		// Remove values from the passed array if they're not in the $_service_options_template class variable
		$service_options = array_intersect_key($service_options, $this->_service_options_template);

		// Remove empty/NULL values from the service options array
		$service_options = array_filter($service_options);

		return $service_options;

	}

	/**
	 * Format parameters for Platform add service API call
	 * @param  string $service_code    Service shortcode
	 * @param  string $term            How often to rebill the service
	 * @param  string $variant         Which variation of the service to add; determines pricing
	 * @param  array $service_options Array of optional service data
	 * @return array                  Formatted parameter array to be sent to Platform
	 */
	private function _format_add_service_params($service_code, $term, $variant, $service_options)
	{

		try{

			// Grab pricing for the given service
			$price_array = $this->get_price_data($service_code, $term, $variant);

		}catch(Exception $e) {

			throw new Exception('Error retrieving price data: '.$e->getMessage());
			return;

		}

		// Remove invalid service options from the given array
		$service_options = $this->_clean_service_options_template($service_options);

		// Format parameters and return
		$params = array_merge(
			$service_options,
			array(
				'price'  => $price_array[$term]['price'],
				'setup'  => $price_array[$term]['setup'],
				'period' => $this->_terms[$term]
			)
		);

		return $params;

	}


	/**
	 * Add a service to an account
	 * @param string $type            "client" or "order"
	 * @param int $id                 client ID or order ID
	 * @param string $service_code    Which service to add
	 * @param string $term            How often the service will be billed
	 * @param string $variant         Which variation of the service to add; allows same service to have multiple price points
	 * @param array  $service_options Optional service data containing items defined in $_service_options_template above
	 */
	function add($type, $id, $service_code, $term, $variant = 'default', $service_options = array())
	{

		// Check service options array for children services
		if (array_key_exists('children', $service_options) && is_array($service_options['children'])) :

			// Loop child services
			foreach ($service_options['children'] as $key=>$data):

				if ( ! isset($data['term']) || ! isset($data['service_code'])) :

					// If child service lacks shortcode or term, nix it
					unset($service_options['children'][$key]);
					continue;

				endif;

				// Set child variant; choose "default" if none provided
				$child_variant = (isset($data['variant']) ? $data['variant'] : 'default');

				$child_options = (array_key_exists('service_options',$data) ? $data['service_options'] : array());

				try {

					// Format parameters for child service's array for Platform API call
					$service_options['children'][$key] = $this->_format_add_service_params($data['service_code'], $data['term'], $child_variant, $child_options);

				} catch(Exception $e) {

					throw new Exception('Unable to format child parameters ['.$data['service_code'].' / '.$data['term'].' / '.$child_variant.'] '.$e->getMessage());
					return;

				}

			endforeach;

		endif;

		try {

			// Format parameters for Platform API call
			$parameter_array = $this->_format_add_service_params($service_code, $term, $variant, $service_options);

		} catch(Exception $e) {

			throw new Exception('Unable to format parameters '.$e->getMessage());
			return;

		}

		$parameter_array['type'] = $type;
		$parameter_array['id']   = $id;

		$this->_ci->load->library('platform');

		try {

			$response = $this->_ci->platform->post('crm/cart/add',$parameter_array);

		} catch(Exception $e) {

			throw new Exception('Error adding service to Platform: '.$e->getMessage());
			return;

		}

		return $response;

		/*********************************
		
			EXAMPLE PARAMETERS
		
		**********************************
		$type = 'client',
		$id = '101234',
		$service_code = 'core_domain',
		$term = 'yearly',
		$variant = 'default',
		$service_options = array(
			'metadata' => array(
				'registrar' => 'enom',
				'sld' => 'domaintest',
				'tld' => 'com'
			),
			'userid' => 'jthomps',
			'pass' => 'testpass',
			'server' => 'web210.brainhost.com'
			'name' => 'Core: domaintest.com',
			'parent' => NULL,
			'children' => array(
				0 => array(
					'service_code' => 'privacy',
					'term' => 'yearly',
					'variant' => 'default',
					'service_options' => array(
						'name' => 'Domain Privacy',
						'metadata' => array(
							....
						)
					)
				),
				1 => array(
					'service_code' => 'seo',
					'term' => 'semiannually',
					'variant' => 'upsell_page',
					'service_options' => array(
						'name' => 'Improve Your SEO',
						'metadata' => array(
							....
						)
					)
				)
			)
		)
		*********************************/

	}

	/**
	 * Return price for a given service
	 * @param  string $service_code Service plan shortcode
	 * @param  string $term         How often the service will be billed
	 * @param  string $variant      Which service variant to use; allows multiple price points for same service
	 * @return float               Price
	 */
	function get_price($service_code, $term, $variant = 'default')
	{

		$price = 0;

		try {

			$response = $this->get_price_data($service_code, $term, $variant);

			$price = $response['price'];

		}catch(Exception $e) {


			throw new Exception('Error retrieving price data: '.$e->getMessage());
			return;

		}

		return $price;

	}

	/**
	 * Return setup fee for a given service
	 * @param  string $service_code Service plan shortcode
	 * @param  string $term         How often the service will be billed
	 * @param  string $variant      Which service variant to use; allows multiple price points for same service
	 * @return float                Setup fee
	 */
	function get_setup_fee($service_code, $term, $variant = 'default')
	{

		$setup_fee = 0;

		try {

			$response = $this->get_price_data($service_code, $term, $variant);

			$setup_fee = $response['setup'];

		}catch(Exception $e) {


			throw new Exception('Error retrieving price data: '.$e->getMessage());
			return;

		}

		return $setup_fee;

	}

	/**
	 * Get setup fee & price for a service
	 * @param  string $service_code Service plan shortcode
	 * @param  string $term         How often the service will be billed
	 * @param  string $variant      Which service variant to use; allows multiple price points for same service
	 * @return array               Array of price and setup fee (ex. array('price' => '1.11', 'setup' => '20.00'))
	 */
	function get_price_data($service_code, $term, $variant = 'default')
	{

		if ( ! in_array($term, $this->_terms)):

			throw new Exception($term.' is not a valid term for '.$service_code);
			return;

		endif;

		// Grab prices from the country folder
		$path = 'prices/'.$this->_country.'/'.$service_code.'.php';

		// Check the global install for the price file
		$infrastructure_file = CUSTOM_PATH.$path;

		if (file_exists($infrastructure_file)):

			require($infrastructure_file);

		endif;

		// Check the current application for the price file
		// If it exists, overwrite the prices in the global file
		$app_file = APPPATH.$path;

		if (file_exists($app_file)) :

			require($app_file);

		endif;


		// Unable to load price file
		if ( ! isset($prices) || is_null($prices)):

			throw new Exception($path.' does not include a valid prices array.');
			return;

		endif;

		// Always return default prices
		$price_array = $prices['default'];

		// If variant is provided and it's not default, override the default prices
		if (array_key_exists($variant, $prices)) :

			$price_array = $prices[$variant];

		endif;

		// Error if a price doesn't exist for the given term in the given variant
		if ( ! array_key_exists($term, $price_array)) :

			throw new Exception($term.' price is not defined for '.$service_code.'['.$variant.']');
			return;

		endif;

		return $price_array;


	}


}