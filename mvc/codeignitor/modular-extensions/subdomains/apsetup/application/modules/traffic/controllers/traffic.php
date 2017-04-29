<?php

class Traffic extends MX_Controller {


	public function campaign($client_id, $pack_id)
	{
		$data				= array();
		
		$categories			= $this->platform->post('traffic_taxi/campaigns/get_categories');
		$data['categories']	= $categories['data']['categories'];
		
		$package			= $this->platform->post('ubersmith/package/get/pack_id/'.$pack_id);
		$data['package']	= $package['data'][0];
		
		if ($data['package']['meta']['username']['value'])
		{
			return false;
		}
		
		if ($data['package']['clientid']!=$client_id)
		{
			return false;
		}
		
		$domains			= $this->platform->post('ubersmith/client/domains', array('client_id'=>$client_id));
		$data['domains']	= $domains['data'];
		
		if ($this->input->post())
		{
			$user			= $this->input->post('traffic_user');
			$pass			= $this->input->post('traffic_pass');
			$url			= $this->input->post('domain');
			$hits			= $data['package']['meta']['traffic_hits']['value'];
			$cat			= $this->input->post('category');
			$submit			= $this->_submit($pack_id, $user, $pass, $url, $hits, $cat);
			if ($submit===true)
			{
				return;
			}
			
			$data['errors']	= $submit;
		}
		
		$this->template->set_layout('bare_no_footer');

		// load view
		$this->template->build('traffic/traffic', $data);
	}


	private function _submit($pack_id, $user, $pass, $url, $hits, $category=9999)
	{
		$params			= array(
			'hits'		=> $hits,
			'url'		=> 'http://'.$url,
			'cat'		=> $category,
			'region'	=> 'BK',
			'user'		=> $user,
			'pass'		=> $pass,
			'cap'		=> $hits/30
		);
		
		$traffic		= $this->platform->post('traffic_taxi/campaigns/add', $params);
		
		$this->template->set_layout('bare_no_footer');
		
		if (!$traffic['success'])
		{
			return $traffic['error'];
		}
		else
		{
			$meta						= array (
				'meta_domain'			=> $url,
				'meta_username'			=> $user,
				'meta_password'			=> $pass,
				'meta_traffic_category'	=> $category
			);
			$update		= $this->platform->post('ubersmith/package/update/'.$pack_id, $meta);
			
			$this->template->build('traffic/submit');
			return true;
		}
	}

}