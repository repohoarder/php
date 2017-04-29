<?php 

class Domain_List extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		// initialize variables
		$data	= array();
		
		// if data is posted, then submit form
		if ($this->input->post())	return $this->_submit();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Whois Domain List');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');

		$post 	= array(
			'database'		=> 'whois_domains',
			'table'			=> 'domains'
		);
		$domains			= $this->platform->post('database/select_all',$post);
		$data['domains']	= $domains['data'];
		
		// load view
		$this->template->build('whois/list', $data);
	}
	
	public function run($domain_id)
	{
		// initialize variables
		$data	= array();
		
		// if data is posted, then submit form
		if ($this->input->post())	return $this->_submit();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Whois Domain List');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');

		$post 	= array(
			'database'				=> 'whois_domains',
			'table'					=> 'domains'
		);
		$domains					= $this->platform->post('database/select_all',$post);
		$data['domains']			= $domains['data'];
		foreach ($data['domains'] as $domain)
		{
			if ($domain['id'] == $domain_id)
			{
				$lookup				= $this->platform->post('whois/domain/lookup/'.$domain['domain']);
				$domain['lookup']	= $lookup['data'];
				$data['domain']		= $domain;
			}
		}
		
		// load view
		$this->template->build('whois/run', $data);
	}

}