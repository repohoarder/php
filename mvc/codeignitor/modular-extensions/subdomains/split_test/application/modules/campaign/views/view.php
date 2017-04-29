<nav>
<ul>
	<li><a href="/campaign/">Add</a></li>
	<li><a href="/campaign/view">View</a></li>
</ul>
</nav>

<section>
<h3>Brands</h3>
<ul class="brands">
	<li <?php if(!$selected_brand_id){ echo 'class="selected"';}?>><a href="/campaign/view/">All</a></li>
<?php foreach($brands as $brand) :
	$selected = "";

	if($selected_brand_id == $brand['id']):
		$selected = 'selected';
	endif;
	
	echo '<li  class="'.$selected.'"><a href="/campaign/view/'.$brand['id'].'">'.$brand['name'].'</a></li>';
endforeach;?>
</ul>
<div style="clear:both;"></div>
</section>

	<h1> Campaigns </h1> 
	
	<?php 

		if(isset($campaigns)){
			
			foreach($campaigns as $campaign) : ?>
				
				<div id="keys">
					<div class="key">
						<span class="control">&nbsp;</span> Control
					</div>
					<div class="key">
						<span class="winner">&nbsp;</span> Winner
					</div>
				</div>
				<section>
					<h3>
						<?php echo $campaign['name']; ?> - <?php echo date('m/d/Y' , strtotime($campaign['date'])); ?> 
						<span style="float:right">
							<a href="/campaign/deactivate/<?php echo $campaign['id']; ?>" class="confirm" title="Are you sure you want to deactivate <?php echo $campaign['name']; ?>">Deactivate</a>
						</span>
					</h3>
					<table style="margin-bottom:20px;">
						<thead>
							<tr>
								<th>ID</th>
								<th>Campaign Name</th>
								<th>Date</th>
								<th>Brand</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td><?php echo $campaign['id']; ?></td>
							<td><?php echo $campaign['name']; ?></td>
							<td><?php echo $campaign['date']; ?></td>
							<td><?php echo $campaign['brand_name']; ?></td>
						</tr>

						<tr>
							<td colspan="4"> <h4>Variations</h4>
								<table>
									<thead>
										<tr>
											<th>Url</th>
											<th>Percent</th>
											<th>Visitors</th>
											<th>Conversions</th>
											<th>Conversion %</th>
											<th>Amount</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($campaign['variations'] as $variation):
											
											$class = '';

											if(isset($variation['control']) && $variation['control']==1): 
												$class = 'control'; 
											endif; 

											$percent = 0;

											if ($variation['conversions'] > 0):

												$percent = ($variation['conversions'] / $variation['hits']) * 100;

											endif;

											$percent = number_format($percent, 2);

											?>
											
											<tr class="<?php echo $class; ?>">
												<td><?php echo $variation['url']; ?></td>
												<td><?php echo $variation['percent']; ?></td>
												<td><?php echo $variation['hits']; ?></td>
												<td><?php echo $variation['conversions']; ?></td>
												<td><?php echo $percent; ?>%</td>
												<td><?php echo $variation['amount']; ?></td>
											</tr>

										<?php endforeach; ?>
									</tbody>
								</table>
							</td>
						</tr>

						</tbody>

					</table>
					<div style="clear:both;"></div>

				</section>

			<?php
			endforeach;
			
		} else {
		
			echo "No split test campaigns are available";
		}

		?>
	



<div style="clear:both">&nbsp;</div>