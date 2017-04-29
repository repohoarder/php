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
<div id="personal_site_upsell">
	<div id="personal_heading">
		<h3>FREE GIFT</h3>
		<div id="personal_sitename">
			<strong class="personal_domain_blue">www.test.com</strong>
		</div>
		<div id="personal_absolutely_free">
			<strong>Get Your Own Personal Website Absolutely FREE</strong>
		</div>
		<div>
			<span>(This is a website all about <strong>you!</strong>)</span>
		</div>
	</div>

	<div id="personal_examples">
		<img src="/assets/v1/images/upsells/personal_site/site_examples.jpg" alt="Example Screenshots"/>
	</div>

	<div id="personal_content">
		<p><strong>As a special bonus for signing up today we will build you a FREE personal website.</p>
		<p>We have already reserved the domain name <span class="personal_domain_blue">www.test.com</span> for you.</strong></p>
		<p>We normally sell this personal website for $249, but today as a special bonus you will receive it absolutely FREE.</p>
		<p>This website will be a personal website all about you. You will have the ability to choose from a collection of high quality and professional-looking personal website templates. Our team will even help you set up your personal website, so there's no technical stuff you need to deal with.</p>
		<p>It's never been easier or cheaper to set up a professional-looking personal website all about you. <strong>(It doesn't get cheaper than FREE!)</strong></p>
		<p>We will add your personal website to your existing hosting account at no additional charge. The account you have allows you to host an unlimited number of websites. The only thing you will need to cover is the $9.95 for the domain name registration for www.test.com</p>
		<p>Having a personal website is more important today now than ever. If you don't already have a personal website, you need one.  <strong>It's so important for you to have a personal website today, and that's why we are going to help you set up one for FREE.</strong></p>
		<p>Click the button below that says "Yes, I Want The Free Personal Website" right now, and within the next 48 hours you can have your own personal website live on the Internet.</p>
	</div>

	<div id="personal_site_buttons">
		<form method="post" action="/special_offer/submit_personal_website">
			<input type="hidden" name="signup[additional_domains][]" value="test.com" />
			<button type="submit" class="personal_button" onclick="show_personal_privacy_popup('test.com');return false;">Yes, I Want The FREE Personal Website</button>
		</form>
	
		<div id="personal_button_separator">
			OR
		</div>
		
		<div>
			<a href="/special_offer/v/" class="personal_button">No, I Don't Want The FREE Personal Website</a>
		</div>
	</div>
	<p id="personal_footnote">When you click the YES button above, within the next 48 hours you will have your own personal website that you can be proud of. <strong>We promise it will look great!</strong></p>
</div>