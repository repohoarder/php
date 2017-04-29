<?php
	if (!defined('BASEPATH'))
	{
		exit('No direct script access allowed');
	}

	class Support_Report extends MX_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
		}

		public function index()
		{
			return $this->report();
		}
		
		private function getFull($brand='brainhost')
		{
			$params=array
			(
				'start_date'=>date('Y-m-d'),
				'end_date'=>date('Y-m-d'),
				'brand'=>$brand
			);
			
			$return['total']=$this->platform->post
			(
				'ubersmith/reports/tickets/total/'.$brand, $params
			);
			$return['total']=$return['total']['data'][$brand][0]['amount'];
			
			$return['touched']=$this->platform->post
			(
				'ubersmith/reports/tickets/touched/'.$brand, $params
			);
			$return['touched']=$return['touched']['data'][$brand][0]['amount'];
			
			$return['closed']=$this->platform->post
			(
				'ubersmith/reports/tickets/closed/'.$brand, $params
			);
			$return['closed']=$return['closed']['data'][$brand][0]['amount'];
			
			if ($brand!='brazil_orders')
			{
				$return['reps']=$this->platform->post
				(
					'ubersmith/reports/sales_team_rev/total', $params
				);
				$return['reps']=$return['reps']['data'][0]['amount'];
			}
				
			return $return;
		}
		
		public function report()
		{
			$this->load->library('email');
			
			$results=array();
			$results['bh']=$this->getFull('brainhost');
			$results['ph']=$this->getFull('purelyhosting');
			$results['br']=$this->getFull('brazil_orders');
			
			$text='';
			$text.="Brain Host\n=====\n";
			$text.='Total Tickets: '.$results['bh']['total']."\n";
			$text.='Tickets Worked On: '.$results['bh']['touched']."\n";
			$text.='Tickets Closed: '.$results['bh']['closed']."\n";
			$text.='Rep Sales: $'.$results['bh']['reps']."\n";
			$text.="\n";
			
			$text.="Purely Hosting\n=====\n";
			$text.='Total Tickets: '.$results['ph']['total']."\n";
			$text.='Tickets Worked On: '.$results['ph']['touched']."\n";
			$text.='Tickets Closed: '.$results['ph']['closed']."\n";
			$text.='Rep Sales: $'.$results['ph']['reps']."\n";
			$text.="\n";
			
			$text.="Brain Host Brazil\n=====\n";
			$text.='Total Tickets: '.$results['br']['total']."\n";
			$text.='Tickets Worked On: '.$results['br']['touched']."\n";
			$text.='Tickets Closed: '.$results['br']['closed']."\n";
			$text.="\n";
			
			$text.="\n";
			$text.='Total Rep Sales: $'.($results['bh']['reps']+$results['ph']['reps'])."\n";
			
			//echo $text;
			$this->email->to('ryan.niddel@brainhost.com');
			$this->email->cc('nena.abdullah@brainhost.com');
			$this->email->bcc('ben.young@brainhost.com');
			$this->email->from('platform@brainhost.com');
			$this->email->subject('Support Report');
			$this->email->message($text);
			$this->email->send();
			
			return true;
		}
	}
?>