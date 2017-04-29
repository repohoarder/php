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
<h1>Statistics | Sales Funnel</h1>
<!-- Sales Statistics view ************************************************************************************* -->
<section id="pnl-accordion">
	<h2>Sales Funnel Statistics</h2>
	<div class="module s-sales">

		<table id="pnl-basic-stats">
			<thead>
				<tr>
					<th width="30%">Page</th>
					<th width="15%">Visitors</th>
					<th width="20%">Revenue</th>
					<th width="20%">Conv. %</th>
					<th width="15%">$ EPC</th>
					<!-- <th>Refunds</th> -->
				</tr>
			</thead>
			<tbody>
            <?php foreach ($stats as $key => $value) : ?>

            	<?php 
            	// don't show master stats
            	if ($key != 'master'):
            	?>
	                <tr>
						<td width="30%"><?php echo @$value['name']; ?></td>
						<td width="15%"><?php echo @$value['hits']['unique']; ?></td>
						<td width="20%"><?php echo @$value['revenue']; ?></td>
						<td width="20%"><?php echo @number_format($value['cr'],0); ?>%</td>
						<td width="15%"><?php echo @$value['epc']; ?></td>
				   </tr>
			   <?php 
			   endif; 	// end not showing master array
			  	?>
			<?php endforeach; ?>
			</tbody>
		</table>
		<form action="#" method="post">
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
</section>
