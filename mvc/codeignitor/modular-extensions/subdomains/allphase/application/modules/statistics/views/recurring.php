

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
	<h1>Recurring Revenue</h1>
	<!-- Sales Statistics view ************************************************************************************* -->
	<section class="pnl-accordion-class">
		<h2>Recurring Revenue</h2>
		<div class="module s-sales">
			<!-- <p> As an All Phase Hosting Partner, all you have to do is promote hosting - we take care of the rest. Below is an outline of the operating costs owed.</p> -->
			<div style="padding:10px;">
			<form method="post" action="">
				Month<br>
			<input type="text" class="datepicker" id="start_date" name="month" placeholder="Month (Format: mm)" value="<?php echo $month;?>"><br>
			Year<br>
			<input type="text" class="datepicker" id="end_date" name="year" placeholder="Year (Format: yyyy)" value="<?php echo $year;?>"><br>
			Plan<br>
			<select name="plan_id"><option value=''>Select Plan</option>
				<?php foreach ($plans as $slug) :
					$c = $slug == $default_plan ? ' selected="selected"' : '';
					echo "<option value='$slug'$c>$slug</option>";
				endforeach;
				?>

			</select>
		<br><br><input type="hidden" name="brand" value="<?php echo $sel_brand;?>">
		<input type="submit" name="getresults" value="Get Report">
		</form>
			</div>
			<table id="pnl-basic-stats">
				<thead>
					<tr class="nosort" role="row">
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th title="period">Period</th>
						<th title="Number of signups">#Signups</th>
						<th title="Initial Revenue">$Revenue</th>
						<th title="$ Amount of refunds">$Refund</th>
						<th title="Rebilling count">#Rebill</th>
						<th title="Rebilling revenue">$Rebill</th>
						<th title="$ Amount of refunds">$Refund</th>
						<th title="clients still active">#Active</th>
					</tr>
				</thead>
				<tbody>
<?php 
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
	?>
				<tr>
					<!-- <td><input type="checkbox" name="row_sel" class="row_sel" /></td> -->
					<td><?php echo $period; ?></td>
					<td><?php echo $value['count']; ?></td>
					<td><?php echo number_format($value['revenue'],2); ?></td>
					<td><?php echo $value['refunds']; ?></td>
					<td><?php echo $value['rec_count']; ?></td>
					<td><?php echo number_format($value['rec_revenue'],2); ?></td>
					<td><?php echo number_format($value['rec_refunds'],2); ?></td>
					<td><?php echo $value['active']; ?></td>
				</tr>
<?php
endforeach;
?>
				<tr>
					<!-- <td><input type="checkbox" name="row_sel" class="row_sel" /></td> -->
					<td>Totals</td>
					<td><?php echo $tcount; ?></td>
					<td><?php echo number_format($trev,2); ?></td>
					<td><?php echo $ref; ?></td>
					<td><?php echo $reccnt; ?></td>
					<td><?php echo number_format($recrev,2); ?></td>
					<td><?php echo number_format($recref,2); ?></td>
					<td><?php echo $ac; ?></td>
				</tr>
				</tbody>
		 </table>
			
		</div>
	</section>

	<?php //echo Modules::run('statistics/graphs/operatingcosts', $partner_id); ?>

