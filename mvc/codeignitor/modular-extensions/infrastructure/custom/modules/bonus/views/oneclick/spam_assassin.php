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
<section class="upsell spam-assassin">
	<h1>Protect Against Spam, Viruses, and Malware</h1>
	<h2>Your website and email accounts are at risk! Spammers are targeting you right now, and there's nothing you can do about it... until now.</h2>
	<div class="wrap">
		<p><strong>Spam Assassin</strong> protects your website and email account from spammers, viruses, spyware, and malware. It's the easiest way to stay spam free!</p>
		<span class="price">Only $<?php echo $price;?>/yr</span>
	</div>
	<?php
	// open the form
	echo form_open(
		$form_submission,
		$attributes,
		array('action_id' => 90, 'domain'	=> $domain, 'plans[]' => $page)	// Hidden Fields
	);
	?>
		<input type="submit" value="Yes! Protect my website and email with Spam Assassin" />
	<?php echo form_close(); ?>
	<p class="note">By clicking one of the buttons above, you give us permission to charge your card on file for this purchase.</p>
</section>

