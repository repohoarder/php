

<section id="marketing">

	<h1><?php echo $this->lang->line('domain_form_heading');?></h1>

	<p><?php echo $this->lang->line('domain_form_paragraph'); ?></p>
	
	<span id="img-loading"></span>

	<form action="#" method="post" id="frmDomainSearch">

		<fieldset>

			<legend><?php echo $this->lang->line('domain_form_legend'); ?></legend>

			<input type="text" id="txtDomainName" name="txtDomainName" placeholder="Google" required="required" />

			<select id="selZone" name="selZone">

				<?php foreach ($this->config->item('available_tlds') as $tld): ?>

					<option value="<?php echo $tld; ?>">.<?php echo $tld; ?></option>

				<?php endforeach; ?>

			</select>

			<input type="submit" value="Search" />

		</fieldset>

	</form>

</section>

<section id="availability">

	<div class="column">

		<div class="results-no">

			<strong><?php echo $searched_domain['full']; ?></strong>

			<p><?php echo $this->lang->line('domain_form_searched_taken'); ?></p>

		</div>

		<div class="results-yes" itemscope itemtype="http://schema.org/Product" data-domain="<?php echo $searched_domain['full']; ?>" data-tld="<?php echo $searched_domain['tld']; ?>" data-sld="<?php echo $searched_domain['sld']; ?>" data-from="result">

			<span class="btn-added-large"><?php echo $this->lang->line('domain_form_add'); ?></span>

			<strong itemprop="name"><?php echo $searched_domain['full']; ?></strong>

			<?php //$tld_prices = $this->prices->get_tld_prices($searched_domain['tld'], $formatted = TRUE); ?>

			<p><?php echo $this->lang->line('domain_form_searched_avail'); ?> <span itemprop="offers" itemscope itemtype="http://schema.org/Offer"><em itemprop="price"><?php //echo $tld_prices['discount']; ?>!</em></span></p>

		</div>

		<table id="suggested-domains">

			<thead>

				<tr class="alt">

					<th colspan="5"><?php echo $this->lang->line('domain_form_suggestion_heading'); ?></th>

				</tr>

				<tr>

					<?php foreach ($this->config->item('available_tlds') as $tld): 

						//$tld_prices = $this->prices->get_tld_prices($tld, $formatted = TRUE); ?>

						<td itemscope itemtype="http://schema.org/Product" data-domain="exampledomain.<?php echo $tld; ?>">

							<strong itemprop="name">.<?php echo $tld; ?></strong><br />

							<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">

								<del><?php //echo $tld_prices['original']; ?></del><br />

								<em itemprop="price"><?php //echo $tld_prices['discount']; ?></em>

							</span>

							<a href="#" class="btn-add"><?php echo $this->lang->line('domain_form_add'); ?></a>

						</td>

					<?php endforeach; ?>

				</tr>

			</thead>

			<tbody>				

				<tr class="alt" itemscope itemtype="http://schema.org/Product" data-domain="googleshop.info">

					<td><a href="#" class="btn-add"><?php echo $this->lang->line('domain_form_add'); ?></a></td>

					<td colspan="3" itemprop="name">googleshop.info</td>

					<td itemprop="offers" itemscope itemtype="http://schema.org/Offer">

						<del>$19.99</del> <em itemprop="price">$3.99</em>

					</td>

				</tr>


			</tbody>

		</table>

	</div>
	<div class="column">
		<form action="" method="post">
			<fieldset>
				<legend>Order Summary</legend>
				<table id="order-summary">
		
					<thead>
		
						<tr>
		
							<th colspan="3"><?php echo $this->lang->line('domain_form_order_heading'); ?></th>
		
						</tr>
		
					</thead>
		
					<tbody>
		
						<tr class="empty"><td colspan="3"><p>Please add an item to your order.</p></td></tr>
		
						<!--<tr class="alt">
		
							<td><?php echo $searched_domain['full']; ?></td>
		
							<td>
								<?php //$tld_prices = $this->prices->get_tld_prices($searched_domain['tld'], $formatted = TRUE); ?>
		
								<del><?php //echo $tld_prices['original']; ?></del> 
								<em>
									<?php //echo $tld_prices['discount']; ?>
								</em>
								<input type="hidden" name="domains[]" data-domain="<?php echo $searched_domain['full']; ?>" data-tld="" data-sld="" data-from="" />
							</td>
		
							<td class="remove"><a href="#" title="<?php echo $this->lang->line('domain_form_del_item'); ?>"><img src="/resources/brainhost/img/icon-remove.png" alt="<?php echo $this->lang->line('domain_form_del_item'); ?>" /></a></td>
		
						</tr>-->
		
					</tbody>
		
				</table>
		
				<input type="submit" class="btn-register-now" value="<?php echo $this->lang->line('domain_form_register_now'); ?>">
			</fieldset>
		</form>
	</div>
</section>
