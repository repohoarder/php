
<?php
	// grab partner info (for display)
	$partner 	= $this->session->userdata('partner');

	$partner_stats = $this->platform->post('partner/account/stats/'.$partner['id']);
	$partner_stats = $partner_stats['data'];
	
?>

<header id="t-branding" role="banner">
	<div class="wrap">
		<div class="pad">
			<img src="/resources/allphase/img/logo.png" alt="All Phase Web Hosting, LLC | Partners" />
			<a href="/logout" class="logout">Logout</a>
		</div>
	</div>
</header>
<aside id="t-overview">
	<div class="wrap">
		<ul>
			<li><strong>Welcome, <?php echo $partner['first_name'].' '.$partner['last_name']; ?>!</strong></li>
			<li><?php echo date("F d, Y"); ?></li>
			<li>Orders: <?php echo $partner_stats['orders']; ?></li>
			<!--<li>New Customers: <?php echo $partner_stats['new_customers']; ?></li> -->
			<li>Revenue: $<?php echo number_format($partner_stats['revenue'], 2, '.', ','); ?></li>
			<li>Estimated Enterprise Value: $<?php echo number_format($partner_stats['ibida'], 2, '.', ','); ?></li>
		</ul>
	</div>
</aside>