<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affiliate extends MX_Controller 
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * This method grabs daily affiliate KPI's and emails them to Jessica
	 * @return [type] [description]
	 */
	public function daily($start=FALSE,$end=FALSE)
	{
		// initialize variables
		$start 		= ( ! $start)? 	date('Y-m-d', strtotime('-1 days')): $start;
		$end 		= ( ! $end)? 	date('Y-m-d', strtotime('-1 days')): $end;
		/*$message 	= 'Affiliate KPI for '.$start.' - '.$end.'

		';*/
		
		$this->output->set_content_type('text/csv');
		header('Content-Disposition: attachment; filename="Derr-KPI_'.$start.'_'.$end.'.csv"');

		// create array of brands to grab data for
		$brands 	= array(
			'Brain Host'		=> 'brainhost',
			'Purely Hosting'	=> 'purelyhosting',
			'Brain Host Brazil'	=> 'brazil_affiliate'
		);
		
		echo 'Brand, Clicks, Sales, Revenue, Conversion, Average Order, Activated, Offers';

		// iterate through brands
		foreach ($brands AS $brand => $domain):

			echo "\n",$brand;
			
			// grab clicks
			$clicks 	= $this->_data($domain,'affiliate_software/kpi/click/count/'.$start.'/'.$end.'/'.$domain);
			echo ',',$clicks;
			// grab sales #
			$sales 		= $this->_data($domain,'affiliate_software/kpi/sale/count/'.$start.'/'.$end.'/'.$domain);
			echo ',',$sales;
			// grab sales $
			$revenue 	= $this->_data($domain,'affiliate_software/kpi/sale/revenue/'.$start.'/'.$end.'/'.$domain);
			echo ',',$revenue;
			// grab conversion %
			$conversion = $this->_data($domain,'affiliate_software/kpi/sale/conversion/'.$start.'/'.$end.'/'.$domain);
			echo ',',$conversion;
			// grab avg order
			$avg_order	= $this->_data($domain,'affiliate_software/kpi/sale/average/'.$start.'/'.$end.'/'.$domain);
			echo ',',$avg_order;
			// grab # affiliates activated
			$activated 	= $this->_data($domain,'affiliate_software/kpi/affiliate/activated/'.$start.'/'.$end.'/'.$domain);
			echo ',',$activated;
			// grab # offers activated
			$offers 	= $this->_data($domain,'affiliate_software/kpi/affiliate/offers/'.$start.'/'.$end.'/'.$domain);
			echo ',',$offers;
			// create email message
			/*$message .= $brand.'

Affiliate Clicks: 		'.$clicks.'
Affiliate Sales:		'.$sales.'
Affiliate Conversion:  	'.$conversion.'%
Affiliate Sale Revenue: $'.$revenue.'
Average Order Size: 	$'.$avg_order.'
Affiliates Activated: 	'.$activated.'
Offers Activated: 		'.$offers.'


			';*/

		endforeach;	// end looping through brands

		// add to message
		//$message .= 'Message sent from: http://crons.brainhost.com/kpi/affiliate/daily';

		// send email
		//mail('thompson2091@gmail.com,jessica.derr@brainhost.com','Affiliate KPI\'s', $message);
	}

	private function _data($brand,$api,$post=array())
	{
		// run API
		$data 	= $this->platform->post($api,$post);

		// if unsuccessful, return FALSE
		if ( ! $data['success'])	return FALSE;

		// send data variable
		return $data['data'];
	}
}