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
		$("#individual").click(function(){
			$("#action_id_form").val('65');
			document.getElementById('sepackfrm').submit;
		});
	});
</script>
<div id="t-main" role="main"> 
	<div class="center-width">
		<div class="content upsell">

			<?php echo $this->template->load_view('warning.php'); ?>

			<section class="module">
				
					<h1 class="custom-bg1"><?php echo $this->lang->line('bonus_se_sub_title'); ?></h1>
					<div class="col-l">
						<p><?php echo $this->lang->line('bonus_se_sub_diduknow1'); ?> <br /><strong class="txt-large custom-color1"><?php echo $this->lang->line('bonus_se_sub_diduknow2'); ?></strong><br /> <?php echo $this->lang->line('bonus_se_sub_diduknow3'); ?></p>
					</div>
					<div class="col-r">
						<p><?php echo $this->lang->line('bonus_se_sub_find1'); ?> <span class="custom-color1"><?php echo $this->lang->line('bonus_se_sub_find2', '$'.$price); ?></span></p>
						
						<?php
						// open the nothanks form
						echo form_open(
							$form_submission,
							$attributes,
							array('action_id' => 77)
						);
						?>
							<input type="submit" value="<?php echo $this->lang->line('bonus_se_sub_add'); ?>" class="btn-yellow" name="addpack">
							<input type="hidden" name="packages[]" value="search_engine_submission">
						<?php 
						echo form_close(); 
						// open the nothanks form
						echo form_open(
							$form_submission,
							$attributes,
							array('action_id' => 78)
						);
						?>
						<input type="submit" value="<?php echo $this->lang->line('bonus_se_sub_nothanks'); ?>" class="btn-plain" />
						<?php echo form_close(); ?>
						<p class="light"><?php echo $this->lang->line('bonus_se_sub_onetimecharge'); ?></p>
					</div>
			</section>
		</div>
	</div>
</div>