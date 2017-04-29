<?php

class Partner_website_api extends MX_Controller {

	private 
		$_valid_post       = FALSE,
		$_submitted_ticket = FALSE,
		$_response         = array(
			'success' => 0,
			'errors'  => array(),
			'data'    => array()
		);


	function index()
	{
		echo json_encode($this->_response);
	}

	function add_support_ticket()
	{

		$post = $this->input->post(NULL, TRUE);
		$post = $this->_validate($post);

		$this->_response['data']['cleaned_fields'] = $post;


		if ($this->_valid_post):

			$this->_submitted_ticket = $this->_submit_ticket($post);

		endif;


		if ($this->_valid_post && $this->_submitted_ticket):

			$this->_response['success'] = 1;

		endif;

		$this->index();

	}

	function _submit_ticket($fields)
	{
		if(isset($fields['dept'])):
			if($fields['dept'] == 'partner') :
					$body      = 
				'Name: '    . $fields['name']."\n".
				'Partner: ' . $fields['partner_id']."\n".
				'Email: '   . $fields['email']."\n".
				'Subject: ' . $fields['subject']."\n".
				'Question:' . $fields['your_question']
				;
				$mailed = @mail(
					'partners@allphasehosting.com',
					$fields['subject'],
					$body
				); 
				return true;
				exit(0);
			endif;
		endif;
		/*
		
		 */  
		$fields['your_question'] .= "\n Partner:{$fields['partner_id']}";
		$config = array(
			
				'meta_partner_id' =>$fields['partner_id'],
				'subject'=> $fields['subject'],
				'body'	=> $fields['your_question'],
				'email' => $fields['email'],
				'name' => $fields['name'] 
			
		);
		$ticket = $this->platform->post('ubersmith/ticket/add',$config);
		
		if ( ! $ticket['success']):

			$this->_response['errors'][] = 'Unable to submit ticket at this time.';
			return FALSE;

		endif;

		return TRUE;
	}

	function _validate($fields)
	{

		$this->load->helper('email');

		$required = array(
			'name', 
			'email', 
			'subject', 
			'your_question',
			'partner_id'
		);

		$template = array_combine(
			$required, 
			array_fill(0, count($required), FALSE)
		);


		$fields = $this->_trim_array($fields);

		if (strtolower($fields['your_question'])=='your question'):

			$fields['your_question'] = '';

		endif;
		
		$fields  = array_filter($fields);		
		$missing = array_diff_key($template, $fields);

		if (count($missing)):

			foreach ($missing as $key => $val):

				$this->_response['errors'][$key] = ucfirst(str_replace('_', ' ', $key)) . ' is required.';

			endforeach;

		endif;

		if ( ! empty($this->_response['errors'])):

			return $fields;

		endif;


		if ( ! valid_email($fields['email'])):

			$this->_response['errors']['email'] = 'Please enter a valid email address.';

			$fields['email'] = '';

		endif;

		$this->_valid_post = TRUE;

		return $fields;

	}

	function _trim_array($input){
	 
	    if ( ! is_array($input)):

	        return trim($input);

	   	endif;
	 
	    return array_map(array($this,'_trim_array'), $input);
	}


}