<?php

class Success extends MX_Controller
{
 
    /**
	 * The ID of this Partner
	 * @var int
	 */
	var $_partner_id;

	public function __construct()
	{
		parent::__construct();
	}
        
    public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();

		// set template layout to use
		$this->template->set_layout('default');
		
		// set the page's title
		$this->template->title('Partner Registration Successful');
            
        // set data variables    
        $data['error'] = urldecode($error);

        $data['demo_account'] = TRUE;
        
		// load view
		$this->template->build('register/success', $data);
	}



}