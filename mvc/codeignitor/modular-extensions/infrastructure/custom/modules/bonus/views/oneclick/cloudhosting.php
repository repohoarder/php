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
<section class="upsell cloud-transfer">
	<h1>Transfer Your Website To The Cloud</h1>
	<h2>The Best &amp; Fastest Hosting Solution Available</h2>
	<div class="wrap">
		<h3>Why cloud hosting ?</h3>
		<ul>
			<li>Rank Better In Google, Yahoo, Bing</li>
			<li>Better Rankings = More Money!</li>
			<li>Faster &amp; More Reliable</li>
			<li>Stable &amp; Secure</li>
		<span class="price">
			<del>$197/yr.</del><br />
			Only $<?php echo $price;?>/yr.
		</span>
	</div>

	<?php
	// open the form
	echo form_open(
		$form_submission,
		$attributes,
		array(
			'action_id' => 91, 
			'domain'	=> $domain, 
			'plans[]' => $page
		)	// Hidden Fields
	);
	?>

		<input type="submit" value="Yes! Transfer My Website to the Cloud!" />

	<?php echo form_close(); ?>

	<p class="note">By clicking one of the buttons above, you give us permission to charge your card on file for this purchase.</p>
</section>
