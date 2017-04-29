<?php 

class Scrape extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		// grab domains needing scraped
		$domains 	= $this->platform->post('whois/get_domains',array());

		// iterate domains needing scraped
		foreach ($domains AS $key => $value):

			// grab whois info for domain
			
			
			// insert data into domains_leads OR update domains to inactive (and/or guarded)

		endforeach;	// end iterating domains needing scraped

		echo 'completed';
	}

}