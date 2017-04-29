<?php

// partners who are really only affiliates
$affiliate_partners 	= array(
	157,
	173,
	175,
	199,
	206,
	200,
	201,
	207,
	205,
	208,
	223,
	//225,
	239,
	//251,
	//169
);

// set custom CPA amounts
$custom_cpa 			= array(
	'157'	=> 150,
	'173'	=> 225,
	'175'	=> 150,
	'199'	=> 110,
	'206'	=> 110,
	'200'	=> 110,
	'201'	=> 110,
	'207'	=> 120,
	'205'	=> 110,
	'208'	=> 250,
	'223'	=> 165,
	'239'	=> 130,
	'169'	=> 50,
	'225'	=> 80,
	'251'	=> 110
);

?>


<div id="sales_statistics" class="graph_form">

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


	<?php if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>'; ?>
	<h1>Customer Reporting | Sales Statistics</h1>
	<!-- Sales Statistics view ************************************************************************************* -->
	<section class="pnl-accordion-class">
		<h2>Sales Statistics</h2>
		<div class="module s-sales">

			<table id="pnl-basic-stats">
				<thead>
					<tr>
						<th style="width: 20%;">Period</th>
						<th style="width: 20%;">Date</th>
						<th style="width: 30%;">Sales</th>

	                    <?php
	                   	## HACK 
	                   	$partner 	= $this->session->userdata('partner');
	                   	$revenue 	= 'Revenue';
	                   	if (in_array($partner['id'],$affiliate_partners))
	                   		$revenue = 'Commissions';
	                   	?>

						<th style="width: 30%;"><?php echo $revenue; ?></th>
						<!-- <th>Refunds</th> -->
					</tr>
				</thead>
				<tbody>
	            <?php foreach ($salesreport as $report) : ?>
	                
	                <tr>
	                    <th width="20%"><?php echo $report['header'];?> </th>
	                    <td width="20%"><?php echo $report['rundate'];?></td>
	                    <td width="30%"><?php echo $report['count'];?></td>

	                    <?php
	                   	## HACK 
	                   	$partner 	= $this->session->userdata('partner');
	                   	$total 		= $report['total'];

	                   	// set custom CPA 
	                   	if (isset($custom_cpa[$partner['id']])):

	                   		// set new total
	                   		$total = ($report['count'] * $custom_cpa[$partner['id']]);

	                   	endif;
	                   	?>

	                    <td width="30%">$<?php echo $total;?></td>
	                     <!-- <td>$<?php echo $report['refunds'];?></td> -->
				   </tr>
	                     <?php endforeach; ?>
					
	                                <!--
					<tr>
						<th>Since last payout</th>
						<td>10/12/12</td>
						<td>24</td>
						<td>$1,734.11</td>
					</tr> -->
				</tbody>
			</table>

            <?php
           	## HACK 
           	$partner 	= $this->session->userdata('partner');
           	if ( ! in_array($partner['id'],$affiliate_partners)):
           	?>

			<form action="#" method="post">
				<?php /*<a href="#" id="btn-sales-custom-date">Custom Date Range</a> */ ?>
				<div id="pnl-sales-custom-date" style="display:block">
					<span>
						<label for="txtCustomFrom">From Date</label>
						<input type="text" name="txtCustomFrom" id="txtCustomFrom" class="date" placeholder="<?php echo date("m/d/Y"); ?>" />
					</span>
					<span>
						<label for="txtCustomTo">To Date</label>
						<input type="text" name="txtCustomTo" id="txtCustomTo" class="date" placeholder="<?php echo date("m/d/Y"); ?>" />
					</span>
					<input type="submit" class="button" value="Show Data" />
				</div>
			</form>


			<?php
			endif;
			?>

			<div id="pnl-custom-stats">
				<table>
					<thead>
						<tr>
							<th>From</th>
							<th>To</th>
							<th>Sales</th>
							<th>Revenue</th>
	                        <th>Refunds</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="result_start_date">&nbsp;</th>
							<td id="result_end_date">&nbsp;</td>
							<td id="result_count">&nbsp;</td>
							<td id="result_total">&nbsp;</td>
	                        <td id="result_refunds">&nbsp;</td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>

	<?php /*

	<!-- Visitor Statistics view ****************************************************************************** --

		<h2>Visitor Statistics</h2>
		<div class="module s-visitor">

		</div>

	<!-- Last Payout view ************************************************************************************* --

		<h2>Last Payout</h2>
		<div class="module s-payout">
			<div class="pnl-payout-data">
				<form action="#" method="post">
					<h3>$X,XXX.XX</h3>
					<input type="submit" class="button" id="btn-payout-less-data" value="Less Data" />
				</form>
			</div>
			<table>
				<thead>
					<tr>
						<th>Date</th>
						<th>Range</th>
						<th>Sales</th>
						<th>Commission</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>8/23/12</th>
						<td>6/1/12 - 7/1/12</td>
						<td>501</td>
						<td>XX.XX%</td>
					</tr>
				</tbody>
			</table>
		</div>

	<!-- Estimated Recurring Revenue view ********************************************************************* --

		<h2>Estimated Recurring Revenue</h2>
		<div class="module s-revenue">
			<table>
				<thead>
					<tr>
						<th>Plan</th>
						<th>Estimated Rebill</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Monthly</th>
						<td>$XXX.XX</td>
					</tr>
					<tr>
						<th>6 Month</th>
						<td>$XXX.XX</td>
					</tr>
					<tr>
						<th>Yearly</th>
						<td>$XXX.XX</td>
					</tr>
				</tbody>
			</table>
			<a href="#" class="button">How Is This Calculated?</a>
			<a href="#" class="button">Show Plans</a>
		</div>
		<!-- end estimated recurring -->
	*/ ?>

	</section>


    <?php
   	## HACK 
   	$partner 	= $this->session->userdata('partner');
   	if ( ! in_array($partner['id'],$affiliate_partners)):
   	?>

	<?php echo Modules::run('statistics/graphs/revenue', $partner_id); ?>


	<?php
	endif;
	?>

</div>