<?php

class Account extends MX_Controller
{
 
    /**
	 * The ID of this Partner
	 * @var int
	 */
	var $_partner_id;

	public function __construct()
	{
		parent::__construct();

		// set the partner id
		$this->_partner_id	= $this->session->userdata('partner_id');
	}
        
    public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();

		// if user POSTed data, run submit function
		if ($this->input->post()) :

            $formreturn = $this->_submit();
            
            // set variables for the form values and error.
            $error              = isset( $formreturn['error'] ) ? $formreturn['error']  : '';
            $data['account']    = isset($formreturn['data']) ?  $formreturn['data']     : '';

        endif;  

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Sign Up');
            
        // set data variables    
        $data['error'] = urldecode($error);
        
		// load view
		$this->template->build('register/signup', $data);
	}

	private function _submit()
	{
                // set empty error array
                $requirederror = array();
                // initialize post array;
		$post= array();
                // set required fields array
                $required = array(
                        "username"      => "Username",
                        "password"      => "Password",
                        "rpassword"     => "Retype Password",
                        "first_name"    => "First Name",
                        "last_name"     => "Last Name",
                        "company"       => "Company",
                        "address"       => "Address",
                        "zip"           => "Postal Code",
                        "state"         => "State/Province",
                        "email"         => "Email",
                        "country"       => "Country"
                );
                
                // loop thru post array and create $post variable array
                foreach ($_POST as $k=>$v):
                    $post[$k] = trim($v);
                endforeach;
                
                // set return post data
                $return['data'] = $post;
                // check for empty form fields and create an error array
                foreach($required as $required_field=>$label):
                    if(empty($post[$required_field])) :
                       $requirederror[] = "$label is required";
                    endif;
                endforeach;
                
                // check to see if passwords dont match
                if($post['password'] != $post['rpassword']) :
                    $requirederror[] = "Passwords do not match.";
                endif;
                
                $validusername = $this->platform->post('partner/account/valid_username',$post);
                if( ! $validusername['success']) :
                    $requirederror[] = "Username already exists.";
                endif;
                    
                // if there are errors
                if ( ! empty ($requirederror)) :
                    
                    // return error and form data for sticky form
                    $return['error'] =  implode("<br>\n",$requirederror);
                
                    return $return;
                    exit(0);
		else :
                    
                // generate password and password salt
                $password = $this->password->generate($post['password']);
                
		// create array of fields to update
		$insert 	= array(
			'company'		=> $post['company'],
			'first_name'            => $post['first_name'],
			'last_name'		=> $post['last_name'],
			'email'			=> $post['email'],
			'address'		=> $post['address'],
			'city'			=> $post['city'],
			'state'			=> $post['state'],
			'zip'			=> $post['zip'],
			'country'		=> $post['country'],
			'phone'			=> $post['phone'],
			'username'		=> $post['username'],
			'password'		=> $password['encrypted'],
			'password_salt'         => $password['salt']
		);
               
		// update partner website details
		$result = $this->platform->post('partner/account/add',$insert);

		// if update was not successful, then return error
		if ( ! $result['success'] OR ! $result['data']):

                      var_dump($result);
			$return['error'] = "There was an error creating your account.";
                        return $return;
			exit(0);

		endif;
                // redirect to next step in signup process
		redirect('signup/register/success');
                
                endif;
	}
    
}

