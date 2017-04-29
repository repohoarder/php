<?php if ($error): ?>

	<p style="font-weight:bold;color:red;"><?php echo $error; ?></p>

<?php endif; ?>


<h1>Manage | Manage Upsell Funnel</h1>

<section id="pnl-accordion">

	<h2>Manage Upsells</h2>
	<div class="module s-manage-upsells" id="manage-products">
		<div class="pad">

			<p>
			Your hosting company, your way. Decide here what services your customers are offered. 
			
				<?php /*
				To estimate your revenue and update pricing, please visit Manage Pricing.
				*/ ?>
			</p>
			
			<!-- <p>You may make changes to your sales funnel at any time.</p> -->
			<h3>Create Your Sales Funnel</h3>
			
			<div class="box upsell-create">
				<a href="/manage/sales_path">Click to add</a>
			</div>

			<?php

			foreach ($funnels as $funnel): 

				$default_class = '';

				if ($funnel['funnel_default']):

					$default_class = 'default-'.$funnel['funnel_default'];

				endif;

				$count = 0;
				$total = count($funnel['upsells']);
				$max   = 5;

				?>

				<div class="box <?php echo $default_class; ?>">
					<h4 title="<?php echo $funnel['funnel_name'];?>"><?php echo $funnel['funnel_name'];?></h4>
					<ol>
						<?php foreach ($funnel['upsells'] as $upsell):

							if ($count >= $max) break; ?>

								<li title="<?php echo $upsell['name']; ?>"><?php echo $upsell['name']; ?></li>

							<?php $count++;

						endforeach; ?>
					</ol>

					<?php if ($total - $max > 0): ?>

						<p class="pop_preview">+MORE</p>

					<?php endif; ?>

					<a href="#" class="pop_upsell btn-green more">Options</a>


					<?php 

					if (count($funnel['upsells'])): 

						$thumbnails = array();

						foreach ($funnel['upsells'] as $upsell):  

							if ( ! array_key_exists($upsell['slug'], $all_products)):

								continue;

							endif;

							$thumbnails[] = $upsell;

						endforeach;

						if (count($thumbnails)): ?>

							<a href="#" class="pop_preview btn-green view">Upsells</a>

							<div class="funnel_preview" style="display:none;">

								<h4 title="<?php echo $funnel['funnel_name'];?>" style="color: #224990;font-size: 20px;margin-bottom:20px;"><?php echo $funnel['funnel_name'];?> - Upsells</h4>

								<?php

								$count = 1;

								foreach ($thumbnails as $upsell): ?>

									<div class="product upsell-<?php echo $upsell['slug']; ?>">

										<span class="product-label"><span><?php echo $upsell['name']; ?></span></span>
										<span class="product-number"><?php echo $count;?></span>

									</div>

									<?php 

									$count++;

								endforeach; ?>

							</div>

							<?php 

						endif; 

					endif; ?>


					<div class="<?php echo $default_class; ?> bot"></div>
					
					<div class="funnel_popup">
						<h1><?php echo $funnel['funnel_name']; ?></h1>
						<h2>What Would You Like To Do?</h2>
						<hr />
						<form method="post" action="/manage/products" class="set_default_funnel">
							<h3>Set As Default</h3>
							<div class="col-l">
								<p>Click button to set this funnel as the default.</p>
								<input type="hidden" name="new_submission" value="1" />
								<input type="hidden" name="set_default" value="<?php echo $funnel['funnel_type'];?>" /> 
								<input type="hidden" name="funnel_id" value="<?php echo $funnel['funnel_id']; ?>" />
							</div>
							<div class="col-r">
								<button type="submit" class="btn-blue">Set as Default <?php echo ucwords($funnel['funnel_type']);?> Funnel</button>
							</div>
						</form>			
						<hr />
						<div class="edit-prices">
							<h3>Edit Prices</h3>
							<div class="col-l">
								<p>Click button to edit prices for this funnel.</p>
							</div>
							<div class="col-r">
								<a href="/manage/pricing/<?php echo $funnel['funnel_id']; ?>" class="btn-blue">Edit Prices</a>
							</div>
						</div>
						<hr />
						<form method="post" action="#" class="copy_link">
							<h3>Upsell Link</h3>
							<div class="col-l">
								<?php $funnel_url = 'https://infrastructure.hostingaccountsetup.com/initialize/'.$partner['id'].'/'.$funnel['funnel_id']; ?>
								<input type="text" class="funnel-link" name="funnel_link" value="<?php echo $funnel_url; ?>" readonly />
							</div>
							<div class="col-r">
								<span class="copier btn-blue">Copy Link</span>
							</div>
						</form>
						<hr />
						<form method="post" action="/manage/products" class="delete_funnel">
							<h3>Delete Funnel</h3>
							<div class="col-l">
								<p>Click button to delete this funnel.</p>
								<input type="hidden" name="new_submission" value="1" />
								<input type="hidden" name="delete_funnel" value="1" /> 
								<input type="hidden" name="funnel_id" value="<?php echo $funnel['funnel_id']; ?>" />
							</div>
							<div class="col-r">
								<button type="submit" class="btn-red">Delete Funnel</button>
							</div>
							<input type="hidden" name="funnel_name" value="<?php echo $funnel['funnel_name'];?>"/>
						</form>

					</div>
				</div>
			<?php endforeach; ?>
			
		</div>
	</div>
</section>
