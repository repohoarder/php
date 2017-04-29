<div class="content upsell-business-domain">
	<?php echo $this->template->load_view('warning.php'); ?>
	<section class="module">
		
			<h1 class="custom-bg1"><?php echo $this->lang->line('bonus_b_domain_title'); ?></h1>
			<div class="col-l">
				<p><?php echo $this->lang->line('bonus_b_domain_diduknow1'); ?> <strong class="txt-large custom-color1"><?php echo $this->lang->line('bonus_b_domain_diduknow2'); ?></strong><br /> <?php echo $this->lang->line('bonus_b_domain_diduknow3'); ?></p>
			</div>
			<div class="col-r">
				<div class="features">
					<h2 class="custom-color1"><?php echo $this->lang->line('bonus_b_domain_meansbusiness'); ?></h2>
					<h3 class="custom-color1"><?php echo $this->lang->line('bonus_b_domain_why'); ?></h3>
					<ul>
						<li><?php echo $this->lang->line('bonus_b_domain_item1'); ?></li>
						<li><?php echo $this->lang->line('bonus_b_domain_item2'); ?></li>
						<li><?php echo $this->lang->line('bonus_b_domain_item3'); ?></li>
					</ul>
				</div>

				<form action="" method="post">
					<input type="hidden" name="plans[]" value="<?php echo $domain_sld.'.co'; ?>" />
					<input type="hidden" name="action_id" value="58"/>
					<input type="submit" value="<?php echo $this->lang->line('bonus_b_domain_add'); ?>" class="btn-yellow">
				</form>

				<form action="" method="post">
					<input type="hidden" name="action_id" value="59"/>
					<input type="submit" value="<?php echo $this->lang->line('bonus_b_domain_nothanks'); ?>" class="btn-plain" />
				</form>

				<p class="light"><?php echo $this->lang->line('bonus_b_domain_onetimecharge'); ?></p>
			</div>
		</form>
	</section>
</div>