<?php

class Sales_team_rev extends MX_Controller {

	function __construct()
	{

		$this->load->config('brands');

	}

	function index($brand = 'all_brands')
	{
		
		$switch_brands = $this->config->item('brands_switch');

		if ($brand != 'all_brands'):

			if (array_key_exists($brand, $switch_brands)):

				$brand = $switch_brands[$brand];

			endif;

		endif;

			
		// set start and end date
			
			$params = array(
				'start_date' => ($this->input->post('start_date') ? $this->input->post('start_date') : date('Y-m-d',strtotime('-7 days'))),
				'end_date'   => ($this->input->post('end_date') ? $this->input->post('end_date') : date('Y-m-d')),
			);
		
		// BRANDS INPUT
		
			// ALL BRANDS OR ONE BRAND ???	
			
			$show_brands = $this->config->item('brandsdb');
	
			if ($brand !== 'all_brands'):
	
				$show_brands = array(
					$brand => $show_brands[$brand]
				);
	
			endif;

		// BRANDS PLATFORM CALL

			$brands = array(); // key = db, val = name
	
			foreach ($show_brands as $key=>$val):
		
				$params['brand'] = $key;
								
				$brands[$val] = $this->platform->post('crm/reports/sales/sales_team_rev_revenues', $params);
	
						
			endforeach;

		// RECONSTRUCT THE RESPONSE	

			$stats = array();
	
			foreach ($brands as $user=>$result) {
				
				/*
						
				$res = array();

				foreach($result['data'] as $lvl1) {
					foreach($lvl1 as $item) {
						$res[] = $item;
					}
				}
			
				$result['data'] = $res;
				
				$stats[$user] = $result['data']; 
				
				*/
				
				$stats = $result['data'];
			
			}
			
			if (count($stats) < 1) {
				$sucess = 0;
			
			} else {
				$success = 1;
			
			}

			$response = array(
				'success' => $success,
				'error'   => array(),
				'data'    => $stats
			);
			
			if ( ! $response || ! array_key_exists('success',$response) || ! $response['success']):
	
				return;
	
			endif;
			
			$rdata=$response['data'];
			################################
			## Begin ugly-ass empty date fix
			#################################

			$this->load->library('duct_tape');
			$rdata = $this->duct_tape->fix_series_gaps($rdata);

			################################
			## End god-awful hack
			###############################
			
			$data['title']               = 'Sales Team Revenue';
			$data['subtitle']            = ucwords(str_replace('_',' ',$brand) .' - Click to drill down');
			
			$data['label_x']             = 'Last 7 Days';
			$data['label_y']             = 'Dollars';
			
			$data['categories_x']        = array();
			
			$data['label_column_format'] = "'$'+Highcharts.numberFormat(this.y,2)";
			
			$reformatted  = array();
			$dates        = array();
			$all_agents   = array();
	
			foreach ($rdata as $agent_id => $sales):
	
				// Sometimes, agents enter multiple IDs into meta for split commissions
				// Assume they're one digit (Nena said so) and remove spaces
				$agents = array_filter(str_split(str_replace(' ','',$agent_id)));
				$num_agents = count($agents);
	
				// Loop each sale for the given agents -- need to reformat for individual IDs
				foreach ($sales as $sale):
	
					// Divide commission among the number of agents
					$split_commission = round($sale['amount'] / $num_agents,2);
	
					// Loop all agents for the current sale (could be 1)
					foreach ($agents as $agent):
	
						// Store distinct agent ID for later -- need to make sure all loops have all dates
						$all_agents[$agent] = $agent;
	
						// Store distinct dates for later
						$dates[$sale['year'].$sale['month'].$sale['day']] = array(
							'year' => $sale['year'],
							'month' => $sale['month'],
							'day' => $sale['day']
						);
	
						// Store sale in a new array
						// Make sure this agent doesn't have sales listed for the current date already
						if ( ! isset($reformatted[$agent][$sale['year'].$sale['month'].$sale['day']])):
	
							$reformatted[$agent][$sale['year'].$sale['month'].$sale['day']] = array(
								'amount' => $split_commission,
								'year' => $sale['year'],
								'month' => $sale['month'],
								'day' => $sale['day']
							);
	
						else:
	
							// If this agent already has a sale, add the current sale amount to it
							$reformatted[$agent][$sale['year'].$sale['month'].$sale['day']]['amount'] += $split_commission;
	
						endif;
	
					endforeach;
	
				endforeach;
	
			endforeach;

		// Loop all unique agents
			
			foreach ($all_agents as $agent):
	
				// Loop unique dates
				foreach ($dates as $date_key => $date_array):
	
					// If this agent doesn't have a sale on the given date, set that date's amount to zero
					if ( ! isset($reformatted[$agent][$date_key])):
	
						$reformatted[$agent][$date_key] = array_merge(
							$date_array,
							array(
								'amount' => 0
							)
						);
	
	
					endif;
	
				endforeach;
	
			endforeach;
	
	
			$date_amounts = array();
			$reps         = array();
	
	
			foreach ($reformatted as $name => $series):
	
				foreach ($series as $series_array):
	
					//$data['categories_x'][] = date("M", mktime(0, 0, 0, $series_array['month'])).' '.$series_array['day'];
					$data['categories_x'][] = date('Y-m-d',mktime(0, 0, 0, $series_array['month'], $series_array['day'],$series_array['year'])); 
	
					$date_amounts[$series_array['year'].$series_array['month'].$series_array['day']][] = $series_array['amount'];
	
				endforeach;			
	
				$reps[] = 'Agent '.$name;
	
			endforeach;
	
	
	
			$all_series = array();
			$test       = array();
			
			$count      = 0;
	
			foreach ($date_amounts as $date=>$amounts):
	
				$test[] = '{
					y: '.array_sum($amounts).',
					color: colors['.$count.'],
					drilldown: {
						name: \'Sales Reps\',
						categories: '.json_encode($reps).',
						data: '.json_encode($amounts).',
						color: colors['.$count.']
					}
				}';
	
	
				$all_series[] = array(
					'y' => array_sum($amounts),
					'color' => 'colors['.$count.']',
					'drilldown' => array(
						'name'       => 'Sales Reps',
						'categories' => $reps,
						'data'       => $amounts,
						'color'      => 'colors['.$count.']'
					)
				);
	
				$count ++;
	
			endforeach;
	
			//$data['label_x_format'] = "hc_readable_date(this.value)";
			
			$data['series'] = $all_series;
			$data['test_series'] = $test;
			
			// REMOVE DUPLICATE ENTRIES FOR DATES ON X CATEGORIES
			$data['categories_x'] = array_map("unserialize", array_unique(array_map("serialize", $data['categories_x'])));
			sort($data['categories_x']);
	
			$this->load->view('highcharts/column_drilldown', $data);

	}


}