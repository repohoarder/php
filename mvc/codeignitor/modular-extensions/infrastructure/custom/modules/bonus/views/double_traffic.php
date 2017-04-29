<?php
//$this->debug->show($double,true);
?>

<script type="text/javascript">
	
	$(document).ready(function(){

		$('form').submit(function(e) {
			if($('form').validate().checkForm()){
				$('#facebox_overlay').hide();
				$('#promo-offer-info').hide();
				show_loading_dialog();
			} else {
				e.preventDefault();
			}
		});
	});
</script>




<img src="/resources/modules/bonus/assets/img/img-double-traffic.jpg" alt="Page background" style="display:block;margin:0 auto;" />





<!-- Double begins here -->


<div id="facebox_overlay" class="facebox_hide facebox_overlayBG" style="display: block; opacity: 0.7; z-index: 400;"></div>



<div id="promo-offer-info" style="display: block; top: 10px; position: absolute;">
<div class="promo-offer-info-top-white">&nbsp;</div>
<div class="promo-offer-info-mid-white">
<div id="double_container">

<div id="traffic_wait">
		<?php echo $this->lang->line('bonus_d_traffic_wait'); ?>
	</div>
	
	<strong>
		<?php echo $this->lang->line('bonus_d_traffic_decision'); ?>
	</strong>
	
	<div class="blue_traffic_box">
		<div class="blue_traffic_inner">
			<?php echo $this->lang->line('bonus_d_traffic_purchased', number_format($double['old_hits'],0,'.',',')); ?>
		</div>
	</div>
	
	<h2><?php echo $this->lang->line('bonus_d_traffic_double'); ?></h2>
	
	<div class="blue_traffic_box">
		<div class="blue_traffic_inner" id="get_more">
			<?php echo $this->lang->line('bonus_d_traffic_more', number_format($double['old_hits'],0,'.',','), number_format($double['old_price'],0), ($double['price'] - $double['old_price'])); ?>
		</div>
	</div>
	
	<div id="traffic_start_date" class="traffic_blue_text">
		<?php echo $this->lang->line('bonus_d_traffic_select'); ?>
	</div>


<?php
// YES button
echo form_open(
	'',
	array('method' 		=> 'POST'),
	array('action_id' 	=> 50, 'name' => $double['name'], 'hits' => $double['traffic_hits'], 'domain'	=> $domain, 'domain_pack_id' => $domain_pack_id, 'plans[]' => $page)	// Hidden Fields
);
?>	
	<button type="submit" class="loading_modal"><?php echo $this->lang->line('bonus_d_traffic_yes'); ?></button>

<?php
echo form_close();
?>
	
</div>
	

<div id="no_thanks">
<?php

// NO button
echo form_open(
	'',
	array('method' 		=> 'POST'),
	array('action_id' 	=> 51)	// Hidden Fields
);

// No Thanks Button
echo form_input(array(
	'name'		=> 'submit',
	'type'		=> 'submit',
	'class'		=> 'lbl-nothanks',
	'id'		=> 'lbl-nothanks',
	'value'		=> $this->lang->line('bonus_d_traffic_nothanks'),
	'style'		=> 'font-weight:bold;color:black;',
	'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
));

?>
	<!-- <a href="/special_offer/v/1/5">No Thanks. Take me to my order confirmation.</a> -->

<?php
echo form_close();
?>

</div>
<div class="promo-offer-info-bottom-white">&nbsp; </div>
</div>
</div>

		</section>