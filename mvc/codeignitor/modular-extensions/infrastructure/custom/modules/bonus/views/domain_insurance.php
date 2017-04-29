<div class="content upsell-domain-insurance">
	<?php echo $this->template->load_view('warning.php'); ?>
	<section class="module">
	
		<h1 class="custom-bg1"><?php echo $this->lang->line('bonus_d_insurance_title'); ?></h1>
		<div class="col-r">
			<div class="features">
				<h2 class="custom-color1"><?php echo $this->lang->line('bonus_d_insurance_isyours', $domain); ?></h2>
				<p><?php echo $this->lang->line('bonus_d_insurance_missing'); ?></p>
				<ul>
					<li><?php echo $this->lang->line('bonus_d_insurance_item1'); ?></li>
					<li><?php echo $this->lang->line('bonus_d_insurance_item2'); ?></li>
					<li><?php echo $this->lang->line('bonus_d_insurance_item3'); ?></li>
				</ul>
			</div>
			<p class="center"><?php echo $this->lang->line('bonus_d_insurance_diduknow1'); ?> <span class="txt-large custom-color1"><?php echo $this->lang->line('bonus_d_insurance_diduknow2'); ?></span> <?php echo $this->lang->line('bonus_d_insurance_diduknow3'); ?></p>

			<?php

			$form_submission = '';
			$attributes      = array(
				'method' => 'POST'
			);

			echo form_open(
				$form_submission,
				$attributes,
				array(
					'action_id'      => 56, 
					'domain'         => $domain, 
					'domain_pack_id' => $domain_pack_id, 
					'plans[]'        => $page
				)
			); ?>

			<input type="hidden" name="action_id" value="56"/>
			<input type="submit" value="<?php echo $this->lang->line('bonus_d_insurance_add'); ?>" class="btn-yellow"/>
			
			<?php echo form_close(); 


			

			$form_submission = '';
			$attributes      = array(
				'method' => 'POST'
			);

			echo form_open(
				$form_submission,
				$attributes,
				array(
					'action_id'      => 57
				)
				
			); ?>

			<input type="submit" value="<?php echo $this->lang->line('bonus_d_insurance_nothanks'); ?>" class="btn-plain" />

			<?php echo form_close(); ?>

			<p class="light"><?php echo $this->lang->line('bonus_d_insurance_onetimecharge'); ?></p>

		</div>

	</section>
</div>