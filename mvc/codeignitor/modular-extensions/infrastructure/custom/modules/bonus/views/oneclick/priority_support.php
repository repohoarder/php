<?php 
// initialize varibales
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
<section class="upsell priority-support">
	<h1>Stop Waiting for Website Help</h1>
	<h2>Your website is your livelihood, so when questions or issues arise you can't afford to wait around for help. </h2>
	<div class="wrap">
		<p>Get <strong>Priority Support</strong> now and your help tickets will be answered within 30 minutes or less – guaranteed!</p>
		<span class="price">Only $<?php echo $price;?>/yr</span>
	</div>
	<?php
	// open the form
	echo form_open(
		$form_submission,
		$attributes,
		array('action_id' => 89, 'domain'	=> $domain, 'plans[]' => $page)	// Hidden Fields
	);
	?>
		<input type="submit" value="Yes! Give me priority support!" />
	<?php echo form_close(); ?>
	<p class="note">By clicking one of the buttons above, you give us permission to charge your card on file for this purchase.</p>
</section>