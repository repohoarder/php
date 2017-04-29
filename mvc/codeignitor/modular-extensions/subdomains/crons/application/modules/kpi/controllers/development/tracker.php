<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tracker extends MX_Controller 
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
		$message 	= 'Development Time Tracking  ('.$start.' - '.$end.')

		';

		// grab time tracking ratios
		$ratios 	= $this->platform->post('time/statistic/ratios/'.$start.'/'.$end);

		// error handling
		if ( ! $ratios['success'] OR empty($ratios['data']))
			show_error('Error grabbing data.');

		// add ot message
		$message 	.= '
% of time worked on each brand:

		';

		// iterate through brands
		foreach ($ratios['data']['brands'] AS $key => $value):

			// add the ratio to the message
			$message 	.= '
'.
$key.': '.($value['ratio'] * 100).'%
';

		endforeach;

		// add ot message
		$message 	.= '
% of time worked on each project type:

		';

		// iterate through brands
		foreach ($ratios['data']['types'] AS $key => $value):

			// add the ratio to the message
			$message 	.= '
'.
$key.': '.($value['ratio'] * 100).'%
';

		endforeach;

		// add to message
		$message .= '

Message sent from: http://crons.brainhost.com/kpi/development/tracker/daily';

		// send email
		mail('thompson2091@gmail.com','Development KPI\'s - '.$start.' thru '.$end, $message);
	}

	public function display($start=FALSE,$end=FALSE)
	{
		// initialize variables
		$start 		= ( ! $start)? 	date('Y-m-d', strtotime('-1 days')): $start;
		$end 		= ( ! $end)? 	date('Y-m-d', strtotime('-1 days')): $end;

		// grab time tracking ratios
		$ratios 	= $this->platform->post('time/statistic/ratios/'.$start.'/'.$end);

		$this->debug->show($ratios['data'],true);
	}

}