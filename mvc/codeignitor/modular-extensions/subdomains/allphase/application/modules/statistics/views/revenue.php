<div id="sales_statistics" class="graph_form">

	<!-- Section specific Javascript -->

	<script>
		$(function() {
			
		});
	</script>


	<?php if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>'; ?>
	<h1>Customer Reporting | Estimated Recurring Revenue</h1>
	<section class="pnl-accordion-class">

	<!-- Estimated Recurring Revenue view ********************************************************************* -->

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
					<?php
					foreach($plans as $title=>$revenue) : ?>
						<tr>
							<th><?php echo $title;?></th>
							<td>$<?php echo number_format($revenue,2);?></td>
						</tr>
					<?php 
					endforeach;
					?>
				</tbody>
			</table>
			
			<br>
			<a href="/tooltip/calculate_revenue" class="lightbox" data-fancybox-type="iframe" style="margin-left: 25px;">How Is This Calculated?</a>
			<br><br>
			<!--<a href="#" class="button">Show Plans</a> -->
		</div>
	
		<!-- end estimated recurring -->

	</section>


	

</div>
