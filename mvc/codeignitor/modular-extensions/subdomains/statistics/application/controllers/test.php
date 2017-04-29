<?php 
class Test extends CI_Controller
{

	/*
	Need to set a Client ID in https://code.google.com/apis/console/b/0/?api=analytics#project:561553201233:access
	Each Client ID has a whitelist for domains
		Whitelist is protocol-, subdomain-, and path-specific


	Step 1: Hit /get_auth_code and you'll be sent back to /get_auth_code?code=XXXX
	Step 2: Take the ?code and put it into the params of /get_access_token
	Step 3: Hooray! You should have an access token.
		If your access token expires, use the refresh_token from Step 2 in the refresh_token() method to get a new one
	Step 4: Plug your access token into your query parameters (get_bh_visits_and_visitors() as an example)

	*/
	

	/*
	
	BRAIN HOST => ga:36327880
	BH BRAZIL  => ga:63301070

	*/

	function format()
	{

		$json = '{"kind":"analytics#gaData","id":"https://www.googleapis.com/analytics/v3/data/ga?ids=ga:36327880&dimensions=ga:date&metrics=ga:visitors,ga:visits&start-date=2012-09-11&end-date=2012-09-18&start-index=1&max-results=50","query":{"start-date":"2012-09-11","end-date":"2012-09-18","ids":"ga:36327880","dimensions":"ga:date","metrics":["ga:visitors","ga:visits"],"start-index":1,"max-results":50},"itemsPerPage":50,"totalResults":8,"selfLink":"https://www.googleapis.com/analytics/v3/data/ga?ids=ga:36327880&dimensions=ga:date&metrics=ga:visitors,ga:visits&start-date=2012-09-11&end-date=2012-09-18&start-index=1&max-results=50","profileInfo":{"profileId":"36327880","accountId":"18304228","webPropertyId":"UA-18304228-1","internalWebPropertyId":"36853900","profileName":"brainhost.com","tableId":"ga:36327880"},"containsSampledData":false,"columnHeaders":[{"name":"ga:date","columnType":"DIMENSION","dataType":"STRING"},{"name":"ga:visitors","columnType":"METRIC","dataType":"INTEGER"},{"name":"ga:visits","columnType":"METRIC","dataType":"INTEGER"}],"totalsForAllResults":{"ga:visitors":"28817","ga:visits":"31627"},"rows":[["20120911","3911","4275"],["20120912","4344","4740"],["20120913","4507","4904"],["20120914","3966","4332"],["20120915","3161","3526"],["20120916","3120","3428"],["20120917","4255","4716"],["20120918","1553","1706"]]}';

		$response = json_decode($json, TRUE);

		$formatted = array();

		foreach ($response['rows'] as $row):

			$count = 0;

			foreach ($response['columnHeaders'] as $header):

				$formatted[$row[0]][$header['name']] = $row[$count];

				$count++;

			endforeach;

		endforeach;



		

		$brands = array();

		foreach ($formatted as $date_string => $data):

			$date = strtotime($data['ga:date']);

			$brands['Brain Host'][] = array(

				'year'   => date('Y',$date),
				'month'  => date('n',$date),
				'date'   => date('j',$date),
				'amount' => floatval($data['ga:visits'])

			);

		endforeach;


		echo var_export($brands, TRUE);

		exit();


	}

	function get_brazil_visits_and_visitors()
	{

		$url = 'https://www.googleapis.com/analytics/v3/data/ga';

		$params = array(
			'ids'          => 'ga:63301070',
			'dimensions'   => 'ga:date',
			'metrics'      => 'ga:visitors,ga:visits',
			'start-date'   => '2012-09-11',
			'end-date'     => '2012-09-18',
			'max-results'  => '50',
			'access_token' => 'ya29.AHES6ZTlbh36N4B1rR8nFXUE92NxBFVhN2FAInb_bSG8nhtLWAdcfQ'
		);

		$this->load->library('curl');

		$response   = $this->curl->get_auth_header($url, $params, $params['access_token']);
		
		var_dump($response); exit();

		$resp_array = json_decode($response, TRUE);

		var_dump($resp_array);


	}


	function get_bh_visits_and_visitors()
	{

		$url = 'https://www.googleapis.com/analytics/v3/data/ga';

		$params = array(
			'ids'          => 'ga:36327880',
			'dimensions'   => 'ga:date',
			'metrics'      => 'ga:visitors,ga:visits',
			'start-date'   => '2012-09-11',
			'end-date'     => '2012-09-18',
			'max-results'  => '50',
			'access_token' => 'ya29.AHES6ZTlbh36N4B1rR8nFXUE92NxBFVhN2FAInb_bSG8nhtLWAdcfQ'
		);

		$this->load->library('curl');

		$response   = $this->curl->get_auth_header($url, $params, $params['access_token']);
		
		var_dump($response); exit();

		$resp_array = json_decode($response, TRUE);

		var_dump($resp_array);


	}


	function refresh_token()
	{

		$url = 'https://accounts.google.com/o/oauth2/token';

		$params = array(
			'client_id'     => '397409216763.apps.googleusercontent.com',
			'client_secret' => 'G8cDwxkIGnudtTtS3CGcqUr9',
			'grant_type'    => 'refresh_token',
			'refresh_token' => '1/9dEbfE0cg053SS6_8Q5TgvOFNoeMMT7uEr14izcgUYg'
		);
		

		// string(127) "{ "access_token" : "ya29.AHES6ZRaznX9W2wik5o-UI9TiSJ3mQkNL1yc3XOVVFPF66c", "token_type" : "Bearer", "expires_in" : 3600 }" 

		$this->load->library('curl');

		$response = $this->curl->post($url, $params);

		var_dump($response);

	}


	function get_access_token()
	{

		$url = 'https://accounts.google.com/o/oauth2/token';

		$params = array(
			'code'          => '4/yQl2J1Che_Mq3l4kh5Q1q5yeLfC7.ov3S_kZj-1AcuJJVnL49Cc8UF3u_cwI',
			'redirect_uri'  => 'http://statistics.brainhost.com/test/get_auth_code',
			'client_id'     => '397409216763.apps.googleusercontent.com',
			'scope'         => '',
			'client_secret' => 'G8cDwxkIGnudtTtS3CGcqUr9',
			'grant_type'    => 'authorization_code'
		);

		$this->load->library('curl');

		$response = $this->curl->post($url, $params);

		// string(196) "{ "access_token" : "ya29.AHES6ZQPM9ARYq6dQ3PJrbGO5iwoJ1hSxOS1C8bHibgW3Ao", "token_type" : "Bearer", "expires_in" : 3600, "refresh_token" : "1/mM_nJeO5yTPt-gKAgXqnYRFGuQkyweNQQ4FpsGyHdp8" }"

		var_dump($response);

	}


	function get_auth_code()
	{

		$url = 'https://accounts.google.com/o/oauth2/auth';

		$params = array(
			'redirect_uri'    => 'http://statistics.brainhost.com/test/get_auth_code',
			'response_type'   => 'code',
			'client_id'       => '397409216763.apps.googleusercontent.com',
			'approval_prompt' => 'force',
			'scope'           => 'https://www.googleapis.com/auth/analytics.readonly',
			'access_type'     => 'offline'

		);

		$this->load->library('curl');

		$response = $this->curl->post($url, $params);

		var_dump($response);


		// code = 4/AinmyDQOhMzA_uAQChy_mh1-c0QI.IobtDqKSHXUZuJJVnL49Cc-43iuzcwI


		/*
		$this->load->library('google_api');
		$this->google_api->super_test();
		*/
	
	}

}
