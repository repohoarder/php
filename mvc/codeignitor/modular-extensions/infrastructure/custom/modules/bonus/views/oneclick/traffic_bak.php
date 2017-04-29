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
?>
	
<section class="upsell traffic oneclick">

	<h1>How Many Visitors Do You Want Us To Send To Your New Website?</h1>
	<h2>Select Your Website Traffic Package Below:</h2>
	<p>Select which traffic package below you would like to add to your account.<br />You can select when you would like to start sending traffic to your website.</p>
	<div class="outer-wrap">
		<div class="wrap">
			<h3>10,000 Website Visitors <em>$297</em></h3>
			<form action="" accept-charset="utf-8" method="POST" id="traffic-10k">
				<div style="display:none">
					<input type="hidden" name="action_id" value="21" />
					<input type="hidden" name="plans[]" value="<?php echo $page;?>" />
					<input type="hidden" name="name" value="10k" />
					<input type="hidden" name="hits" value="10000" />
					<input type="hidden" name="domain" value="<?php echo $domain;?>" />
					<input type="hidden" name="domain_pack_id" value="<?php echo $domain_pack_id;?>" />
				</div>
				<span class="checkbox"></span>
				<input type="submit" name="submit" value="Yes, I want 10,000 website visitors for a one-time payment of $297" class="" onsubmit="_gaq.push(['_linkByPost', this]);"  />
			</form>
		</div>
		<div class="wrap active">
			<span class="highly-recommended">Highly Recommended</span>
			<h3>5,000 Website Visitors <em>$197</em> <span>(Most Popular Package)</span></h3>
			<form action="" accept-charset="utf-8" method="POST" id="traffic-5k">
				<div style="display:none">
					<input type="hidden" name="action_id" value="22" />
					<input type="hidden" name="plans[]" value="<?php echo $page;?>" />
					<input type="hidden" name="name" value="5k" />
					<input type="hidden" name="hits" value="5000" />
					<input type="hidden" name="domain" value="<?php echo $domain;?>" />
					<input type="hidden" name="domain_pack_id" value="<?php echo $domain_pack_id;?>" />
				</div>
				<span class="checkbox active"></span>
				<input type="submit" name="submit" value="Yes, I want 5,000 website visitors for a one-time payment of $197" class="" onsubmit="_gaq.push(['_linkByPost', this]);"  />
			</form>
		</div>
		<div class="wrap">
			<h3>1,000 Website Visitors <em>$67</em></h3>
			<form action="" accept-charset="utf-8" method="POST" id="traffic-1k">
				<div style="display:none">
					<input type="hidden" name="action_id" value="23" />
					<input type="hidden" name="plans[]" value="<?php echo $page;?>" />
					<input type="hidden" name="name" value="1k" />
					<input type="hidden" name="hits" value="1000" />
					<input type="hidden" name="domain" value="<?php echo $domain;?>" />
					<input type="hidden" name="domain_pack_id" value="<?php echo $domain_pack_id;?>" />
				</div>
				<span class="checkbox"></span>
				<input type="submit" name="submit" value="Yes, I want 1,000 website visitors for a one-time payment of $67" class="" onsubmit="_gaq.push(['_linkByPost', this]);"  />
			</form>
		</div>
		<?php 
		
		/*
		<div class="wrap">
			<h3>0 Website Visitors</h3>
			<form action="/bonus/offer/traffic" accept-charset="utf-8" method="POST" id="traffic-none">
				<div style="display:none">
					<input type="hidden" name="action_id" value="24" />
				</div>
				<span class="checkbox"></span>
				<input type="submit" name="submit" value="I Don't Want Any Visitors Sent to My Site" class="" onsubmit="_gaq.push(['_linkByPost', this]);"  />
			</form>
		</div*/ 
		
		?>
		<a href="" id="btn-submit">Start Sending Traffic To My Website</a>
	</div>
	<p class="note">By clicking one of the buttons above, you give us permission to charge your card on file for this purchase.</p>
</section>

<script>
	$(function() {

		// Select the current faux checkbox, and deselect all others
    	$('span.checkbox').on('click', function() {
    		$('span.checkbox').each(function() {
    			$(this).removeClass('active');
    		});
    		$(this).addClass('active');
    	});

    	// The button at the bottom is clicked, redirect that click the the proper form
    	$('#btn-submit').on('click', function(e) {
    		e.preventDefault();
    		$('span.checkbox.active').parent().find('input[type="submit"]').click();
    	});
	});
</script>