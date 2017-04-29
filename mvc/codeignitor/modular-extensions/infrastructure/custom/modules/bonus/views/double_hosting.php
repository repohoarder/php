<div class="content upsell">
	<?php echo $this->template->load_view('warning.php'); ?>
	<section class="module">

		
			<h1 class="custom-bg1"><?php echo $this->lang->line('bonus_hostdiscount_title'); ?></h1>
			<hgroup>
				<h2><?php echo $this->lang->line('bonus_hostdiscount_wait'); ?></h2>
				<h3><?php echo $this->lang->line('bonus_hostdiscount_selected',$current_period['period']); ?></h3>
				<h4><?php echo $this->lang->line('bonus_hostdiscount_discount'); ?></h4>
			</hgroup>
			<div class="col-l">
				<p>
					<?php echo $this->lang->line('bonus_hostdiscount_today_only'); ?>
					<span>
						<strong><?php echo $this->lang->line('bonus_hostdiscount_save_percent'); ?></strong>
						<?php echo $this->lang->line('bonus_hostdiscount_on_hosting',$next_period['num_months']); ?>
					</span>
				</p>
			</div>
			<div class="col-r">

				<p><?php echo $this->lang->line('bonus_nodiscount_price',$current_period['period'],$current_mo); ?></p>

				<p class="custom-color1" style="margin:0 0 50px 0;">
					<?php echo $this->lang->line('bonus_hostdiscount_price',$next_period['num_months'],$next_mo,$discount_mo); ?>
				</p>

				<?php
				$form_submission = '';
				$attributes      = array(
					'method' => 'POST'
				);

				echo form_open(
					$form_submission,
					$attributes,
					array(
						'action_id' => 84
					)
					
				);
				?>

					<input type="submit" value="<?php echo $this->lang->line('bonus_hostdiscount_add'); ?>" class="btn-yellow">
				
				<?php 

				echo form_close(); 


				echo form_open(
					$form_submission,
					$attributes,
					array(
						'action_id' => 85
					)
					
				); ?>

					<input type="submit" value="<?php echo $this->lang->line('bonus_hostdiscount_nothanks'); ?>" class="btn-plain" />

				<?php echo form_close(); ?>

				<p class="light"><?php echo $this->lang->line('bonus_hostdiscount_onetimecharge'); ?></p>

			</div>

	</section>
</div>