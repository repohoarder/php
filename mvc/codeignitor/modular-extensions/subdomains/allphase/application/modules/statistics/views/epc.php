<div id="epc_statistics" class="graph_form">

	<?php if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>'; ?>
	<h1>Customer Reporting | EPC Statistics</h1>

	<!-- Sales Statistics view ************************************************************************************* -->
	<section class="pnl-accordion-class">

		<h2>Earnings-Per-Click (EPC) Statistics</h2>
		<div class="module s-visitor">
			<table id="pnl-visitor-basic-stats">
				<thead>
					<tr>
						<th></th>
						<th>Date</th>
						<th>Visitors</th>
						<th>Revenue</th>
	                    <th>EPC</th>
						<th>Est. EPC</th>
					</tr>
				</thead>
				<tbody>
	                 <?php foreach ($visitorreport as $report) : ?>
	                        
	                    <tr>
	                        <th><?php echo $report['header'];?> </th>
	                        <td><?php echo $report['rundate'];?></td>
	                        <td><?php echo $report['visitors'];?></td>
	                        <td>$<?php echo number_format($report['total'],2);?></td>
	                        <td>$<?php echo number_format($report['epc'],2);?></td>
							 <td>$<?php echo number_format($report['estimated_epc'],2);?></td>
			   			</tr>
	                 <?php endforeach; ?>
				</tbody>
			</table>
			<form action="#" method="post">
				
				<div id="pnl-epc-custom-date" class="pnl-custom-cl-stats" style="display:block;">
					<span>
						<label for="txtCustomFrom">From Date</label>
						<input type="text" name="txtCustomFrom" id="startdateepc" class="date" placeholder="<?php echo date("m/d/Y"); ?>" />
					</span>
					<span>
						<label for="txtCustomTo">To Date</label>
						<input type="text" name="txtCustomTo" id="enddateepc" class="date" placeholder="<?php echo date("m/d/Y"); ?>" />
					</span>
					<input type="submit" class="button" value="Show Data" id="epcbutton"/>
				</div>
			</form>
			<div id="pnl-epc-stats"  class="pnl-custom-cl-stats">
				<table>
					<thead>
						<tr>
							<th>From</th>
							<th>To</th>
							<th>Visitors</th>
							<th>Revenue</th>
	                        <th>EPC</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="epc_start_date">&nbsp;</th>
							<td id="epc_end_date">&nbsp;</td>
							<td id="epc_visitors">&nbsp;</td>
							<td id="epc_total">&nbsp;</td>
	                        <td id="epc_epc">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<?php 

	echo Modules::run('statistics/graphs/epc', $partner_id); ?>

</div>