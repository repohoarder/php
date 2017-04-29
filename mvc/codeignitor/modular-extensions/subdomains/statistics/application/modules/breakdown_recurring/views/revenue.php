<style>
	
	
</style>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<form method="post" action="/breakdown_recurring" name="frm" id="frm">
Change Brand<br>
<select name="brand" onChange="document.frm.submit();">
	<?php foreach ($brands as $b=>$rand) :
		 $c = ($rand == $sel_brand) ? ' selected="selected"' : '';
		$rand = ucfirst(str_replace('_',' ',$rand));
	   
		echo "<option value='$b' $c>$rand</option>";
	endforeach;
	?>
	
</select>
</form>
<br>
<h1><?php echo ucfirst(str_replace("_", " ",$sel_brand));?></h1>
<br>
<form method="post" action="">
	Month<br>
<input type="text" class="datepicker" id="start_date" name="month" placeholder="Month (Format: 01-12)" value="<?php echo $month;?>"><br>
Year<br>
<input type="text" class="datepicker" id="end_date" name="year" placeholder="Year" value="<?php echo $year;?>"><br>
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

<?php
if( ! empty($report) ) : ?>

<br>
<h2 style="font-size: 16px;font-weight: 800;line-height: 16px;margin-bottom:20px;"> 
	Date Range : <?php echo date('F',strtotime("$year-$month-01")) . " " . $year ;?>  &nbsp; <a href="/breakdown_recurring/export?month=<?php echo $month;?>&year=<?php echo $year;?>&brand=<?php echo $sel_brand;?>&plan_id=<?php echo $default_plan;?>">Export</a></h2>
	<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">

			<table class="table table-striped table-bordered table-condensed dTableR uafix" id="service_table" aria-describedby="service_table_info">
				<thead>
					<tr class="nosort" role="row">
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th title="period" class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Period</th>
						<th title="Number of signups" class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">#Signups</th>
						<th title="Initial Revenue"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">$Rev</th>
						<th title="Count of refunds"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">$Ref</th>
						<th title="$ Amount of refunds"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">#Ref</th>
						<th title="Refund percentage by revenue"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">%$</th>
						<th title="Refund percentage by count"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">%#</th>
						<th title="Rebilling count"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">#Reb</th>
						<th title="Rebilling revenue"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">$Reb</th>
						<th title="Count of refunds"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">#Ref</th>
						<th title="$ Amount of refunds"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">$Ref</th>
						<th title="Refund percentage by revenue"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">%$</th>
						<th title="Refund percentage by count"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">%#</th>
						<th title="clients still active"  class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">#Active</th>
					</tr>
				</thead>

				<tbody role="alert" aria-live="polite" aria-relevant="all">

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
					<td><?php echo $value['refund_cnt']; ?></td>
					<td><?php echo $value['refunds']; ?></td>
					<td><?php echo $value['revenue'] == 0 ? 0 : round($value['refunds'] / $value['revenue'],2)  * 100; ?>%</td>
					<td><?php echo $value['count'] == 0 ? 0 : round($value['refund_cnt'] / $value['count'],2)  * 100; ?>%</td>
					<td><?php echo $value['rec_count']; ?></td>
					<td><?php echo number_format($value['rec_revenue'],2); ?></td>
					<td><?php echo $value['rec_refcount']; ?></td>
					<td><?php echo number_format($value['rec_refunds'],2); ?></td>
					<td><?php echo $value['rec_revenue'] == 0 ? 0 : round($value['rec_refunds'] / $value['rec_revenue'],2)  * 100; ?>%</td>
					<td><?php echo $value['rec_count'] == 0 ? 0 : round($value['rec_refcount'] / $value['rec_count'],2)  * 100; ?>%</td>
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
					<td><?php echo $refcnt; ?></td>
					<td><?php echo $ref; ?></td>
					<td><?php echo $trev == 0 ? 0 : round($ref / $trev ,2) * 100; ?> %</td>
					<td><?php echo $tcount == 0 ? 0 : round($refcnt / $tcount ,2) * 100; ?>%</td>
					<td><?php echo $reccnt; ?></td>
					<td><?php echo number_format($recrev,2); ?></td>
					<td><?php echo $recrefcnt; ?></td>
					<td><?php echo number_format($recref,2); ?></td>
					<td><?php echo $recrev == 0 ? 0 : round($recref / $recrev ,2) * 100; ?> %</td>
					<td><?php echo $reccnt == 0 ? 0 : round($recrefcnt / $reccnt ,2) * 100; ?>%</td>
					<td><?php echo $ac; ?></td>
				</tr>
				</tbody>
			</table>

		</div>
<?php
endif;
?>

