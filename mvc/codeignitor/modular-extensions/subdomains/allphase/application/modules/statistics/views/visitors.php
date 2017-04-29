<div id="visitors_statistics" class="graph_form">

	<?php if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>'; ?>
	<h1>Customer Reporting | Visitor Statistics</h1>

	<!-- Sales Statistics view ************************************************************************************* -->
	<section class="pnl-accordion-class">

		<h2>Visitor Statistics</h2>
		<div class="module s-visitor">
			<table id="pnl-visitor-basic-stats">
				<thead>
					<tr>
						<th></th>
						<th>Date</th>
						<th>Hits</th>
						<th>Visitors</th>
						<th>Conversion</th>
					</tr>
				</thead>
				<tbody>
	                 <?php
	                  foreach ($visitorreport as $report) : ?>
	                        
	                    <tr>
	                        <th><?php echo $report['header'];?> </th>
	                        <td><?php echo $report['rundate'];?></td>
	                        <td><?php echo $report['visits'];?></td>
	                        <td><?php echo $report['visitors'];?></td>
	                        <td><?php echo ($report['conversion'] * 100);?>%</td>
			   			</tr>
	                 <?php endforeach; ?>
				</tbody>
			</table>
			<form action="#" method="post">
				
				<div id="pnl-visitor-custom-date" class="pnl-custom-cl-stats" style="display:block;">
					<span>
						<label for="txtCustomFrom">From Date</label>
						<input type="text" name="txtCustomFrom" id="startdate" class="date" placeholder="<?php echo date("m/d/Y"); ?>" />
					</span>
					<span>
						<label for="txtCustomTo">To Date</label>
						<input type="text" name="txtCustomTo" id="enddate" class="date" placeholder="<?php echo date("m/d/Y"); ?>" />
					</span>
					<input type="submit" class="button" value="Show Data" />
				</div>
			</form>
			<div id="pnl-visitor-stats"  class="pnl-custom-cl-stats">
				<table>
					<thead>
						<tr>
							<th>From</th>
							<th>To</th>
							<th>Hits</th>
							<th>Visitors</th>
	                        <th>Conversion</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="visitors_start_date">&nbsp;</th>
							<td id="visitors_end_date">&nbsp;</td>
							<td id="visitors_visits">&nbsp;</td>
							<td id="visitors_visitors">&nbsp;</td>
							<td id="visitors_conversion">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<?php 

	echo Modules::run('statistics/graphs/visitors', $partner_id); ?>

</div>