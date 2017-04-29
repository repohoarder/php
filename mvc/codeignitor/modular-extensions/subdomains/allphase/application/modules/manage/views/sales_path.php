<?php $step = 0; ?>

<form method="post" action="">

	<input type="hidden" name="funnel_type" value="hosting" />

	<input type="hidden" name="pre_upsells[0]" value="domain_lander" />

	<?php 

	foreach ($tied_products as $page_slug => $products): 

		foreach ($products as $product_slug => $type):  ?>

			<input type="hidden" name="tied_products[<?php echo $page_slug; ?>][<?php echo $product_slug; ?>]" value="<?php echo $type; ?>" />

		<?php endforeach; 

	endforeach; 

	?>

	<h1>Manage Upsells</h1>
	<section id="pnl-accordion">

		<h2>Name Your Funnel</h2>
		<div class="module s-manage-name">
			<div class="pad">
				<h3>Step <?php echo ++$step; ?>: Name Your Funnel</h3>
				<input type="text" name="funnel_name" value="" />
			</div>
		</div>
	</section>
	

	<section id="pnl-accordion">
		<h2>Manage Billing Page</h2>
		<div class="module s-manage-billing">
			<div class="pad">
				<h3>Step <?php echo ++$step; ?>: Select Your Billing Page</h3>
				<div class="pad">
					<p>Choose from one of our options below:</p>

					<div class="billing-pages">

						<?php

						$bill_pgs = array(
							'billing'   => 'Accordion',
							'three_col' => 'Three Column'
						);

						$selected = 'bill_select';

						foreach ($bill_pgs as $slug => $name): ?>
							
							<div class="box">
								<a href="/resources/modules/manage/assets/images/dragon/billing/<?php echo $slug;?>.png" target="_blank"><img src="/resources/modules/manage/assets/images/dragon/billing/<?php echo $slug;?>-thumb.png" alt="<?php echo $name;?>" title="<?php echo $name;?>" /></a>

								<a href="/resources/modules/manage/assets/images/dragon/billing/<?php echo $slug;?>.png" target="_blank" class="zoom">Zoom</a>

								<span class="bill_btn <?php echo $selected;?>" data-slug="<?php echo $slug;?>"></span>
							</div>

							<?php $selected = FALSE;

						endforeach; ?>

						<input type="hidden" name="pre_upsells[1]" value="billing" id="billing_input" />

					</div>
				</div>
			</div>
		</div>
	</section>


	<section id="pnl-accordion" class="upsell-manage">
		<h2>Manage Upsells</h2>
		<div class="module s-manage-upsell-path">
			<div class="pad">
				<h3>Step <?php echo ++$step; ?>: Arrange Your Sales Path</h3>
			
				<p>
					Arrange upsells in the order you wish to present them to your customers. Select between 1 and <?php echo $max_num_pages; ?> products.
				</p>

				<p style="font-size:0.7em;margin:8px 0 0">Note: if this area is left blank, your customers will not see any upsells. </p>
				<div id="container">
					<?php for($i=1; $i <= $max_num_pages; $i++): ?>
						<div class="lair" data-lair="<?php echo $i;?>">
							<span class="lair-label"><span></span></span>
							<span class="knight"><?php echo $i;?></span>
							<input type="hidden" class="inp-item"  name="products[<?php echo $i;?>][item]"     value="" />
							<input type="hidden" class="inp-pos"   name="products[<?php echo $i;?>][position]" value="<?php echo $i;?>" />
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</section>

	<section id="pnl-accordion" class="upsell-dragon">
		<h2>Upsell Product Menu - Drag and Drop</h2>
		<div class="module s-manage-dragon">
			<div class="pad">
				<p>All upsell products are listed below; simply drag and drop the products you want into their desired location on the left.</p>

				<hr />
				<span class="tooltip">To add an upsell to your funnel, click on one below and drag it to a box under "Manage Upsells" on the left. <br/>You may preview an upsell by clicking on its magnifying glass icon.</span>
			</div>
			<div id="accordion">
				<?php foreach ($upsells as $category => $products): ?>
					<h3><?php echo $category;?></h3>
					<div class="pad" style="position:static;">
						<?php foreach ($products as $name => $short): 
							
							?>
							<div class="dragon upsell-<?php echo $short; ?>" data-dragon="<?php echo $short;?>">
								<span class="dragon-label"><span><?php echo $name; ?></span></span>
								<span class="zoom">Zoom</span>
							</div>
						<?php 
						endforeach; ?>
						<span class="clear"></span>
					</div>
				<?php  endforeach; ?>
			</div>
		</div>
	</section>
	
	<section id="pnl-accordion" style="clear:both;">

		<h2>Additional Settings</h2>
		<div class="module s-manage-name">
			<div class="pad">

				<?php if(isset($order_confirmation)): 

					//$order_confirmation = array_reverse($order_confirmation, TRUE);

					$checked = FALSE;

					foreach($order_confirmation as $title => $slug): 

						$checked = ($checked === FALSE) ? 'checked="checked"' : ''; ?>
					
						<input type="radio" name="post_upsells[0]" value="<?php echo $slug;?>" <?php echo $checked;?> /> <?php echo $title;?>

						<?php $checked = '';

					endforeach;

				endif; ?>
			</div>

			<div class="pad">
				<input type="checkbox" name="white_label" value="1">
				<span>Remove Header &amp; Footer ("White Label" - For use with iFrames)</span>
			</div>

			<div class="pad">
				<input type="checkbox" name="exit_pop" value="1" />
				<span>Prompt customers to stay if they attempt to leave before buying ("Exit Popup")</span>
			</div>
		</div>
	</section>
	
	<div class="row" style="float:left;margin:25px 0;width:100%;">

		<input type="hidden" name="sales_path" value="1" />
		<input type="submit" class="upsell btn-contact" value="Save My Funnel" style="float:none;display:block;margin:0 auto;" />

	</div>
</form>