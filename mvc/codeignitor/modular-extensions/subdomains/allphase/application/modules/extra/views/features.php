<?php if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>'; ?>

<h1>Extra Features</h1>

<section id="pnl-accordion">

	<h2>Extra Features</h2>
	<div class="module s-extra-features">
		<div class="pad">
			<p>We know how important your customers are to your company, and we now offer special bonus features that will improve your customer experience! Select and add extra features to your account.</p>
			<form action="/extra/features" method="post">
				<div class="box">
					<div class="col-l">
						<h4>Dedicated Toll Free Number</h4>
						<img src="/resources/allphase/img/icon-phone2.png" alt="Dedicated Toll Free Number" />
						<p>Description..get your own toll free number, specific to your brand. We will automatically post your phone number on your website’s terms of service pages.</p>
						<p><strong>Now only $XX.XX/month</strong></p>
					</div>
					<div class="col-r">
						<div class="bot">
							<?php if( ! in_array(99,$services)) : ?>
							<input type="checkbox" value="99" name="plan[]" id="chkPlanBasic" /><label for="chkPlanBasic">Add to account</label>
							<?php else:?>
							Activated
							<?php 
							endif;
							?>
						</div>
					</div>
				</div>
				<div class="box">
					<div class="col-l">
						<h4>Phone Support</h4>
						<img src="/resources/allphase/img/icon-headset.png" alt="" />
						<p>Description..get your own toll free number, specific to your brand. We will automatically post your phone number on your website’s terms of service pages.</p>
						<p><strong>Now only $XX.XX/month</strong></p>
					</div>
					<div class="col-r">
						<div class="bot">
						<?php if( ! in_array(104,$services)) : ?>
							<input type="checkbox" value="104" name="plan[]" id="chkPlanBasic" /><label for="chkPlanBasic">Add to account</label>
						<?php else:?>
							Activated
							<?php 
							endif;
							?>
						</div>
					</div>
				</div>
				<div class="box">
					<div class="col-l">
						<h4>Dedicated Toll Free Number</h4>
						<img src="/resources/allphase/img/icon-chat2.png" alt="" />
						<p>Description..get your own toll free number, specific to your brand. We will automatically post your phone number on your website’s terms of service pages.</p>
						<p><strong>Now only $XX.XX/month</strong></p>
					</div>
					<div class="col-r">
						<div class="bot">
						<?php if( ! in_array(105,$services)) : ?>	
							<input type="checkbox" value="105" name="plan[]" id="chkPlanBasic" /><label for="chkPlanBasic">Add to account</label>
						<?php else:?>
							Activated
							<?php 
							endif;
							?>
						</div>
					</div>
				</div>
				<input type="submit" class="btn-contact" value="Update Account" />
			</form>
		</div>
	</div>
</section>