<h1>Financial Statements</h1>
<section id="pnl-accordion">
	<h2>Financial Statements</h2>
	<div class="module s-financial">
		<div class="pad">
			<?php
			if ($error) echo '<p style="text-align:center;font-weight:bold;color:red;">'.$error.'</p>';
			?>

			<p style="text-align:center;">Review your financial statements here to see revenue, costs and commissions.</p>

			<hr />
			<?php if (!empty($statements)): ?>
				<h3>Click to download</h3>
				<table>

					<?php
					// iterate through each financial statement and populate table
					foreach ($statements AS $key => $value):
					?>
						<tr>
							<td><a href="/financial/download/<?php echo $value['id']; ?>">Date <?php echo date("m/d/Y", strtotime($value['paid_date'])); ?></a></td>
						</tr>
					<?php
					endforeach;
					?>
				</table>
			<?php endif; ?>
			<?php if (empty($statements)): ?>
				<p style="text-align:center;">There are currently no statements yet.</p>
			<?php endif; ?>
		</div>
	</div>
</section>