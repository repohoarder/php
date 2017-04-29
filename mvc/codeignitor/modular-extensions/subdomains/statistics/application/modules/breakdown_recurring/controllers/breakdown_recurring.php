<?php

class Breakdown_recurring extends MX_Controller {

public function index($brand = 'brainhost'){
		
	    // initialize
		$data = array();
		$data['plans'] = array();
		$merged_data = array();
		//initialize variables
		$brand  = $this->input->post('brand') ? $this->input->post('brand') : $brand;
		$default_plan = $this->input->post('plan_id') ? $this->input->post('plan_id') : 'Web Hosting';
		$month = $this->input->post('month') ?  $this->input->post('month'): date('m',time());
		$year = $this->input->post('year') ?  $this->input->post('year'): date('Y',time());
		
		// set start and end dates
		$start = "$year-$month-01";
		$end = date('Y-m-t',strtotime($start)); // this is a neat feature i just learned. gets the last day of the month...
		
		$params = array(
			'start_date' => $start,
			'end_date'   => $end,
			'plan' => $default_plan
		);
		
		// set brands
		$data['brands'] = array(
				'brainhost'=>'brainhost',
				'purelyhosting' => 'purelyhosting',
				'brazil_orders' => 'brazil_orders'
				);
		
		$sel_brand = $data['brands'][$brand];
		
		// get plans
		$plans = $this->platform->post('crm/reports/getplans/get/'.$sel_brand,array());
		
		// set plans
		if($plans['success']) :
			$data['plans'] = $plans['data'][$sel_brand];
		endif;
		
		// get inital sales
		$initial  = $this->platform->post('crm/reports/renewals/revenue_initial/'.$sel_brand, $params);
		
		if($initial['success']) :
			$merged_data = $initial['data'][$sel_brand];
			//echo"<pre>";print_r($merged_data[24]['ids']);echo"</pre>";
		endif;
		
		$data['skipfilters'] = true;
		// set template variables
		$data['sel_brand'] = $sel_brand;
		$data['default_plan'] = $default_plan;
		$data['month'] = $month;
		$data['year'] = $year;
		$data['report'] = $merged_data;
		
		$this->template->set_layout('no_boxes');

		$this->template->build('breakdown_recurring/revenue',$data);
		
	}
	
	public function export(){
		
		//initialize variables
		$brand  = $this->input->get('brand') ? $this->input->get('brand') : $brand;
		$default_plan = $this->input->get('plan_id') ? $this->input->get('plan_id') : 'Web Hosting';
		$month = $this->input->get('month') ?  $this->input->get('month'): date('m',time());
		$year = $this->input->get('year') ?  $this->input->get('year'): date('Y',time());
		
		// set start and end dates
		$start = "$year-$month-01";
		$end = date('Y-m-t',strtotime($start)); 
		
		$params = array(
			'start_date' => $start,
			'end_date'   => $end,
			'plan' => $default_plan
		);
		
		// set brands
		$data['brands'] = array(
				'brainhost'=>'brainhost',
				'purelyhosting' => 'purelyhosting',
				'brazil_orders' => 'brazil_orders'
				);
		
		$sel_brand = $data['brands'][$brand];
		
		
		// get inital sales
		$initial  = $this->platform->post('crm/reports/renewals/revenue_initial/'.$sel_brand, $params);
		
		// set csv header;
		$csv= '"Period","#Signups","$Revenue","$Refund","#Refunds","%$ Refund Ratio","$# Refund Ratio","#Rebills","$Rebills","#Refunds","$Refunds","%$ Refund Ratio","$# Refund Ratio","Active"'."\n";

		if($initial['success']) :
			
			$report = $initial['data'][$sel_brand];
			
			$tcount =0;$trev=0;$trefcnt=0;$ref=0;$refcnt=0;$reccnt=0;$recrev=0;$recrefcnt=0;$recref=0;$ac=0;

			foreach($report as $period=>$value ): 
				
				$tcount		+= $value['count'];
				$trev		+= $value['revenue'];
				$refcnt		+= $value['refund_cnt'];
				$ref		+= $value['refunds'];
				$reccnt		+= $value['rec_count'];
				$recrev		+= $value['rec_revenue'];
				$recrefcnt  += $value['rec_refcount'];
				$recref		+= $value['rec_refunds'];
				$ac			+= $value['active'];
				$rp1 = $value['revenue'] == 0 ? 0 :round($value['refunds'] / $value['revenue'],2)  * 100;
				$rp2 = $value['count'] == 0 ? 0 : round($value['refund_cnt'] / $value['count'],2)  * 100;
				$rec3 = $value['rec_revenue'] == 0 ? 0 : round($value['rec_refunds'] / $value['rec_revenue'],2)  * 100;
				$rec4 = $value['rec_count'] == 0 ? 0 : round($value['rec_refcount'] / $value['rec_count'],2)  * 100  ;
			   $csv .= '"'.$period.'","'.$value['count'].'","'.number_format($value['revenue'],2).'","'.$value['refund_cnt'].'","'.$value['refunds'].'","'.$rp1 .'","'. $rp2 .'","'.$value['rec_count'].'","'.number_format($value['rec_revenue'],2).'","'.$value['rec_refcount'].'","'.number_format($value['rec_refunds'],2).'","'.$rec3 .'","'.$rec4.'","'.$value['active'].'"'."\n";


			endforeach;
			
			$t1 = $trev == 0 ? 0 :round($ref / $trev,2)  * 100;
			$t2 = $tcount == 0 ? 0 : round($refcnt / $tcount,2)  * 100 ;
			$t4 = $recrev == 0 ? 0 : round($recref / $recrev,2)  * 100;
			$t3 = $reccnt == 0 ? 0:round($recrefcnt / $reccnt,2)  * 100;
			$csv .= '"Totals","'.$tcount.'","'.number_format($trev,2).'","'.$refcnt.'","'.$ref.'","'.$t1 .'","'.$t2.'","'.$reccnt.'","'.number_format($recrev,2).'","'.$recrefcnt.'","'.number_format($recref,2).'","'.$t4 .'","'.$t3 .'","'.$ac.'"'."\n";

			$csv .="$brand Report: $month-$year";
		endif;
		
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=download.csv');
		echo $csv;
		exit();
	}
	
}