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
<section class="upsell se-submission">
	<h1>Get Your Website Found By Search Engines</h1>
	<h2>Search engines are the #1 source of website visitors, and you need plenty of website visitors to ensure your site’s success.</h2>
	<img src="/resources/brainhost/img/img-se-submission.jpg" alt="Google, Yahoo!, Bing" />
	<div class="wrap">
		<h3>But don't panic. THERE IS AN EASIER WAY!</h3>
		<p>Now, you can let us do all the hard work for you.  We'll make sure your website is found by all the major search engines so you can get the visitors you need to make your site a success.</p>
		<span class="price">Only $<?php echo $price;?>/yr</span>
	</div>
	<?php
	// open the form
	echo form_open(
		$form_submission,
		$attributes,
		array('action_id' => 77, 'domain'	=> $domain, 'plans[]' => $page, 'domain_pack_id' => $domain_pack_id)	// Hidden Fields
	);
	?>
		<input type="submit" value="Yes! Get me listed in the search engines!" />
	<?php echo form_close(); ?>
	<p class="note">By clicking one of the buttons above, you give us permission to charge your card on file for this purchase.</p>
</section>