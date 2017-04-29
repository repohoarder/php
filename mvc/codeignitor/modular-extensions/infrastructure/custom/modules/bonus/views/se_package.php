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
			document.getElementById('sepackfrm').submit();
		});
	});
</script>
<div class="content upsell-se-package">
	<?php echo $this->template->load_view('warning.php'); ?>
	<section class="module">
		<form action="" method="post" id="sepackfrm" name="sepackfrm">
			<h1 class="custom-bg1"><?php echo $this->lang->line('bonus_se_title'); ?></h1>
			<div class="col-l">
				<p><?php echo $this->lang->line('bonus_se_contains'); ?></p>
				<p><?php echo $this->lang->line('bonus_se_diduknow1'); ?> <strong class="txt-large custom-color1"><?php echo $this->lang->line('bonus_se_diduknow2'); ?></strong><br /> <?php echo $this->lang->line('bonus_se_diduknow3'); ?></p>
			</div>
			<div class="col-r">
				<h2><?php echo $this->lang->line('bonus_se_includes'); ?></h2>
				<table>
					<?php
					// load individual price blocks
						$this->load->view(
						'bonus/se_package_block',
							array(
								'slug' 			=> 'directory_listing', 
								'partner_id' 	=> $partner_id,
								'funnel_id'		=> $funnel_id,
								'affiliate_id'	=> $affiliate_id,
								'offer_id'		=> $offer_id,
								'variant'		=> 'default',
								'term'			=> '12'
							)
						);
						// load individual price blocks
						$this->load->view(
						'bonus/se_package_block',
							array(
								'slug' 			=> 'search_engine_submission', 
								'partner_id' 	=> $partner_id,
								'funnel_id'		=> $funnel_id,
								'affiliate_id'	=> $affiliate_id,
								'offer_id'		=> $offer_id,
								'variant'		=> 'default',
								'term'			=> '12'
							)
						);
						// load individual price blocks
						$this->load->view(
						'bonus/se_package_block',
							array(
								'slug' 			=> 'google_local_listing', 
								'partner_id' 	=> $partner_id,
								'funnel_id'		=> $funnel_id,
								'affiliate_id'	=> $affiliate_id,
								'offer_id'		=> $offer_id,
								'variant'		=> 'default',
								'term'			=> '12'
							)
						);
					?>
				<?php $ogprice = $price + 10;?>
				</table>
				<h2><span class="custom-color1"><?php echo $this->lang->line('bonus_se_foronly'); ?></span> <del>$<?php echo number_format($ogprice,2); ?></del> <span class="custom-color1">$<?php echo number_format($price,2); ?></span></h2>
				<input type="submit" value="<?php echo $this->lang->line('bonus_se_addall'); ?>" class="btn-yellow">
				<span class="or"></span>
				<input type="hidden" name="action_id" id="action_id_form" value='64'>
				<input type="button" value="<?php echo $this->lang->line('bonus_se_add'); ?>" class="btn-gray" id="individual"/>
				</form>
						<?php
						// open the nothanks form
						echo form_open(
							$form_submission,
							$attributes,
							array('action_id' => 66)
						);
						?>
		<?php
					// check to see if this is not a one click upsell and either show or not show the no thanks
					if( ! isset($shownothanks) ) : ?>
						<input type="submit" value="<?php echo $this->lang->line('bonus_se_nothanks'); ?>" class="btn-plain" />
						<?php endif;
						echo form_close(); ?>
				<p class="light"><?php echo $this->lang->line('bonus_se_onetimecharge'); ?></p>
			</div>
		</form>
	</section>
</div>