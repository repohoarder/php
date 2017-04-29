<?php 

class Upload extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		// load config(s)
		$this->load->config('domain_sites');
		$this->load->config('uploads');
	}
	
	public function index($error=FALSE)
	{
		// initialize variables
		$data	= array();
		
		// if data is posted, then submit form
		if ($this->input->post())	return $this->_submit();
		
		// set template layout to use
		$this->template->set_layout('bare');
		
		// set the page's title
		$this->template->title('Whois CSV Domain Uploader');
		
		// append custom css
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/style.css">');
		$this->template->append_metadata('<link rel="stylesheet" href="/resources/modules/pages/assets/css/button.css">');
		
		// set data variables
		$data['sites']		= $this->config->item('sites');
		$data['error']		= urldecode($error);

		// load view
		$this->template->build('whois/upload', $data);
	}

	/**
	 * This method takes a CSV upload, parses it, and adds records into database
	 * @return [type] [description]
	 */
	private function _submit()
	{
		// grab upload config vars
		$config 	= $this->config->item('attributes');

		// load upload class
		$this->load->library('upload',$config);

		// if unable to upload the CSV, show error
		if ( ! $this->upload->do_upload('csv'))
			return $this->_show_page_error($this->upload->display_errors());


		// initialize varibales
		$csv 		= $this->input->post('csv');
		$domain 	= $this->input->post('domain');
		$ns 		= $this->input->post('nameserver');
		$upload 	= $this->upload->data();

		// grab possible sites we will accept uploads for
		$sites 		= $this->config->item('sites');

		// set domain_sites config information
		if ( ! in_array($domain,$sites))
			return $this->_show_page_error('This is not a valid Domain CSV.');

		// parse CSV
		$data 		= $this->_parse_csv($upload['client_name'],$domain);
	
		// if no data array, then we had an error parsing CSV
		if ( ! $data)
			return $this->_show_page_error('There was an error parsing the CSV.');

		// add data from CSV to db table
		$add 		= $this->_add_data($data,$ns);

		// if unable to add, show error
		if ( ! $add)
			return $this->_show_page_error('There was an error adding information to database.');
		
		// return to page
		redirect('whois/upload/Data added successfully.');
	}

	private function _add_data($data=array(),$nameserver=FALSE)
	{
		// if no data passed, do not attempt insert
		if ( ! $data OR empty($data))
			return FALSE;

		// iterate through all data rows
		foreach ($data AS $key => $value):

			// initialize variables
			$field_values	= array();

			// iterate through all field=>values that need inserted
			foreach ($value AS $keys => $values):

				// set field => value
				$field_values[$keys]	= $values;

			endforeach;	// end iterating through field => values

			// add nameserver to field values
			$field_values['nameserver']	= $nameserver;
			$field_values['date_added']	= date('Y-m-d H:i:s');

			// create post array
			$post 	= array(
				'database'	=> 'whois_domains',
				'table'		=> 'domains',
				'data'		=> $field_values
			);

			// insert data
			$this->platform->post('database/insert',$post);

		endforeach;	// end iterating through all data rows

		return TRUE;
	}

	private function _parse_csv($file,$domain)
	{
		// initialize variables
		$file 	= APPPATH.'modules/whois/uploads/'.$file;	// this is the filepath to the csv file
		$data 	= array();									// this will hold all lines of csv into array

		// make sure this is a valid file
		if ( ! is_file($file))
			return FALSE;

		// grab variables this site needs to extract CSV information
		$variables 	= $this->config->item($domain);

		// open the CSV file
		if (($fp = fopen($file,'r')) !== FALSE):

			// iterate through csv
			while (($line = fgetcsv($fp,1000,',')) !== FALSE):

				// initialize variables
				$fields 	= array();

				// iterate through all fields we'd like to collect from $variables
				foreach ($variables['fields'] AS $key => $value):

					// add variables to data array
					if (isset($line[$variables['fields'][$key]])):

						// see if we have where clause
						if (isset($variables['where']) AND is_array($variables['where'])):

							// run any where clauses
							foreach ($variables['where'] AS $keys => $values):

								// if where clause is satisfied, then add item to data array
								if (isset($line[$keys]) AND $line[$keys] == $values)
									$fields[$key]	= $line[$variables['fields'][$key]];

							endforeach;	// end iterating through where clauses

						else: 	// No where clause found, just add item to $data array

							// add item to data array
							$fields[$key]	= $line[$variables['fields'][$key]];

						endif;	// End seeing if we have a where clause

					endif;	// end adding variables to the $data array

				endforeach;	// end iterating through all fields we'd like to collect

				// set data variable
				if ( ! empty($fields))
					$data[]		= $fields;

			endwhile;	// end iterating through csv

			// close the handle
			fclose($fp);

			// remove file
			unlink($file);		

			// return the data
			return $data;

		endif;	// end opening the CSV

		// if we got here, then things failed
		return FALSE;
	}

	private function _show_page_error($error=FALSE)
	{
		redirect('whois/upload/'.$error);
		return;
	}

}