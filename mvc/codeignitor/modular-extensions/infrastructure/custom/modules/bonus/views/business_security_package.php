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
			$("#action_id_form").val('73');
			document.getElementById('secform').submit;
		});
	});
</script>
<div id="t-main" role="main">
	<div class="center-width">
		<div class="content upsell">
			
			<?php echo $this->template->load_view('warning.php'); ?>

			<section class="module">
				<form action="" method="post" name="secform" id="secform">
					<h1 class="custom-bg1"><?php echo $this->lang->line('bonus_b_security_title'); ?></h1>
					<div class="col-l">
						<p><?php echo $this->lang->line('bonus_b_security_intro', '$'.$price); ?></p>
						<p><?php echo $this->lang->line('bonus_b_security_diduknow1'); ?><br /> <strong class="txt-large custom-color1"><?php echo $this->lang->line('bonus_b_security_diduknow2'); ?></strong><br /> <?php echo $this->lang->line('bonus_b_security_diduknow3'); ?></p>
					</div>
					<div class="col-r">
						<p>Includes:</p>
						<?php
						
						// load individual price blocks
						$this->load->view(
						'bonus/business_security_block',
							array(
								'slug' 			=> 'lightning_fast_servers', 
								'partner_id' 	=> $partner_id,
								'funnel_id'		=> $funnel_id,
								'affiliate_id'	=> $affiliate_id,
								'offer_id'		=> $offer_id,
								'variant'		=> 'default',
								'term'			=> '12'
							)
						);
						$this->load->view(
						'bonus/business_security_block',
							array(
								'slug' 			=> 'private_ssl', 
								'partner_id' 	=> $partner_id,
								'funnel_id'		=> $funnel_id,
								'affiliate_id'	=> $affiliate_id,
								'offer_id'		=> $offer_id,
								'variant'		=> 'default',
								'term'			=> '12'
							)
						);
						$this->load->view(
						'bonus/business_security_block',
							array(
								'slug' 			=> 'dedicated_ip', 
								'partner_id' 	=> $partner_id,
								'funnel_id'		=> $funnel_id,
								'affiliate_id'	=> $affiliate_id,
								'offer_id'		=> $offer_id,
								'variant'		=> 'default',
								'term'			=> '12'
							)
						);
						$this->load->view(
						'bonus/business_security_block',
							array(
								'slug' 			=> 'spam_assassin', 
								'partner_id' 	=> $partner_id,
								'funnel_id'		=> $funnel_id,
								'affiliate_id'	=> $affiliate_id,
								'offer_id'		=> $offer_id,
								'variant'		=> 'default',
								'term'			=> '12'
							)
						);
						?>
						<?php 
						$ogprice = $price + 10;
						?>
						<hr />
						<h2><span class="custom-color1">All for only</span> <del>$<?php echo number_format($ogprice,2); ?></del> <span class="custom-color1">$<?php echo number_format($price,2); ?></span></h2>
						<input type="submit" value="<?php echo $this->lang->line('bonus_b_security_add_package'); ?>" class="btn-yellow" name="allforone">

						<span class="or">- Or -</span>
						<input type="button" value="<?php echo $this->lang->line('bonus_b_security_add'); ?>" class="btn-gray" name="individual" id="individual" />
						<input type="hidden" name="action_id" id="action_id_form" value='72'>
						</form>
						<?php
						// open the nothanks form
						echo form_open(
							$form_submission,
							$attributes,
							array('action_id' => 74)
						);
						?>
						<input type="submit" value="<?php echo $this->lang->line('bonus_b_security_nothanks'); ?>" class="btn-plain" />
						<?php echo form_close(); ?>
						
						<p class="light"><?php echo $this->lang->line('bonus_b_security_onetimecharge'); ?></p>
					</div>
				</form>
			</section>
		</div>
	</div>
</div>