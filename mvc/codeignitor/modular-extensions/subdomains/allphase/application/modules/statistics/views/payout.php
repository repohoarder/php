<?php

if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>';

?>

<section id="pnl-accordion">

<!-- Last Payout view ************************************************************************************* -->

	<h2>Payouts</h2>
	<div class="module s-payout">
		<div class="pnl-payout-data">
			
		</div>
		<table>
			<thead>
				<tr>
					<th>Date</th>
					<th>Range</th>
					<th>Sales</th>
					<th>Payout</th>
				</tr>
			</thead>
			<tbody>
                        <?php foreach ($payoutdata as $record) : ?>
				<tr>
					<th><?php echo date("m/d/y",strtotime($record['paid_date']));?></th>
					<td><?php echo date("m/d/y",strtotime($record['payment_period_start']));?> - <?php echo date("m/d/y",strtotime($record['payment_period_end']));?></td>
					<td><?php echo $record['count']; ?></td>
					<td>$<?php echo $record['amount']; ?></td>
				</tr>
                        <?php endforeach;?>
			</tbody>
		</table>
	</div>


</section>
