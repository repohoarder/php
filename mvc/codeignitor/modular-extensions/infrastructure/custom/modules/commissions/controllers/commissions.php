<?php 

class Commissions extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();

		// load excel third party library
		$this->load->add_package_path(CUSTOM_PATH.'third_party/excel/');

		// load phpexcel
		$this->load->library('PHPExcel');
	}

	public function index($success=FALSE,$data=array())
	{
		// initialize response
		$response	= ($success)? array('success' => TRUE, 'data' => $data): array('success' => FALSE, 'error' => $data);

		echo json_encode($response);
	}

	public function add($error=FALSE)
	{
		// if data was posted, then add it to table
		if ($this->input->post()):

			// initialize variables
			$affiliate_id 	= $this->input->post('affiliate_id');
			$type 			= $this->input->post('type');
			$amount 		= $this->input->post('amount');
			$arrears 		= $this->input->post('arrears');
			$start 			= $this->input->post('start');
			$end 			= $this->input->post('end');

			// create post array 
			$post 			= array(
				'affiliate_id'	=> $affiliate_id,
				'type'			=> $type,
				'amount'		=> $amount,
				'arrears'		=> $arrears,
				'start'			=> $start,
				'end'			=> $end
			);

			// add data
			$add 	= $this->platform->post('affiliate_software/commission/add_pay_term',$post);

			$error 	= ( ! $add['success'] OR ! $add['data'])? $add['error']: 'Adding affiliate term was successful.';

		endif;

		// get current affiliate terms
		$terms 		= $this->platform->post('affiliate_software/commission/get');

		// error handling
		if ( ! $terms['success'] OR empty($terms['data']))
			$terms['data'] 	= array();

		// set data variables
		$data['error'] 	= $error;
		$data['terms']	= $terms['data'];

		// load view
		$this->load->view('add',$data);
	}

	public function download($filename=FALSE)
	{
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		readfile(CUSTOM_PATH.'modules/commissions/statements/'.$filename);
	}

	public function pay()
	{
		// initialize variables
		$export 	= array();	// this array holds the data to be exported
		$errors 	= array();	// this array holds an errors encountered
		$files 		= array();	// this is an array of files to email to Ana (accounting)

		// run refund cron before we get started (in case it hasn't ran yet)?

		// grab all affiliate payout terms
		$terms 	= $this->platform->post('affiliate_software/commission/get');

		// if no terms/affiliates, return error
		if ( ! $terms['success'] OR ! isset($terms['data'][0]) OR empty($terms['data'][0]))
			return $this->index(FALSE,'No affiliate terms found.');

		// iterate through each affiliate term
		foreach ($terms['data'] AS $key => $value):

			// get data
			$data 	= $this->_get_data($value);

			// if we didn't get any data, then we need to skip this affiliate (but log it)
			if ( ! is_array($data) OR empty($data)):

				// add to errors
				$errors[]	= array(
					'affiliate_id'	=> $value['affiliate_id'], 
					'error'			=> $data
				);

			
			
			else:
			// add this affiliate id to the export array
			$export[$value['type']][$value['affiliate_id']]	= array();

			// iterate through each data array (each offer's statistics) for this affiliate
			foreach ($data AS $keys => $values):

				// set the variables for this offer id
				$export[$value['type']][$value['affiliate_id']][$values['offer_id']]	= array_merge($values,$value);	// add sales data and pay terms to array

			endforeach;
		endif;
		endforeach;

		// add errors to export array
		$export['errors']	= $errors;
	//	echo"<pre>";print_r($export);
		
		// create export workbook for each payment term
		foreach ($export AS $pay_term => $values):

			// create the export file
			$files[$pay_term]	= $this->_build_workbook_data($pay_term,$values);

		endforeach;
		
		// send email to Ana (accounting) with the files to download
		$this->_send_mail($files);

		return $this->index(TRUE,$files);
	}

	private function _send_mail($files=array())
	{
		// initialize variables
		$message = '';

		// iterate all files
		foreach ($files AS $term => $file):

			// make sure we got a valid file
			if ($file)
				// append to email message
				$message .= ucwords(str_replace('_',' ',$term)).':  http://'.$_SERVER['HTTP_HOST'].'/commissions/download/'.$file.'\r\n';

		endforeach;

		// send email
		mail('thompson2091@gmail.com,anamarie.kachovec@brainhost.com','Affiliate Commission Statement '.date('m/d/Y'),$message);

		return;
	}

	private function _get_data($term)
	{
		// initialize variables
		$affiliate_id 	= $term['affiliate_id'];
		$type 			= $term['type'];
		$amount 		= $term['amount'];
		$arrears 		= $term['arrears'];
		$start 			= $term['start_day'];
		$end 			= $term['end_day'];

		// create date range to select data for
		$date_range		= $this->_create_date_range($arrears,$start,$end);

		// grab sale and refund counts for this affiliate
		$data 			= $this->_get_sales_data($affiliate_id,$date_range['start'],$date_range['end']);

		// error handling
		if ( ! $data)
			return 'Unable to grab sales data for affiliate id: '.$affiliate_id;

		return $data;
	}

	private function _build_workbook_data($term='rev_share',$data=array())
	{
		// initialize variables
		$workbook 	= array();											// array of data to pass to create Excel Workbook
		$filename 	= $term.'_'.strtotime(date('Y-m-d H:i:s')).'.xls';	// filename of workbook

		// if term == errors, then we need to do something else ..
		//if ($term == 'errors')	return $data;

		// iterate through the affiliates data
		foreach ($data AS $affiliate_id => $offers):

			if($term != 'errors') :
			// iterate through this affiliate's offer
			foreach ($offers AS $offer_id => $values):

				// if this affiliate data isn't already set - then set it
				if ( ! isset($workbook[$affiliate_id]) OR empty($workbook[$affiliate_id])):

					// initialize workbook data
					$workbook[$affiliate_id]	= array(
						'name'			=> $values['first_name'].' '.$values['last_name'],
						'email'			=> $values['email_id'],
						'company'		=> $values['company_name'],
						'address'		=> $values['street_address'],
						'address2'		=> $values['additional_address'],
						'city'			=> $values['city'],
						'state'			=> $values['state'],
						'zip'			=> $values['postal_code'],
						'country'		=> $values['country_name'],
						'affiliate_id'	=> $values['affiliate_id'],
						'username'		=> $values['username'],
						'phone'			=> $values['phone_no'],
						'start'			=> $values['date_range']['start'],
						'end'			=> $values['date_range']['end'],
						'pay_term'		=> $term,
						'pay_amount'	=> $values['amount']
					);

				endif;

				// set commission amount
				switch($term):
					case 'rev_share':
						$gross_commission 	= number_format(($values['hosting_revenue'] * ($values['amount'] / 100)),2);
						$gross_refunds 		= number_format(($values['hosting_refunds'] * ($values['amount'] / 100)),2);
						$total_commission 	= number_format((($values['hosting_revenue'] * ($values['amount'] / 100)) - ($values['hosting_refunds'] * ($values['amount'] / 100))),2);
						break;
					case 'net_cpa':
						$gross_commission 	= number_format(($values['sales_count'] * ($values['amount'])),2);
						$gross_refunds	 	= number_format(($values['refund_count'] * ($values['amount'])),2);
						$total_commission 	= number_format(($values['sales_count'] - $values['refund_count']) * ($values['amount']),2);
						break;
					case 'cpa':
						$gross_commission 	= number_format(($values['sales_count'] * ($values['amount'])),2);
						$gross_refunds	 	= number_format(($values['refund_count'] * $values['amount']),2);
						$total_commission 	= number_format(($values['sales_count'] * $values['amount']),2);
						break;
				endswitch;

				// set offers data for workbook
				$workbook[$affiliate_id]['offers'][]	= array(
					'id'				=> $values['offer_id'],
					'title'				=> $values['offer_title'],
					'sales_counts'		=> $values['sales_count'],
					'sales_revenue'		=> number_format($values['hosting_revenue'],2),
					'refund_counts'		=> $values['refund_count'],
					'refund_revenue'	=> number_format($values['hosting_refunds'],2),
					'remaining_sales'	=> ($values['sales_count'] - $values['refund_count']),
					'chargeback_counts'	=> 0,
					'chargeback_amount'	=> 0.00,
					'gross_commission'	=> $gross_commission,
					'gross_refunds'		=> $gross_refunds,
					'total_commission'	=> $total_commission
				);

			endforeach;
			else:
				$workbook = $data; // set error data to workbook array
			endif;
		endforeach;

		// set method to run (payment term)
		$method 	= '_'.$term;

		// run method for this specific payment term to build the workbook
		return ($this->$method($filename,$workbook))? $filename: FALSE;
	}

	private function _create_date_range($arrears='45',$start='Sunday',$end='Saturday')
	{
		// go $arrears days back from today
		$date_in_arrear	= date('Y-m-d',strtotime('-'.$arrears.' days'));

		// is date_in_arrear the start day? 
		$start_date 	= (date('l',strtotime($date_in_arrear)) == $start)? $date_in_arrear: date('Y-m-d',strtotime('Previous '.$start, strtotime($date_in_arrear)));

		// set the end date for the range
		$end_date 		= date('Y-m-d',strtotime('Next '.$end, strtotime($start_date)));

		return array(
			'start'	=> $start_date,
			'end'	=> $end_date
		);
	}

	private function _get_sales_data($affiliate_id=FALSE,$start=FALSE,$end=FALSE)
	{
		// error handling
		if ( ! $affiliate_id OR ! $start OR ! $end)
			return 'Invalid affiliate id and/or start/end date(s).';

		// grab data for this affiliate
		$data 	= $this->platform->post('affiliate_software/commission/payout',array('affiliate_id' => $affiliate_id, 'start' => $start, 'end' => $end));

		// if no data, then return empty array
		if ( ! $data['success'] OR empty($data['data']))
			return 'No data found for affiliate: '.$affiliate_id;

		// add date range to data
		foreach ($data['data'] AS $key => $value):

			// add date range to data array for every offer
			$data['data'][$key]['date_range']	= array(
				'start'	=> $start,
				'end'	=> $end
			);

		endforeach;

		return $data['data'];
	}

	private function _get_affiliate_info($affiliate_id=FALSE)
	{
		// grab affiliate information
		$affiliate 		= $this->platform->post('affiliate_software/affiliate/get',array('affiliate_id' => $affiliate_id));

		// return any errors 
		if ( ! $affiliate['success'] OR ! isset($affiliate['data'][0]) OR empty($affiliate['data'][0]))
			return FALSE;

		// return affiliate data
		return $affiliate['data'][0];
	}

	private function _rev_share($filename=FALSE,$data=array())
	{
		if ( ! $filename OR empty($data))
			return FALSE;

		// Set properties
		$this->phpexcel->getProperties()
			->setCreator("AnaMarie Kachovec")
		 	->setLastModifiedBy("AnaMarie Kachovec")
		 	->setTitle("Affiliate Commission Report")
		 	->setSubject("Affiliate Commission Report")
			->setDescription("Affiliate Commission Report")
			->setKeywords("Affiliate Commission Report")
			->setCategory("Affiliate");

		// iterate through affiliates (create new excel sheet for each sheet)
		$sheet	= 0;
		foreach ($data AS $affiliate_id => $row):

			// create new sheet
			if ($sheet != 0) $this->phpexcel->createSheet();

			// set active worksheet
			$this->phpexcel->setActiveSheetIndex($sheet)
				->setCellValue('A1', 'Affiliate Information')
	            ->setCellValue('A2', $row['name'])
	            ->setCellValue('A3', $row['email'])
	            ->setCellValue('A4', $row['company'])
	            ->setCellValue('A5', $row['address'])
	            ->setCellValue('A6', $row['address2'])
	            ->setCellValue('A7', $row['city'].",".$row['state']." ".$row['zip'])
	            ->setCellValue('A8', $row['country'])
	            ->setCellValue('A12', 'Account Information')
	            ->setCellValue('A13', 'Affiliate ID')
	            ->setCellValue('B13', $row['affiliate_id'])
	            ->setCellValue('A14', 'Username')
	            ->setCellValue('B14', $row['username'])
	            ->setCellValue('A15', 'Phone Number')
	            ->setCellValue('B15', $row['phone'])
	            ->setCellValue('A17', 'Generated Date')
	            ->setCellValue('B17', date('m/d/Y'))
	            ->setCellValue('A18', 'Commission Start Date')
	            ->setCellValue('B18', date('m/d/Y',strtotime($row['start'])))
	            ->setCellValue('A19', 'Commission End Date')
	            ->setCellValue('B19', date('m/d/Y',strtotime($row['end'])))
	            ;

			// Bold the cells
			$styleArray = array('font' => array('bold' => true));
			$this->phpexcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
			$this->phpexcel->getActiveSheet()->getStyle('A12')->applyFromArray($styleArray);
			$this->phpexcel->getActiveSheet()->getStyle('A17:F17')->applyFromArray($styleArray);


			// iterate all offers for this affiliate
			$cell 	= 21;
			$total 	= 0;
			foreach ($row['offers'] AS $key => $row2):

				// Add some data
				$this->phpexcel->setActiveSheetIndex($sheet)
				            ->setCellValue('A'.$cell, $row2['title'])
				            ->setCellValue('B'.$cell, '')
				            ->setCellValue('C'.$cell, 'Transaction Counts')

				            // gross commission
				            ->setCellValue('A'.($cell + 1), 'Gross Commission')
				            ->setCellValue('B'.($cell + 1), $row2['gross_commission'])
				            ->setCellValue('C'.($cell + 1), $row2['sales_counts'])

				            // gross commission
				            ->setCellValue('A'.($cell + 2), 'Refunds & Credits')
				            ->setCellValue('B'.($cell + 2), $row2['gross_refunds'])
				            ->setCellValue('C'.($cell + 2), $row2['refund_counts'])

				            // gross commission
				            ->setCellValue('A'.($cell + 3), 'Chargebacks')
				            ->setCellValue('B'.($cell + 3), '0.00')
				            ->setCellValue('C'.($cell + 3), '0')

				            // gross commission
				            ->setCellValue('A'.($cell + 4), 'Total Commission')
				            ->setCellValue('B'.($cell + 4), $row2['total_commission']);

				// bold the offer title & total commission
				$this->phpexcel->getActiveSheet()->getStyle('A'.$cell)->applyFromArray($styleArray);
				$this->phpexcel->getActiveSheet()->getStyle('A'.($cell + 4))->applyFromArray($styleArray);

				// increment cell
				$cell = ($cell + 6);

				// add total
				$total 	= ($total + $row2['total_commission']);

			endforeach;

			// add total commission field
			$cell = ($cell + 2);
			$this->phpexcel->setActiveSheetIndex($sheet)
							            ->setCellValue('A'.$cell, 'Total Net Commissions')
							            ->setCellValue('B'.$cell, $total);

			// bold the net commission row
			$this->phpexcel->getActiveSheet()->getStyle('A'.$cell.':B'.$cell)->applyFromArray($styleArray);

			// Rename sheet
			$this->phpexcel->getActiveSheet()->setTitle($row['affiliate_id']);

			// increment sheet counter
			$sheet++;

		endforeach;

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->phpexcel->setActiveSheetIndex(0);
		
		// write the file
		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
		$objWriter->save(CUSTOM_PATH.'modules/commissions/statements/'.$filename);

		return TRUE;
	}


	private function _net_cpa($filename=FALSE,$data=array())
	{
		if ( ! $filename OR empty($data))
			return FALSE;

		// Set properties
		$this->phpexcel->getProperties()
			->setCreator("AnaMarie Kachovec")
		 	->setLastModifiedBy("AnaMarie Kachovec")
		 	->setTitle("Affiliate Commission Report")
		 	->setSubject("Affiliate Commission Report")
			->setDescription("Affiliate Commission Report")
			->setKeywords("Affiliate Commission Report")
			->setCategory("Affiliate");

		// iterate through affiliates (create new excel sheet for each sheet)
		$sheet	= 0;
		foreach ($data AS $affiliate_id => $row):

			// create new sheet
			if ($sheet != 0) $this->phpexcel->createSheet();

			// set active worksheet
			$this->phpexcel->setActiveSheetIndex($sheet)
				->setCellValue('A1', 'Affiliate Information')
	            ->setCellValue('A2', $row['name'])
	            ->setCellValue('A3', $row['email'])
	            ->setCellValue('A4', $row['company'])
	            ->setCellValue('A5', $row['address'])
	            ->setCellValue('A6', $row['address2'])
	            ->setCellValue('A7', $row['city'].",".$row['state']." ".$row['zip'])
	            ->setCellValue('A8', $row['country'])
	            ->setCellValue('A12', 'Account Information')
	            ->setCellValue('A13', 'Affiliate ID')
	            ->setCellValue('B13', $row['affiliate_id'])
	            ->setCellValue('A14', 'Username')
	            ->setCellValue('B14', $row['username'])
	            ->setCellValue('A15', 'Phone Number')
	            ->setCellValue('B15', $row['phone'])
	            ->setCellValue('A17', 'Generated Date')
	            ->setCellValue('B17', date('m/d/Y'))
	            ->setCellValue('A18', 'Commission Start Date')
	            ->setCellValue('B18', date('m/d/Y',strtotime($row['start'])))
	            ->setCellValue('A19', 'Commission End Date')
	            ->setCellValue('B19', date('m/d/Y',strtotime($row['end'])))
	            ;

			// Bold the cells
			$styleArray = array('font' => array('bold' => true));
			$this->phpexcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
			$this->phpexcel->getActiveSheet()->getStyle('A12')->applyFromArray($styleArray);
			$this->phpexcel->getActiveSheet()->getStyle('A17:F17')->applyFromArray($styleArray);


			// iterate all offers for this affiliate
			$cell 	= 21;
			$total 	= 0;
			foreach ($row['offers'] AS $key => $row2):

				// Add some data
				$this->phpexcel->setActiveSheetIndex($sheet)
				            ->setCellValue('A'.$cell, $row2['title'])

				            // gross commission
				            ->setCellValue('A'.($cell + 1), 'Sales Counts')
				            ->setCellValue('B'.($cell + 1), $row2['sales_counts'])

				            // gross commission
				            ->setCellValue('A'.($cell + 2), 'Refund Counts')
				            ->setCellValue('B'.($cell + 2), $row2['refund_counts'])

				            /*
				            // gross commission
				            ->setCellValue('A'.($cell + 3), 'Chargebacks')
				            ->setCellValue('B'.($cell + 3), '0.00')
				            ->setCellValue('C'.($cell + 3), '0')
							*/

				            // gross commission
				            ->setCellValue('A'.($cell + 4), 'Remaining Sales')
				            ->setCellValue('B'.($cell + 4), (($row2['sales_counts'] - $row2['refund_counts'])).' @ $'.$row['pay_amount'].' CPA')
				            ->setCellValue('C'.($cell + 4), '$'.($row2['total_commission']));

				// bold the offer title & total commission
				$this->phpexcel->getActiveSheet()->getStyle('A'.$cell)->applyFromArray($styleArray);
				$this->phpexcel->getActiveSheet()->getStyle('C'.($cell + 4))->applyFromArray($styleArray);

				// increment cell
				$cell = ($cell + 6);

				// add total
				$total 	= ($total + $row2['total_commission']);

			endforeach;

			// add total commission field
			$cell = ($cell + 2);
			$this->phpexcel->setActiveSheetIndex($sheet)
							            ->setCellValue('A'.$cell, 'Total Net Commissions')
							            ->setCellValue('B'.$cell, $total);

			// bold the net commission row
			$this->phpexcel->getActiveSheet()->getStyle('A'.$cell.':B'.$cell)->applyFromArray($styleArray);

			// Rename sheet
			$this->phpexcel->getActiveSheet()->setTitle($row['affiliate_id']);

			// increment sheet counter
			$sheet++;

		endforeach;

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->phpexcel->setActiveSheetIndex(0);
		
		// write the file
		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
		$objWriter->save(CUSTOM_PATH.'modules/commissions/statements/'.$filename);

		return TRUE;
	}

	private function _cpa($filename=FALSE,$data=array())
	{
		if ( ! $filename OR empty($data))
			return FALSE;

		// Set properties
		$this->phpexcel->getProperties()
			->setCreator("AnaMarie Kachovec")
		 	->setLastModifiedBy("AnaMarie Kachovec")
		 	->setTitle("Affiliate Commission Report")
		 	->setSubject("Affiliate Commission Report")
			->setDescription("Affiliate Commission Report")
			->setKeywords("Affiliate Commission Report")
			->setCategory("Affiliate");

		// iterate through affiliates (create new excel sheet for each sheet)
		$sheet	= 0;
		foreach ($data AS $affiliate_id => $row):

			// create new sheet
			if ($sheet != 0) $this->phpexcel->createSheet();

			// set active worksheet
			$this->phpexcel->setActiveSheetIndex($sheet)
				->setCellValue('A1', 'Affiliate Information')
	            ->setCellValue('A2', $row['name'])
	            ->setCellValue('A3', $row['email'])
	            ->setCellValue('A4', $row['company'])
	            ->setCellValue('A5', $row['address'])
	            ->setCellValue('A6', $row['address2'])
	            ->setCellValue('A7', $row['city'].",".$row['state']." ".$row['zip'])
	            ->setCellValue('A8', $row['country'])
	            ->setCellValue('A12', 'Account Information')
	            ->setCellValue('A13', 'Affiliate ID')
	            ->setCellValue('B13', $row['affiliate_id'])
	            ->setCellValue('A14', 'Username')
	            ->setCellValue('B14', $row['username'])
	            ->setCellValue('A15', 'Phone Number')
	            ->setCellValue('B15', $row['phone'])
	            ->setCellValue('A17', 'Generated Date')
	            ->setCellValue('B17', date('m/d/Y'))
	            ->setCellValue('A18', 'Commission Start Date')
	            ->setCellValue('B18', date('m/d/Y',strtotime($row['start'])))
	            ->setCellValue('A19', 'Commission End Date')
	            ->setCellValue('B19', date('m/d/Y',strtotime($row['end'])))
	            ;

			// Bold the cells
			$styleArray = array('font' => array('bold' => true));
			$this->phpexcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
			$this->phpexcel->getActiveSheet()->getStyle('A12')->applyFromArray($styleArray);
			$this->phpexcel->getActiveSheet()->getStyle('A17:F17')->applyFromArray($styleArray);


			// iterate all offers for this affiliate
			$cell 	= 21;
			$total 	= 0;
			foreach ($row['offers'] AS $key => $row2):

				// Add some data
				$this->phpexcel->setActiveSheetIndex($sheet)
				            ->setCellValue('A'.$cell, $row2['title'])

				            // gross commission
				            ->setCellValue('A'.($cell + 1), (($row2['sales_counts'])).' @ $'.$row['pay_amount'].' CPA')
				            ->setCellValue('B'.($cell + 1), '$'.($row2['total_commission']));


				// bold the offer title & total commission
				$this->phpexcel->getActiveSheet()->getStyle('A'.$cell)->applyFromArray($styleArray);
				$this->phpexcel->getActiveSheet()->getStyle('B'.($cell + 1))->applyFromArray($styleArray);

				// increment cell
				$cell = ($cell + 6);

				// add total
				$total 	= ($total + $row2['total_commission']);

			endforeach;

			// add total commission field
			$cell = ($cell + 2);
			$this->phpexcel->setActiveSheetIndex($sheet)
							            ->setCellValue('A'.$cell, 'Total Net Commissions')
							            ->setCellValue('B'.$cell, $total);

			// bold the net commission row
			$this->phpexcel->getActiveSheet()->getStyle('A'.$cell.':B'.$cell)->applyFromArray($styleArray);

			// Rename sheet
			$this->phpexcel->getActiveSheet()->setTitle($row['affiliate_id']);

			// increment sheet counter
			$sheet++;

		endforeach;

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->phpexcel->setActiveSheetIndex(0);
		
		// write the file
		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
		$objWriter->save(CUSTOM_PATH.'modules/commissions/statements/'.$filename);

		return TRUE;
	}
	private function _errors($filename=FALSE,$data=array())
	{
		if ( ! $filename OR empty($data))
			return FALSE;

		// Set properties
		$this->phpexcel->getProperties()
			->setCreator("AnaMarie Kachovec")
		 	->setLastModifiedBy("AnaMarie Kachovec")
		 	->setTitle("Affiliate Commission Report")
		 	->setSubject("Affiliate Commission Report")
			->setDescription("Affiliate Commission Report")
			->setKeywords("Affiliate Commission Report")
			->setCategory("Affiliate");

		// iterate through affiliates (create new excel sheet for each sheet)
		$sheet	= 1;
		// create new sheet
		$this->phpexcel->createSheet();
		
		foreach ($data AS $affiliate_id => $row):

			// set active worksheet
			$this->phpexcel->setActiveSheetIndex(0)
				->setCellValue('A'.$sheet, $row['affiliate_id'].":".$row['error']);
			// increment sheet counter
			$sheet++;

		endforeach;
		// Rename sheet
		$this->phpexcel->getActiveSheet()->setTitle('errors');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->phpexcel->setActiveSheetIndex(0);
		
		// write the file
		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
		$objWriter->save(CUSTOM_PATH.'modules/commissions/statements/'.$filename);

		return TRUE;
	}
}