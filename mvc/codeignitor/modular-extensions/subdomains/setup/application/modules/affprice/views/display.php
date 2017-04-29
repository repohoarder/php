
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Affiliate Prices</title>
	
	<link rel="stylesheet" href="/resources/modules/affprice/assets/css/style.css" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

	<script type="text/javascript">

		$(document).ready(function(){

			$('h2').click(function(){

				$(this).next('.offers').slideToggle();
			});

			$('input.default_term').click(function(e){

				if ($(this).closest('tr').find('input.active').is(':not(:checked)')) {

					e.preventDefault();

				}

			});

			$('form').submit(function(){

				if ( ! $('input[name="affiliate_id"]').val()){
					return confirm('This will set the default price for all affiliates. Continue?');
				}

			});

			$('.active').change(function(){

				if ($(this).is(':not(:checked)')) {

					if ($(this).closest('tr').find('input.default_term:radio:checked').length > 0){

						var $tbody = $(this).closest('tbody');

						$tbody.find('input.default_term:radio').prop('checked', false);
						$tbody.find('input.active:checked:last').closest('tr').find('input.default_term:radio').prop('checked', true);

					}

				}

			});

		});

	</script>

</head>
<body>
	
	<div id="container">
		
		<?php if ($this->input->post('form_submitted')): 

			if ( ! isset($errors) || empty($errors)): ?>
		
				<ul class="success">
					<li>Prices updated</li>
				</ul>	
			
			<?php else: ?>

				<ul class="error">
					<?php foreach ($errors as $error): ?>
						<li><?php echo $error; ?></li>
					<?php endforeach; ?>
				</ul>

			<?php endif;

		endif; ?>

		<h1>Update Prices</h1>

		<form method="post" action="">

			<label>Affiliate: <input type="text" name="affiliate_id" /></label>
			<label>Offer: <input type="text" name="offer_id" /></label>
			
			<table>

				<?php 

				foreach ($default as $plan => $info): 

					foreach ($info['variants'] as $variant => $var): ?>

						<thead>
							<tr class="top">
								<th colspan="3"><?php echo $info['name']; ?></th>
								<?php /* <th colspan="2">Variant: <?php echo $variant; ?></th> */ ?>
								<th colspan="3"></th>
							</tr>
							<tr>
								<th>Active</th>
								<th>Default</th>
								<th>Num Months</th>
								<th>Trial Discount</th>
								<th>Price</th>
								<th>Setup Fee</th>
							</tr>
						</thead>

						<tbody>

							<?php foreach($var['terms'] as $term => $pricing): ?>

								<tr>
									<?php 

									if ($pricing['service_id'] == 1): 

										$checked = (isset($pricing['active']) && $pricing['active']) ? 'checked="checked"' : ''; ?>

										<td>
											<input type="checkbox" class="active" name="plans[<?php echo $info['brand_service_id']; ?>][terms][<?php echo $term; ?>][active]" value="1" <?php echo $checked; ?>/>
										</td>

										<?php $checked = (isset($pricing['default']) && $pricing['default']) ? 'checked="checked"' : ''; ?>

										<td>
											<input type="radio" class="default_term" name="plans[<?php echo $info['brand_service_id']; ?>][default_term]" value="<?php echo $term; ?>" <?php echo $checked; ?>/>
										</td>

									<?php else: ?>
										
										<td>&mdash; <input type="hidden" name="plans[<?php echo $info['brand_service_id']; ?>][terms][<?php echo $term; ?>][active]" value="1" />
										</td>
										<td>&mdash;</td>

									<?php endif; ?>
									
									<td><?php echo $term; ?></td>
									<td><input type="text" name="plans[<?php echo $info['brand_service_id']; ?>][terms][<?php echo $term; ?>][trial_discount]" value="<?php echo $pricing['trial_discount']; ?>"/></td>

									<td><input type="text" value="<?php echo $pricing['price']; ?>" name="plans[<?php echo $info['brand_service_id']; ?>][terms][<?php echo $term; ?>][price]"/></td>

									<td><input type="text" value="<?php echo $pricing['setup']; ?>" name="plans[<?php echo $info['brand_service_id']; ?>][terms][<?php echo $term; ?>][setup]"/></td>

									<input type="hidden" name="plans[<?php echo $info['brand_service_id']; ?>][service_id]" value="<?php echo $pricing['service_id'];?>"/>
								</tr>
								
							<?php endforeach; ?>

						</tbody>

					<?php endforeach; ?>

				<?php endforeach; ?>

			</table>
			
			<div id="button_container">
	
				<?php

				$button_txt = array(
					'Do dat thang',
					'Get \'er done',
					'Let \'er rip',
					'I dare you',
					'Git along lil doggy',
					'Rock and roll',
					'Save the whales',
					'Press for bacon',
					'Derr for President',
					'Can\'t touch this',
					'Fire ze missiles',
					'I don\'t think so, Tim'
				);

				shuffle($button_txt);

				?>

				<button type="submit"><?php echo array_shift($button_txt); ?></button>

			</div>

			<input name="form_submitted" type="hidden" value="1" />

		</form>
	
		<h1>View Custom Prices</h1>

		<ul class="aff_column">

			<?php 

			$num  = count($prices['affiliates']);
			$half = round($num / 2);

			$loop = 0;

			
			foreach ($prices['affiliates'] as $affiliate => $aff): 

				if ($loop == $half): ?>

					</ul>
					<ul class="aff_column second_aff_column">
				
				<?php

				endif;

				$loop++;

				$affiliate = ($affiliate) ? $affiliate : 'Default'; 

				$num_offers = count($aff['offers']); ?>

				<li class="affiliate">

					<h2><?php echo $affiliate;?> <span>(<?php echo $num_offers; ?>)</span></h2>
					
					<ul class="offers">

						<?php foreach ($aff['offers'] as $offer => $off): 

							$offer = ($offer) ? $offer : 'All'; ?>

							<li class="offer">

								<strong>Offer: <?php echo $offer;?></strong>
									
								<table>

									<?php foreach ($off['plans'] as $plan => $info): ?>
										
										<?php foreach ($info['variants'] as $variant => $var): ?>

											<thead>
												<tr class="top">
													<th colspan="7"><?php echo $info['name']; ?></th>
													<?php /*<th colspan="2">Variant: <?php echo $variant; ?></th> */ ?>
												</tr>
												<tr>
													<th>Active</th>
													<th>Default</th>
													<th>Num Months</th>
													<th>Trial Discount</th>
													<th>Price</th>
													<th>Setup Fee</th>
												</tr>
											</thead>

											<tbody>

												<?php foreach($var['terms'] as $term => $pricing): 

													$is_active = (isset($pricing['active']) && $pricing['active']); ?>
													
													<tr class="<?php echo ($is_active) ? 'active_row' : 'inactive_row'; ?>">

														<?php 

														if ($pricing['service_id'] == 1): ?>

															<td>
																<?php if ($is_active): ?>
																	<img src="/resources/modules/affprice/assets/img/check.png" alt=""/>
																<?php endif; ?>
															</td>
															
															<td>
																<?php if (isset($pricing['default']) && $pricing['default']): ?>
																	<img src="/resources/modules/affprice/assets/img/check.png" alt=""/>
																<?php endif; ?>
															</td>

														<?php else: ?>
															
															<td>&mdash;</td>
															<td>&mdash;</td>

														<?php endif; ?>

														
														<td><?php echo $term; ?></td>
														<td><?php echo $pricing['trial_discount']; ?></td>
														<td><?php echo $pricing['price']; ?></td>
														<td><?php echo $pricing['setup']; ?></td>
													</tr>
													
												<?php endforeach; ?>

											</tbody>

										<?php endforeach; ?>

									<?php endforeach; ?>

								</table>

							</li>

						<?php endforeach; ?>

					</ul>

				</li>
			
			<?php endforeach; ?>

		</ul>

		<div style="clear:both"></div>

	</div>

</body>
</html>