<?php 

// if there is no domain, then we need to skip this upsell
if ( ! $domain):
?>

	<!-- Submit "no_domain" form (for tracking purposes) -->
	<script type="text/javascript">
	$(document).ready(function() {
		// submit no_domain form
		$("#no_domain").submit();		// submit form
	});
	</script>

<?php
endif;

// initialize varibales
$form_submission	= '';
$attributes			= array(
	'method'	=> 'POST'
);


// this is the form used to skip this upsell if no domain is available
echo form_open(
	$form_submission,
	array('method' 		=> 'POST', 'id'	=> 'no_domain'),
	array('action_id' 	=> 48)	// Hidden Fields
);
echo form_close();


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
<div class="container traffic_upgrade" style="min-height:550px;margin:0;padding:0;border:0px none transparent;box-shadow:none;text-align:center;">
	<h3 id="traffic_upgrade_highlight_txt"><?php echo $this->lang->line('bonus_traffic_important'); ?></h3>
	<dl id="traffic_upgrade_headline">
		<dt><?php echo $this->lang->line('bonus_traffic_visitors'); ?></dt>
		<dd><?php echo $this->lang->line('bonus_traffic_send'); ?></dd>
	</dl>
	<div id="traffic_uprgrade_select_package"><?php echo $this->lang->line('bonus_traffic_select'); ?></div>

		<!--
		<div class="traffic_upsell_container">
			<h4>100,000 Website Visitors <em>$997</em> <span>(Best Value)</span></h4>
			<button type="submit">Yes, I want 100,000 website visitors for a one-time payment of <strong>$997</strong></button>
		</div>
		<div class="traffic_upsell_container recommended" style="margin-top: 20px;">
			<h4>346 Website Visitors <em>$59.99</em></h4>
			<button type="submit">Yes, I want 346 website visitors for a one-time payment of <strong>$59.99</strong></button>
		</div>
		
		<div class="traffic_upsell_container">
			<h4>999 Website Visitors <em class="red">Free</em></h4>
			<button type="submit">Yes, I want 999 visitors for free</button>
		</div>
		-->
		<div class="traffic_upsell_container" style="margin-top: 20px;">
			<h4><?php echo $this->lang->line('bonus_traffic_10000').'<em>$'.$traffic_10k.'</em>'; ?></h4>
			<?php
			// open the form
			echo form_open(
				$form_submission,
				$attributes,
				array('action_id' => 21, 'plans[]' => $page, 'name' => '10k', 'hits' => '10000', 'domain'	=> $domain, 'domain_pack_id' => $domain_pack_id)	// Hidden Fields
			);

			// No Thanks Button
			echo form_input(array(
				'name'		=> 'submit',
				'type'		=> 'submit',
				'class'		=> '',
				'value'		=> $this->lang->line('bonus_traffic_yes10000').'$'.$traffic_10k,
				'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
			));

			?>
			<!-- <button type="submit">Yes, I want 10,000 website visitors for a one-time payment of <strong>$297</strong></button> -->
			<?php echo form_close(); ?>
		</div>
	
		<div class="traffic_upsell_container" style="margin-top: 20px;">
			<span class="arrow-l"></span>
			<span class="arrow-r"></span>
			<span class="highly-recommended"><?php echo $this->lang->line('bonus_traffic_recommend'); ?></span>
			<h4><?php echo $this->lang->line('bonus_traffic_5000').'<em>$'.$traffic_5k.'</em> <span>(Most Popular Package)</span>'; ?></h4>
			<?php
			// open the form
			echo form_open(
				$form_submission,
				$attributes,
				array('action_id' => 22, 'plans[]' => $page, 'name' => '5k', 'hits' => '5000', 'domain'	=> $domain, 'domain_pack_id' => $domain_pack_id)	// Hidden Fields
			);

			// No Thanks Button
			echo form_input(array(
				'name'		=> 'submit',
				'type'		=> 'submit',
				'class'		=> '',
				'value'		=> $this->lang->line('bonus_traffic_yes5000').'$'.$traffic_5k,
				'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
			));

			?>
			<!-- <button type="submit">Yes, I want 5,000 website visitors for a one-time payment of <strong>$197</strong></button> -->
			<?php echo form_close(); ?>
		</div>
	
		<div class="traffic_upsell_container">
			<h4><?php echo $this->lang->line('bonus_traffic_1000').'<em>$'.$traffic_1k.'</em>'; ?></h4>
			<?php
			// open the form
			echo form_open(
				$form_submission,
				$attributes,
				array('action_id' => 23, 'plans[]' => $page, 'name' => '1k', 'hits' => '1000', 'domain'	=> $domain, 'domain_pack_id' => $domain_pack_id)	// Hidden Fields
			);

			// No Thanks Button
			echo form_input(array(
				'name'		=> 'submit',
				'type'		=> 'submit',
				'class'		=> '',
				'value'		=> $this->lang->line('bonus_traffic_yes1000').'$'.$traffic_1k,
				'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
			));

			?>
			<!-- <button type="submit">Yes, I want 1,000 website visitors for a one-time payment of <strong>$67</strong></button> -->
			<?php echo form_close(); ?>
		</div>
		<!--
		<div class="traffic_upsell_container">
			<h4>100 Website Visitors <em class="red">FREE</em></h4>
			<button type="submit">Yes, I want 100 website visitors for <strong>FREE</strong></button>
		</div>
		-->	
		<?php
		// check to see if this is not a one click upsell and either show or not show the no thanks
		if( ! isset($shownothanks) ) : ?>
		<div class="traffic_upsell_container">
			<h4><?php echo $this->lang->line('bonus_traffic_0'); ?></h4>
			<?php
			// open the form
			echo form_open(
				$form_submission,
				$attributes,
				array('action_id' => 24)	// Hidden Fields
			);

			// No Thanks Button
			echo form_input(array(
				'name'		=> 'submit',
				'type'		=> 'submit',
				'class'		=> '',
				'value'		=> $this->lang->line('bonus_traffic_none'),
				'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
			));

			?>
			<!-- <button type="submit">I Don't Want Any Visitors Sent to My Site</button> -->
			<?php echo form_close(); ?>
		</div>
		<?php endif;?>
		<!--
		<div id="no_thanks" style="margin:20px 0 40px;">
			<a href="/special_offer/v/">
				<span style="font-weight:normal;font-size:1em;">I Don't Want Any Visitors Sent to My Site</span>
			</a>
		</div>
		-->

	<div id="traffic_upgrade_footer_txt"><?php echo $this->lang->line('bonus_traffic_outro'); ?></div>
	
	
	
</div><!-- .container -->
