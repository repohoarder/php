<?php if ($error): ?>

	<p style="font-weight:bold;color:red;"><?php echo $error; ?></p>

<?php endif; ?>

<h1>Manage | Products</h1>

<section id="pnl-accordion">

	<h2>Manage Products</h2>
	<form action="/manage/products" method="post">
	<div class="module s-manage-upsells" id="manage-products">
		<div class="pad">
			<p>Your hosting company, your way. Choose one of our sales funnels from the options below and determine exactly what you want your customers to experience during the sign up process. Each funnel is tested and proven to yield high conversions and maximum value.</p><br>
			<!-- <p>To set pricing for each product and determine your revenue potential, please visit <a href="/manage/pricing">Manage Pricing</a>.</p> -->
			<h3>Pick Your Sales Funnel</h3>

			<input id="funnel-url" name="funnel-url" type="hidden" value="https://infrastructure.hostingaccountsetup.com/initialize/<?php echo $partner["id"];?>/"/>
			
			<div class="box-wrap">

				<div class="box">
					<h4>Basic</h4>
					<strong>Keep it Simple!</strong>
					<ul>
						<li><a href="#">Domain Privacy</a></li>
						<li><a href="#">Daily Backup</a></li>
						<li><a href="#">Weblock</a></li>
					</ul>
					<div class="bot"><input type="radio" name="funnel_id" id="chkPlanBasic"  value="3" <?php echo $default_funnel == 3 ? "checked" :'';?>/><label for="chkPlanBasic">Set as Default</label></div>
				</div>

				<div class="funnel-link">
					<span>
						Get Link
					</span>
				</div>

			</div>

			<div class="box-wrap">

				<div class="box">
					<h4>All Inclusive</h4>
					<strong>Highest Profits!</strong>
					<ul>
						<li><a href="#">Domain Privacy</a></li>
						<li><a href="#">Daily Backup</a></li>
						<li><a href="#">Weblock</a></li>
						<li><a href="#">Platinum Package</a></li>
						<li><a href="#">Platinum Package 50% Off</a></li>
						<li><a href="#">SEO Package</a></li>
						<li><a href="#">Traffic Packages 1k-20k Hits</a></li>
					</ul>
					<div class="bot"><input type="radio" name="funnel_id" id="chkPlanAll"   value="5" <?php echo $default_funnel == 5 ? "checked" :'';?>/><label for="chkPlanAll">Set as Default</label></div>
				</div>

				<div class="funnel-link">
					<span>
						Get Link
					</span>
				</div>

			</div>

			<div class="box-wrap">

				<div class="box">
					<h4>Advanced</h4>
					<strong>Best Seller!</strong>
					<ul>
						<li><a href="#">Domain Privacy</a></li>
						<li><a href="#">Daily Backup</a></li>
						<li><a href="#">Weblock</a></li>
						<li><a href="#">Platinum Package</a></li>
						<li><a href="#">Platinum Package 50% Off</a></li>
					</ul>
					<div class="bot">
						<input type="radio" name="funnel_id" id="chkPlanSeller" value="4" <?php echo $default_funnel == 4 ? "checked" :'';?>/>
						<label for="chkPlanSeller">Set as Default</label>
					</div>
				</div>

				<div class="funnel-link">
					<span>
						Get Link
					</span>
				</div>

			</div>

			<div id="funnel-hidden">
				Link: <input type="text" style="margin:0px;" size="60" value="https://infrastructure.hostingaccountsetup.com/initialize/<?php echo $partner['id'];?>/5" readonly/> <span id="copy">Copy</span>
			</div>

			<p id="funnel-footnote">*You may make changes to your sales funnel at any time.</p>
			<hr />
			<input type="submit" class="btn-contact" value="Update Account" />
		</div>
	</div>
	</form>

</section>