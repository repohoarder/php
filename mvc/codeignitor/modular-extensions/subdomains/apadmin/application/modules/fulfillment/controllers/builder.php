<?php 

class Builder extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * This method allows the admin to view all services
	 * @return [type] [description]
	 */
	public function view($build_id=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// grab services
		$builds 	= $this->_get_builds($build_id);

		// set template layout to use
		$this->template->set_layout('default');
		
		// set data variables
		$data['builds']	= $builds;

		// load view
		$this->template->build('fulfillment/builder/view', $data);
	}

	/**
	 * This page adds a service to the system
	 */
	public function create()
	{
		// initialize variables
		$data	= array();

		// set template layout to use
		$this->template->set_layout('default');

		// load view
		$this->template->build('fulfillment/builder/create', $data);
	}

	/**
	 * This is the queue that displays all builder tickets
	 * @return [type] [description]
	 */
	public function queue()
	{
		// initialize variables
		$data	= array();

		// grab queue items
		$queue 	= $this->platform->post('builder/queue/get');

		// set template layout to use
		$this->template->set_layout('default');

		// set data variables
		$data['queue']	= $queue['data'];

		// load view
		$this->template->build('fulfillment/builder/queue', $data);
	}

	public function ajax_domains($client_id = NULL)
	{

		$output = $this->platform->post(
			'ubersmith/client/get_domains',
			array(
				'client_id' => $client_id
			)
		);

		$this->load->view(
			'fulfillment/builder/json_output',
			array(
				'output' => $output
			)
		);

	}

	public function surprise()
	{
		$versions = $this->platform->post(
			'builder/build/get_all_versions',
			array()
		);

		if ( ! $versions['success']):

			show_error('Unable to retrieve builds');
			return;

		endif;

		$builds = array();

		foreach ($versions['data']['rows'] as $build):

			$builds[$build['build_id']]['build_id']               = $build['build_id'];
			$builds[$build['build_id']]['name']                   = $build['name'];
			$builds[$build['build_id']]['versions'][$build['id']] = array(
				'version_id' => $build['id'],
				'version'    => $build['version_id'],
				'num_sites'  => $build['num_sites']
			);

		endforeach;

		$data['status'] = 'unsubmitted';
		$data['errors'] = array();

		if ($this->input->post('dom_sub')):

			$params = array(
				'client_id'        => $this->input->post('client_id'),
				'domain'           => $this->input->post('domain_id_'.$this->input->post('client_domain')),
				'build_version_id' => $this->input->post('build_'.$this->input->post('which_build'))
			);

			$resp = $this->platform->post('builder/queue/insert', $params);

			$data['status'] = 'failed';
			$data['errors'] = $resp['error'];

			if ( ! is_array($data['errors'])):

				$data['errors'] = array($data['errors']);

			endif;

			if ($resp['success']):
				$data['status'] = 'success';
			endif;

		endif;

		// set template layout to use
		$this->template->set_layout('default');

		// set data variables
		$data['builds']	= $builds;

		// load view
		$this->template->build('fulfillment/builder/adder', $data);

	}

	/**
	 * This method attempts to build a site
	 * @param  boolean $id [description]
	 * @return [type]      [description]
	 */
	public function build($id=FALSE)
	{
		// if no id passed, show error
		if ( ! $id)
			show_error('You must pass a valid id in order to build a site.');

		// grab build ticket info
		$ticket 	= $this->platform->post('builder/queue/get',array('id' => $id));

		// if unable to grab ticket info, show error
		if ( ! $ticket['success'])
			show_error('Unable to find ticket information.');

		// set ticket info
		$ticket 	= $ticket['data'][0];

		// build site
		$build	= $this->curl->post('http://sitebully.brainhost.com/builder/install/'.$ticket['name'],array('domain' => $ticket['domain'], 'client_id' => $ticket['client_id'], 'partner_id' => $ticket['partner_id']));

		// see if there was an error
		if ( ! $build['success']):	

			// insert error into DB
			$error 	= $this->platform->post('builder/errors/insert',array('queue_id' => $id, 'error_code' => $build['error'], 'error_message' => $build['data']));

			// redirect to builder errors page
			redirect($this->config->item('subdir').'/fulfillment/builder/errors');
		
		endif;	// end checking for errors

		// mark status as completed (status_id = 3)
		$completed 	= $this->platform->post('builder/queue/insertstatus',array('login_id' => $this->session->userdata('login_id'), 'queue_id' => $id, 'status_id' => 3));

		// redirect to queue
		redirect($this->config->item('subdir').'/fulfillment/builder/queue');
	}

	/**
	 * This method closes a queue ticket
	 * @param  boolean $id [description]
	 * @return [type]      [description]
	 */
	public function close($id=FALSE)
	{
		// if no id passed, show error
		if ( ! $id)
			show_error('You must pass a valid id in order to close a ticket.');

		// mark status as closed (status_id = 4)
		$close 	= $this->platform->post('builder/queue/insertstatus',array('login_id' => $this->session->userdata('login_id'), 'queue_id' => $id, 'status_id' => 4));

		// if there was an error, display it
		if ( ! $close['success'])
			show_error('There was an error closing the ticket: '.$close['error']);

		// redirect to queue
		redirect($this->config->item('subdir').'/fulfillment/builder/queue');
	}

	/**
	 * This page displays all errors associated with a queue ticket
	 * @param  boolean $id [description]
	 * @return [type]      [description]
	 */
	public function errors($id=FALSE)
	{
		// initialize variables
		$data	= array();

		// grab queue items
		$queue 	= $this->platform->post('builder/errors/get');

		// set template layout to use
		$this->template->set_layout('default');

		// set data variables
		$data['queue']	= $queue['data'];

		// load view
		$this->template->build('fulfillment/builder/errors', $data);
	}

	/**
	 * This page displays all errors associated with a queue ticket
	 * @param  boolean $id [description]
	 * @return [type]      [description]
	 */
	public function dismiss_error($id=FALSE)
	{
		// dismiss the error
		// attempt rebuilding the site
		$build	= $this->platform->post('builder/');

		// see if there was an error
		if ( ! $build['success']):	

			// insert error into DB
			$error 	= $this->platform->post('builder/errors/insert');
		
		endif;	// end checking for errors
		
		// redirect to builder errors page
		redirect($this->config->item('subdir').'/fulfillment/builder/errors');
	}

	/**
	 * This page displays all errors associated with a queue ticket
	 * @param  boolean $id [description]
	 * @return [type]      [description]
	 */
	public function close_error($id=FALSE)
	{
		// if no id passed, show error
		if ( ! $id)
			show_error('You must pass a valid id in order to close a ticket.');

		// close ticket
		$close	= $this->platform->post('builder/queue/close');

		// if there was an error, display it
		if ( ! $close['success'])
			show_error('There was an error closing the ticket: '.$close['error']);

		// redirect back to errors page	
		redirect($this->config->item('subdir').'/fulfillment/builder/errors');
	}

	/**
	 * This method grabs all build types from the datbase
	 * @param  boolean $build_id [description]
	 * @return [type]            [description]
	 */
	private function _get_builds($build_id=FALSE)
	{
		return array();


		// get all services
		$services 	= $this->platform->post('sales_funnel/service/get_all',array('page_id' => $page_id));
		
		// if unable to grab the services, default it to an empty array
		if ( ! $services['success'] OR empty($services['data']))
			$services['data']	= array();

		return $services['data'];
	}

}