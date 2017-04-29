

	<!-- Section specific Javascript -->

	<script>
		$(function() {
			$( "#txtCustomFrom" ).datepicker();
			$( "#txtCustomTo" ).datepicker();
	             $("#export").click(function(){
	                 $("#exportid").val('1');
	                 $("#submit").click();
	                
	             })
		});
	</script>


	<?php 
	if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>'; ?>
	<h1>Operating Costs Breakdown</h1>
	<!-- Sales Statistics view ************************************************************************************* -->
	<section class="pnl-accordion-class">
		<h2>Operating Costs Breakdown</h2>
		<div class="module s-sales">
			<!-- <p> As an All Phase Hosting Partner, all you have to do is promote hosting - we take care of the rest. Below is an outline of the operating costs owed.</p> -->
			
			<table id="pnl-basic-stats">
				<thead>
					<tr>
						<th>Period</th>
						<th>Gross</th>
						<th>Net</th>
						<th>Refunds</th>
						<th>Chargebacks</th>
						<th>Product Fees</th>
						<th>Support Fees</th>
						<th>Merchant Reserves</th>
					</tr>
				</thead>
				<tbody>

					<?php
					
					// initialize variables for TODAY
					$revenue 		= ! isset($fees[$today]['revenue'] ) ? '0.00' :  round($fees[$today]['revenue'],2);
					$refunds		= ! isset($refund['fees'][$today]['refund'] ) ? '0.00' :  round($refund['fees'][$today]['refund'],2);
					$chargebacks 	= ! isset($chargeback['fees'][$today]['refund'] ) ? '0.00' :  round($chargeback['fees'][$today]['refund'],2);
					$processing 	= ! isset($fees[$today]['processingfees'] ) ? '0.00' :  round($fees[$today]['processingfees'],2);
					$reserves 		= ! isset($fees[$today]['reserves'] ) ? '0.00' :  round($fees[$today]['reserves'],2);
					$hosting_fees 	= ! isset($breakdown[$today]['HOSTING']['cost'] ) ? '0.00' : round($breakdown[$today]['HOSTING']['cost'],2);
					$domain_fees 	= ! isset($breakdown[$today]['DOMAIN']['cost'] ) ? '0.00' :  round($breakdown[$today]['DOMAIN']['cost'],2);
					$upsell_fees 	= ! isset($breakdown[$today]['UPSELL']['cost'] ) ? '0.00' : round($breakdown[$today]['UPSELL']['cost'],2);
					$call_fee		= ! isset($calls['callfee'][$today]['calls'] ) ? '0.00' : round($calls['callfee'][$today]['calls'],2);
					$tick_fee		= ! isset($tickets['ticketfee'][$today]['replies'])? 0.00 :round($tickets['ticketfee'][$today]['replies'],2);
					$freebies		= ! isset($freebie[$today]['amount'])? 0.00 :round($freebie[$today]['amount'],2);
					$fees_ch = round($processing+$hosting_fees+$domain_fees+$upsell_fees,2);
					$supp = round($call_fee+$tick_fee,2);
					$expenses = round($refunds+$chargebacks+$fees_ch+$reserves+$freebies+$supp,2);
					$net = round($revenue - $expenses,2);
					if($net < 0):
						$net = $net * -1;
						$net = "-".number_format($net,2);
						else:
							$net =number_format($net,2);
					endif;
					?>

					<tr>
						<td>Today</td>
						<td>$<?php echo number_format($revenue ,2); ?></td>
						<td>$<?php echo $net;?></td>
						<td>$<?php echo number_format($refunds ,2); ?></td>
						<td>$<?php echo number_format($chargebacks ,2); ?></td>
						<td>$<?php echo number_format($fees_ch ,2); ?></td>
						<td>$<?php echo number_format($supp,2);?></td>
						<td>$<?php echo number_format($reserves ,2); ?></td>
					</tr>

					<?php
					// initialize variables for YESTERDAY
					$revenue 		= ! isset($fees[$yesterday]['revenue'] ) ? '0.00' :  round($fees[$yesterday]['revenue'],2);
					$refunds		= ! isset($refund['fees'][$yesterday]['refund'] ) ? '0.00' :  round($refund['fees'][$yesterday]['refund'],2);
					$chargebacks 	= ! isset($chargeback['fees'][$yesterday]['refund'] ) ? '0.00' :  round($chargeback['fees'][$yesterday]['refund'],2);
					$processing 	= ! isset($fees[$yesterday]['processingfees'] ) ? '0.00' :  round($fees[$yesterday]['processingfees'],2);
					$reserves 		= ! isset($fees[$yesterday]['reserves'] ) ? '0.00' :  round($fees[$yesterday]['reserves'],2);
					$hosting_fees 	= ! isset($breakdown[$yesterday]['HOSTING']['cost'] ) ? '0.00' : round($breakdown[$yesterday]['HOSTING']['cost'],2);
					$domain_fees 	= ! isset($breakdown[$yesterday]['DOMAIN']['cost'] ) ? '0.00' :  round($breakdown[$yesterday]['DOMAIN']['cost'],2);
					$upsell_fees 	= ! isset($breakdown[$yesterday]['UPSELL']['cost'] ) ? '0.00' : round($breakdown[$yesterday]['UPSELL']['cost'],2);
					$call_fee		= ! isset($calls['callfee'][$yesterday]['calls'] ) ? '0.00' : round($calls['callfee'][$yesterday]['calls'],2);
					$tick_fee		= ! isset($tickets['ticketfee'][$yesterday]['replies'])? 0.00 :round($tickets['ticketfee'][$yesterday]['replies'],2);
					$freebies		= ! isset($freebie[$yesterday]['amount'])? 0.00 :round($freebie[$yesterday]['amount'],2);

					$fees_ch = round($processing+$hosting_fees+$domain_fees+$upsell_fees,2);
					$supp = round($call_fee+$tick_fee,2);
					$expenses = round($refunds+$chargebacks+$fees_ch+$reserves+$freebies+$supp,2);
					$net = round($revenue - $expenses,2);
					if($net < 0):
						$net = $net * -1;
						$net = "-".number_format($net,2);
						else:
							$net =number_format($net,2);
					endif;
					?>					

					<tr>
						<td>Yesterday</td>
						<td>$<?php echo number_format($revenue ,2); ?></td>
						<td>$<?php echo $net;?></td>
						<td>$<?php echo number_format($refunds ,2); ?></td>
						<td>$<?php echo number_format($chargebacks ,2); ?></td>
						<td>$<?php echo number_format($fees_ch ,2); ?></td>
						<td>$<?php echo number_format($supp,2);?></td>
						<td>$<?php echo number_format($reserves ,2); ?></td>
					</tr>

					<?php
					// initialize variables for MTD
					
					$revenue 		= ! isset($fees['total']['revenue'] ) ? '0.00' :  round($fees['total']['revenue'],2);
					$refunds		= ! isset($refund['fees']['total']['refund'] ) ? '0.00' : round($refund['fees']['total']['refund'],2);
					$chargebacks 	= ! isset($chargeback['fees']['total']['refund'] ) ? '0.00' :  round($chargeback['fees']['total']['refund'],2);
					$processing 	= ! isset($fees['total']['processingfees'] ) ? '0.00' :  round($fees['total']['processingfees'],2);
					$reserves 		= ! isset($fees['total']['reserves'] ) ? '0.00' : $fees['total']['reserves'];
					$hosting_fees 	= ! isset($breakdown['total']['HOSTING']['cost'] ) ? '0.00' : round($breakdown['total']['HOSTING']['cost'],2);
					$domain_fees 	= ! isset($breakdown['total']['DOMAIN']['cost'] ) ? '0.00' : round($breakdown['total']['DOMAIN']['cost'],2);
					$upsell_fees 	= ! isset($breakdown['total']['UPSELL']['cost'] ) ? '0.00' : round($breakdown['total']['UPSELL']['cost'],2);
					$call_fee		= ! isset($calls['callfee']['total']['calls'] ) ? '0.00' : round($calls['callfee']['total']['calls'],2);
					$tick_fee		= ! isset($tickets['ticketfee']['total']['replies'])? 0.00 :round($tickets['ticketfee']['total']['replies'],2);
					$freebies		= ! isset($freebie['total']['amount'])? 0.00 :round($freebie['total']['amount'],2);
					
					$fees_ch = round($processing+$hosting_fees+$domain_fees+$upsell_fees,2);
					$supp = round($call_fee+$tick_fee,2);
					$expenses = round($refunds+$chargebacks+$fees_ch+$reserves+$freebies+$supp,2);
					$net = round($revenue - $expenses,2);
					if($net < 0):
						$net = $net * -1;
						$net = "-".number_format($net,2);
						else:
							$net =number_format($net,2);
					endif;
					?>	
					<tr>
						<td>MTD</td>
						<td>$<?php echo number_format($revenue ,2); ?></td>
						<td>$<?php echo $net;?></td>
						<td>$<?php echo number_format($refunds ,2); ?></td>
						<td>$<?php echo number_format($chargebacks ,2); ?></td>
						<td>$<?php echo number_format($fees_ch ,2); ?></td>
						<td>$<?php echo number_format($supp,2);?></td>
						<td>$<?php echo number_format($reserves ,2); ?></td>
					</tr>

				</tbody>
			</table>
			<form action="/operating/breakdown" method="post">
				<?php /*<a href="#" id="btn-sales-custom-date">Custom Date Range</a> */ ?>
				<div id="pnl-sales-custom-date" style="display:block">
					<span>
						<label for="txtCustomFrom">From Date</label>
						<input type="text" name="startdate" id="txtCustomFrom" class="date" placeholder="<?php echo date("m/d/Y"); ?>" />
					</span>
					<span>
						<label for="txtCustomTo">To Date</label>
						<input type="text" name="enddate" id="txtCustomTo" class="date" placeholder="<?php echo date("m/d/Y"); ?>" />
					</span>
					<input type="submit" class="button" value="Show Data" />
				</div>
			</form>
			<?php if( ! empty($range)) : ?>
			<div id="pnl-custom-stats" style="display:block;">
				<table>
					<thead>
					<tr>
						<th>Period</th>
						<th>Gross</th>
						<th> Net</th>
						<th>Refunds</th>
						<th>Chargebacks</th>
						<th>Product Fees</th>
						<th>Support Fees</th>
						<th>Merchant Reserves</th>
					</tr>
					</thead>
					<tbody>
						
							<?php
							// initialize variables for CUSTOM RANGE
							$date_range 	= isset($range['daterange']) ? $range['daterange'] : '';
							$revenue 		= ! isset($range['fees']['total']['revenue'] ) ? '0.00' :  round($range['fees']['total']['revenue'],2);
							$refunds		= ! isset($range['refund']['fees']['total']['refund'] ) ? '0.00' :  round($range['refund']['fees']['total']['refund'],2);
							$chargebacks 	= ! isset($range['chargeback']['fees']['total']['refund'] ) ? '0.00' :  round($range['chargeback']['fees']['total']['refund'],2);
							$processing 	= ! isset($range['fees']['total']['processingfees'] ) ? '0.00' :  $range['fees']['total']['processingfees'];
							$reserves 		= ! isset($range['fees']['total']['reserves'] ) ? '0.00' : $range['fees']['total']['reserves'];
							$hosting_fees 	= ! isset($range['breakdown']['total']['HOSTING']['cost'] ) ? '0.00' : round($range['breakdown']['total']['HOSTING']['cost'],2);
							$domain_fees 	= ! isset($range['breakdown']['total']['DOMAIN']['cost'] ) ? '0.00' : round($range['breakdown']['total']['DOMAIN']['cost'],2);
							$upsell_fees 	= ! isset($range['breakdown']['total']['UPSELL']['cost'] ) ? '0.00' : round($range['breakdown']['total']['UPSELL']['cost'],2);
							$call_fee		= ! isset($range['calls']['callfee']['total']['calls'] ) ? '0.00' : round($range['calls']['callfee']['total']['calls'],2);
							$tick_fee		= ! isset($range['tickets']['ticketfee']['total']['replies'])? 0.00 :round($range['tickets']['ticketfee']['total']['replies'],2);
							$freebies		= ! isset($range['freebie']['total']['amount'])? 0.00 :round($range['freebie']['total']['amount'],2);
						
							//echo"<pre>";print_r($range);
							$fees_ch = round($processing+$hosting_fees+$domain_fees+$upsell_fees,2);
							$supp = round($call_fee+$tick_fee,2);
							$expenses = round($refunds+$chargebacks+$fees_ch+$reserves+$freebies+$supp,2);
							$net = round($revenue - $expenses,2);
							if($net < 0):
								$net = $net * -1;
								$net = "-".number_format($net,2);
								else:
									$net =number_format($net,2);
							endif;
							?>	
							<tr>
								<td><?php echo $date_range; ?></td>
								<td>$<?php echo number_format($revenue ,2); ?></td>
								<td>$<?php echo $net;?></td>
								<td>$<?php echo number_format($refunds ,2); ?></td>
								<td>$<?php echo number_format($chargebacks ,2); ?></td>
								<td>$<?php echo number_format($fees_ch ,2); ?></td>
								<td>$<?php echo number_format($supp,2);?></td>
								<td>$<?php echo number_format($reserves ,2); ?></td>
							</tr>
					</tbody>
				</table>
				</div>
				<?php endif; ?>
			
		</div>


	</section>

	<?php //echo Modules::run('statistics/graphs/operatingcosts', $partner_id); ?>

