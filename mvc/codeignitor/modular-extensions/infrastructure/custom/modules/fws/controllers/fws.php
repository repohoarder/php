<?php 

class Fws extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function fun()
	{
		// randomly generate data
		$first 		= $this->_get_first_name();
		$last 		= $this->_get_last_name();
		$email 		= $this->_get_email($first,$last);
		$pass 		= $this->_get_password($first,$last);

		// create post array
		$post 		= array(
			'pages'		=> 'version',
			'name'		=> $first.' '.$last,
			'email'		=> $email,
			'password'	=> $pass,
			'submit'	=> 'Get My FREE Website'
		);
	
		//$this->debug->show($post,true);


		$response = $this->platform->post(
			'curl_proxy/go',
			array(
				'urls'     => array(
					array(
						'addr' => 'http://tracking.freewebsite.com/?a=349&c=17&p=r&s1=',
					),
					array(
						'addr' => 'http://www.freewebsite.com/order/setup/optin',
						'post' => $post
					)
				),
				'api_key' => 'iwishiwasan0scarMayerwi3ner'
			)
		);

		$this->debug->show(
			array(
				'post'     => $post,
				'response' => $response
			), 
			TRUE
		);


	}

	private function _get_email($first,$last)
	{
		// create array of free email domains
		$domains 	= array(
			'gmail.com',
			'hotmail.com',
			'yahoo.com',
			'ymail.com',
			'aol.com'
		);

		// generate random number
		$local 		= $this->_get_local_email($first,$last);

		// grab random domain
		$domain 	= $domains[rand(0,(count($domains)-1))];

		return $local.'@'.$domain;
	}

	private function _get_password()
	{

		// grab random 
		switch(rand(0,2)):
			
			case 0:	// uber

				$password 	= $this->platform->post('ubersmith/password/generate');
				$password 	= $password['data']['password'];
				break;
			case 1:	// random number
				$password 	= rand(1000,9999999);
				break;
			case 2:

				$names 		= array(
					'pikachu',
					'morpheus',
					'hotgirlz',
					'morbid',
					'deathstroke',
					'geeknoob',
					'supermonkey',
					'golfer',
					'ballplayer',
					'champ',
					'daddy',
					'longnhung',
					'pimp',
					'tpk',
					'supafreak',
					'videogamerz',
					'nintendo',
					'mario',
					'linq',
					'zelda',
					'nympho',
					'sexgoddess',
					'hotwheelz',
					'ponies',
					'happy',
					'butter',
					'butterfly',
					'snooze',
					'winner',
					'fleece',
					'wooljacket',
					'supertramp',
					'fouler',
					'dumbell',
					'gizmo',
					'prioritize',
					'babies',
					'freaky',
					'lordofrings',
					'pizzapie',
					'goofy',
					'goofballz',
					'clown',
					'happygilmore',
					'danceswithdogs',
					'flirt',
					'secret',
					'sauce',
					'secretsauce',
					'dingbat',
					'owls',
					'hearts'
				);

				$password 	= $names[rand(0,(count($names)-1))].rand(1,999);
				break;
		endswitch;

		return $password;
	}

	private function _get_local_email($first,$last)
	{
		// grab random 
		switch(rand(0,2)):
			
			case 0:	// first initial last name
				$local 	= preg_replace('/[^a-zA-Z0-9]/','',strtolower(substr($first,0,1).$last.rand(1,500)));
				break;

			case 1:	// first name last initial
				
				$local 	= preg_replace('/[^a-zA-Z0-9]/','',strtolower($first.substr($last,0,1).rand(1,500)));

				break;
			case 2:	// fname.lname

				$local 	= preg_replace('/[^a-zA-Z0-9]/','',strtolower($first)).'.'.preg_replace('/[^a-zA-Z0-9]/','',strtolower($last)).rand(0,999);
				break;

			case 3:	// create email from random characters

				$shitty 	= array(
					'pekachu',
					'morpheus',
					'hotgirlz',
					'morbid',
					'deathstroke',
					'geeknoob',
					'supermonkey',
					'golfer',
					'ballplayer',
					'champ',
					'daddy',
					'longnhung',
					'pimp',
					'tpk',
					'supafreak',
					'videogamerz',
					'nintendo',
					'mario',
					'linq',
					'zelda',
					'nympho',
					'sexgoddess',
					'hotwheelz',
					'ponies',
					'happy',
					'butter',
					'butterfly',
					'snooze',
					'winner',
					'fleece',
					'wooljacket',
					'supertramp',
					'fouler',
					'dumbell',
					'gizmo',
					'prioritize',
					'babies',
					'freaky',
					'lordofrings',
					'pizzapie',
					'goofy',
					'goofballz',
					'clown',
					'happygilmore',
					'danceswithdogs',
					'flirt',
					'secret',
					'sauce',
					'secretsauce',
					'dingbat',
					'owls',
					'hearts'
				);

				$local 	= $shitty[rand(0,(count($shitty)-1))].rand(0,300);
				break;

		endswitch;

		// return local email
		return $local;
	}

	private function _get_first_name()
	{
		// initialize variables
		$names 	= array(
			'Joey',
			'William',
			'Bill',
			'Ashley',
			'Christina',
			'Susan',
			'Fred',
			'Michael',
			'Mike',
			'Matthew',
			'John',
			'Jessica',
			'Katrina',
			'Al',
			'Robert',
			'Frank',
			'Tom',
			'Thomas',
			'Ryan',
			'Mickey',
			'Charles',
			'James',
			'Robert',
			'David',
			'Richard',
			'Charles',
			'Christopher',
			'Chris',
			'Don',
			'Donald',
			'George',
			'Harry',
			'Lucille',
			'Michelle',
			'Diane',
			'Melissa',
			'Anthony',
			'Tony',
			'JP',
			'Peter',
			'Paul',
			'Jeremy',
			'Ben',
			'Benjamin'
		);

		// grab name
		$name 	= $names[rand(0,(count($names)-1))];

		return $name;
	}

	private function _get_last_name()
	{
		// initialize variables
		$names 	= array(
			'Douglas',
			'Sizemore',
			'Roberts',
			'Smith',
			'Johnson',
			'Crocker',
			'Hanks',
			'James',
			'Bryant',
			'Byrd',
			'Young',
			'Miller',
			'Yoder',
			'O\'Hare',
			'Williames',
			'Secrest',
			'Jones',
			'Brown',
			'Davis',
			'Wilson',
			'Moore',
			'Jackson',
			'Thomas',
			'White',
			'Harris',
			'Martin',
			'Garcia',
			'Martinez',
			'Rodriguez',
			'Walker',
			'Louis',
			'Mickey'
		);

		// grab name
		$name 	= $names[rand(0,(count($names)-1))];

		return $name;
	}

}