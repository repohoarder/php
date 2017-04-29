<div id="tickets_statistics" class="graph_form">

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
	<h1>Customer Reporting | Tickets Statistics</h1>
	<!-- Sales Statistics view ************************************************************************************* -->
	<section class="pnl-accordion-class">
		<h2>Tickets Statistics</h2>
		<div class="module s-sales">

			<table id="pnl-basic-stats">
				<thead>
					<tr>
						<th style="width: 30%;">Period</th>
						<th style="width: 35%;">Tickets</th>
						<th style="width: 35%;">Replies</th>
					</tr>
				</thead>
				<tbody>
	            <?php foreach ($tickets as $report) : ?>
	                
	                <tr>
	                    <th width="30%"><?php echo $report['period'];?> </th>
	                    <td width="35%"><?php echo $report['tickets'];?></td>
	                    <td width="35%"><?php echo $report['replies'];?></td>
				   </tr>
	                     <?php endforeach; ?>
				</tbody>
			</table>
			<form action="#" method="post">
				<?php /*<a href="#" id="btn-sales-custom-date">Custom Date Range</a> */ ?>
				<div id="pnl-tickets-custom-date" style="display:block">
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
			<div id="pnl-custom-stats">
				<table>
					<thead>
						<tr>
							<th>From</th>
							<th>To</th>
							<th>Tickets</th>
							<th>Replies</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="result_start_date">&nbsp;</th>
							<td id="result_end_date">&nbsp;</td>
							<td id="result_tickets">&nbsp;</td>
							<td id="result_replies">&nbsp;</td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>

	</section>
	
<?php 

	echo Modules::run('statistics/graphs/tickets', $partner_id); ?>

</div>