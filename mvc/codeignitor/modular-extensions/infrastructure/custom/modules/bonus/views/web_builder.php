<div class="content upsell">
	<?php echo $this->template->load_view('warning.php'); ?>
	<section class="module">
		<form action="" method="post">
			<h1 class="custom-bg1"><?php echo $this->lang->line('bonus_builder_title'); ?></h1>
			<div class="col-l">
				<p><?php echo $this->lang->line('bonus_builder_diduknow1'); ?><br /> <strong class="txt-large custom-color1"><?php echo $this->lang->line('bonus_builder_diduknow2'); ?></strong><br /> <?php echo $this->lang->line('bonus_builder_diduknow3'); ?></p>
			</div>
			<div class="col-r">
				<div class="features">
					<h2 class="custom-color1"><?php echo $this->lang->line('bonus_builder_open'); ?></h2>
					<h3 class="custom-color1"><?php echo $this->lang->line('bonus_builder_almost'); ?></h3>
					<p><?php echo $this->lang->line('bonus_builder_choose'); ?></p>
					<h3 class="custom-color1"><?php echo $this->lang->line('bonus_builder_start'); ?></h3>
				</div>
				<input type="submit" value="<?php echo $this->lang->line('bonus_builder_add'); ?>" class="btn-yellow">
				<input type="submit" value="<?php echo $this->lang->line('bonus_builder_nothanks'); ?>" class="btn-plain" />
				<p class="light"><?php echo $this->lang->line('bonus_builder_onetimecharge'); ?></p>
			</div>
		</form>
	</section>
</div>