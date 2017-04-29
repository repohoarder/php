<?php 
ini_set("display_errors",'on');
class Autobuild extends MX_Controller
{ 
	
	function index(){
		//load empty page 
		
		$data['response'] = 'hello';
		$this->load->view('ajax_display',$data);
	}

	function build(){
		
		

		// grab build ticket info
		$ticket 	= $this->platform->post('builder/queue/autobuild');

		
		// if unable to grab ticket info, show error
		if ( ! $ticket['success']):
			echo 'Unable to find ticket information.';
			exit();
		endif;

		// set ticket info
		$ticket 	= $ticket['data'][0];
		
		$id = $ticket['id'];
		
		// build site
		$build	= $this->curl->post('http://sitebully.brainhost.com/builder/install/'.$ticket['name'],array('domain' => $ticket['domain'], 'client_id' => $ticket['client_id'], 'partner_id' => $ticket['partner_id']));
		
		$build = json_decode($build,true);
		
		// see if there was an error
		if ( ! $build['success']):	

			// insert error into DB
			$completed 	= $this->platform->post('builder/queue/insertstatus',array('login_id' => '212', 'queue_id' => $id, 'status_id' => 5));

			$error 	= $this->platform->post('builder/errors/insert',array('queue_id' => $id, 'error_code' => $build['error'], 'error_message' => $build['data']));
			$err = $build['data'];
			$error = "Error Code : ".$build['error']. "<br>";
			if(is_array($err)) :
				foreach($error as $k=>$v):
				$error .= "$k=$v";
				endforeach;
			else :
				$error .= "Error : " .$build['data'];
			endif;
			echo $error;
		else:
		// end checking for errors
		
		// mark status as completed (status_id = 3)
		$completed 	= $this->platform->post('builder/queue/insertstatus',array('login_id' => '212', 'queue_id' => $id, 'status_id' => 3));
		endif;
		echo "Build finished successfully.<br>" . $build['data'];
		
	}
	
}
