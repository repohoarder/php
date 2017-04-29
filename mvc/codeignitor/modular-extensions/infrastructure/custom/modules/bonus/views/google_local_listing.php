<?php
$form_submission	= '';
$attributes			= array(
	'method'	=> 'POST'
);
?>
<script type="text/javascript">
	
	$(document).ready(function(){

		$('form').submit(function(e) {
			if($('form').validate().checkForm()){
				show_loading_dialog();
			} else {
				e.preventDefault();
			}
		});
		
	});
</script>
<div id="t-main" role="main">
	<div class="center-width">
		<div class="content upsell">

			<?php echo $this->template->load_view('warning.php'); ?>

			<section class="module">
				<form action="" method="post">
					<h1 class="custom-bg1"><?php echo $this->lang->line('bonus_google_title'); ?></h1>
					<div class="col-l">
						<p><?php echo $this->lang->line('bonus_google_diduknow1'); ?> <strong class="txt-large custom-color1"><?php echo $this->lang->line('bonus_google_diduknow2'); ?></strong><br /> <?php echo $this->lang->line('bonus_google_diduknow3'); ?></p>
					</div>
					<div class="col-r">
						<p><strong><?php echo $this->lang->line('bonus_google_showcase1'); ?> <span class="custom-color1"><?php echo $this->lang->line('bonus_google_showcase2'); ?></span></strong></p>
						<?php
						echo form_open(
							$form_submission,
							$attributes,
							array('action_id' => 79)
						);
						?>
						<input type="hidden" name="packages[]" value="google_local_listing">
						<input type="submit" value="<?php echo $this->lang->line('bonus_google_add'); ?>" class="btn-yellow">
						<?php 
						echo form_close(); 
						// open the nothanks form
						echo form_open(
							$form_submission,
							$attributes,
							array('action_id' => 80)
						);
						?>
						<input type="submit" value="<?php echo $this->lang->line('bonus_google_nothanks'); ?>" class="btn-plain" />
						<?php echo form_close(); ?>
						<p class="light"><?php echo $this->lang->line('bonus_google_onetimecharge'); ?></p>
					</div>
				</form>
			</section>
		</div>
	</div>
</div>