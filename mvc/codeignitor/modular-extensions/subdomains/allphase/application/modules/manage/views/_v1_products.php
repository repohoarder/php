<?php if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>'; ?>

<style type="text/css">
	.box ul li a {cursor:default;text-decoration:none;}
</style>

<h1>Manage | Products</h1>

<section id="pnl-accordion">

	<h2>Manage Products</h2>
	<form action="/manage/products" method="post">
	<div class="module s-manage-upsells">
		<div class="pad">
			<p>Your hosting company, your way. Choose one of our sales funnels from the options below and determine exactly what you want your customers to experience during the sign up process. Each funnel is tested and proven to yield high conversions and maximum value.</p><br>
			<!-- <p>To set pricing for each product and determine your revenue potential, please visit <a href="/manage/pricing">Manage Pricing</a>.</p> -->
			<h3>Pick Your Sales Funnel</h3>
			
			<div class="box">
				<h4>Basic</h4>
				<strong>Keep it Simple!</strong>
				<ul>
					<li><a href="#">Domain Privacy</a></li>
					<li><a href="#">Daily Backup</a></li>
					<li><a href="#">Weblock</a></li>
				</ul>
				<div class="bot"><input type="radio" name="funnel_id" id="chkPlanBasic"  value="3" <?php echo $default_funnel == 3 ? "checked" :'';?>/><label for="chkPlanBasic">Select this plan</label></div>
			</div>
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
				<div class="bot"><input type="radio" name="funnel_id" id="chkPlanAll"   value="5" <?php echo $default_funnel == 5 ? "checked" :'';?>/><label for="chkPlanAll">Select this plan</label></div>
			</div>
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
					<label for="chkPlanSeller">Select this plan</label>
				</div>
			</div>

			<?php /*

			<div style="font-size:0.8em;text-align:center;clear:both;margin:10px 0 20px;">
				<span style="line-height:16px;background:url(/resources/allphase/img/link-icon.png) left center no-repeat;padding-left:18px;">
					Get Link
				</span>
			</div>
			
			*/ ?>

			<p>*You may make changes to your sales funnel at any time.</p>
			<hr />
			<input type="submit" class="btn-contact" value="Update Account" />
		</div>
	</div>
	</form>

</section>