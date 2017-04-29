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

        $this->template->append_metadata('
            <link rel="stylesheet" href="/resources/apfrontend/js/lightview/css/lightview/lightview.css" />
            <script type="text/javascript" src="/resources/apfrontend/js/lightview/js/lightview/lightview.js"></script>
            <script type="text/javascript" src="/resources/apfrontend/js/jquery.complexify.js"></script>
        ');

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
			'first_name'    => $post['first_name'],
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

                     # var_dump($result);
			$return['error'] = $result['error'];
                        return $return;
			exit(0);

		endif;

        $headers  = 'From: partners@allphasehosting.com'."\r\n";
		$headers .= 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        
        $body    = $post['first_name'].",<br />
<br />
Thank you for submitting your All Phase Hosting Partner application - and congratulations on taking the
first step toward owning your own hosting company! Your information is currently under review and an
Account Manager will be in touch within 24 hours.<br />
<br />
<br />
<b>Anxious to check out all of the features?</b><br />
<br />
Log into our <b>Demo Account</b> to check out all of our great features!<br />
<br />
<a href=\"http://partner.allphasehosting.com\">http://partner.allphasehosting.com</a><br />
<b>Username: Demo1234</b><br />
<b>Password: Demo1234</b><br />
<br />
<br />
<b>What Kind of Partner Will You Be?</b><br />
<table>
<tr><td></td><th>Sales / Day</th><th>Avg. Sale (After Costs)</th><th>First Month's Net</th><th>First Year's Net</th></tr>
<tr><td>Absolute Minimum</td><td>1</td><td>$4</td><td>$120</td><td>$9,360</td></tr>
<tr>Average Partner</td><td>5</td><td>$135</td><td>$20,250</td><td>$1,579,500</td></tr>
<tr><td><b>TOP PARTNERS</b></td><td><b>100</b></td><td><b>$225</b></td><td><b>$675,000</b></td><td><b>$52,650,000</b></td></tr>
</table><br />
<br />
Best,<br />
The All Phase Hosting Team<br />";

        @mail(
            $post['email'],
            'Your All Phase Partner Form Has Been Received',
            $body,
            $headers
        );

        // mail partners@ that somebody signed up
        $message    = '
        Name:   '.$post['first_name'].' '.$post['last_name'].'
        Email:  '.$post['email'].'
        Phone:  '.$post['phone'].'

        http://a.allphasehosting.com/admin/partner/queue
        ';
        @mail('partners@allphasehosting.com', 'All Phase Partner Signup', $message);


                // redirect to next step in signup process
		redirect('signup/register/success');
                
                endif;
	}
    
}

