<?php
// array of partner ID's that are actually Purely Affiliates (aka - don't show specific nav items)
$partner_ids 	= array(
	157,
	173,
	223
);


// set boolean to show/hide the layout
$show_layout 	= (isset($_SERVER['QUERY_STRING']) AND $_SERVER['QUERY_STRING'] == 'frame=1')
	? FALSE 
	: TRUE;

// make sure we need to show the navbar
if ($show_layout):

	// grab URL segments
	$segments 	= $this->uri->segments;

	// grab partner details from session
	$partner 	= $this->session->userdata('partner');
	?>

	<ul id="nav-main">
		<li class="nav-home <?php if ($segments[1] == 'home') echo 'active'; ?>"><a href="/home">Home</a></li>

		<?php
		## HACK
		if ( ! in_array($partner['id'],$partner_ids)):
		?>

		<li class="nav-manage <?php if ($segments[1] == 'manage') echo 'active'; ?>">
			<a href="#">Manage</a>
			<ul>
				<!-- <li <?php if ($segments[1] == 'manage' AND $segments[2] == 'pricing') echo 'class="active"'; ?>><a href="/manage/pricing">Pricing</a></li> -->
				<li <?php if ($segments[1] == 'manage' AND $segments[2] == 'account') echo 'class="active"'; ?> ><a href="/manage/account">Account</a></li>
				<li <?php if ($segments[1] == 'manage' AND ($segments[2] == 'products' OR $segments[2] == 'pricing')) echo 'class="active"'; ?>><a href="/manage/products">Products</a></li>
				<li <?php if ($segments[1] == 'manage' AND $segments[2] == 'website') echo 'class="active"'; ?> ><a href="/manage/website">Website</a></li>
				<!-- <li <?php if ($segments[1] == 'manage' AND $segments[2] == 'affiliates') echo 'class="active"'; ?>><a href="/manage/affiliates">Affiliates</a></li> -->
			</ul>
		</li>
		<!-- <li class="nav-features  <?php if ($segments[1] == 'extra' AND $segments[2] == 'features') echo 'active'; ?>"><a href="/extra/features">Extra Features</a></li> -->
		<li class="nav-costs <?php if ($segments[1] == 'operating') echo 'active'; ?>"><a href="/operating/costs">Operating Costs</a></li>
		<li class="nav-statements  <?php if ($segments[1] == 'financial' AND $segments[2] == 'statements') echo 'active'; ?>"><a href="/financial/statements">Financial Statements</a></li>
		<li class="nav-totals <?php if ($segments[1] == 'statistics' AND $segments[2] != 'visitors' AND $segments[2] != 'sales' AND $segments[2] != 'epc') echo 'active'; ?>" >
			<a href="#">Customer Support Totals</a>
			<ul>
				<li <?php if ($segments[1] == 'statistics' AND $segments[2] == 'tickets') echo 'class="active"'; ?> ><a href="/statistics/tickets">Tickets</a></li>
				<li <?php if ($segments[1] == 'statistics' AND $segments[2] == 'calls') echo 'class="active"'; ?> ><a href="/statistics/calls">Calls</a></li>
			</ul>
		</li>

		<?php
		endif;
		?>

		<li class="nav-reporting <?php if ($segments[1] == 'customer' && $segments[2] == 'data') echo 'active'; ?>" ><a href="/customer/data">Customer Reporting</a></li>
		<li class="nav-totals <?php if ($segments[1] == 'statistics' AND $segments[2] != 'tickets' AND $segments[2] != 'calls') echo 'active'; ?>" >
			<a href="#">Statistics</a>
			<ul>
				<li <?php if ($segments[1] == 'statistics' AND $segments[2] == 'epc') echo 'class="active"'; ?> ><a href="/statistics/epc">EPC</a></li>
				<li <?php if ($segments[1] == 'statistics' AND $segments[2] == 'sales') echo 'class="active"'; ?> ><a href="/statistics/sales">Sales</a></li>
				<li <?php if ($segments[1] == 'statistics' AND $segments[2] == 'recurring') echo 'class="active"'; ?>><a href="/statistics/recurring">Rebills</a></li>
				<li <?php if ($segments[1] == 'statistics' AND $segments[2] == 'visitors') echo 'class="active"'; ?>><a href="/statistics/visitors">Visitors</a></li>
				
			</ul>
		</li>
		<li class="nav-partner  <?php if ($segments[1] == 'support') echo 'active'; ?>"><a href="/support">Partner Support</a></li>
		<li class="nav-logout"><a href="/logout">Logout</a></li>
		<li class="nav-manager">
			<hgroup>
				<h2>Your Account Manager Is:</h2>
				<h3><?php echo $partner['manager']['first_name'].' '.$partner['manager']['last_name']; ?></h3>
			</hgroup>
			<img src="/resources/allphase/img/managers/<?php echo $partner['manager']['image']; ?>" alt="<?php echo $partner['manager']['first_name'].' '.$partner['manager']['last_name']; ?>" height="78" width="111" />
			<p class="email"><?php echo $partner['manager']['email']; ?></p>
			<p class="phone"><?php echo $partner['manager']['phone']; ?></p>
			<p class="chat"><?php echo $partner['manager']['skype_name']; ?></p>
			<a href="/support" class="btn-contact">Contact <?php echo $partner['manager']['first_name']; ?> Now</a>
		</li>
	</ul>

<?php
// end show/hide navbar
endif;
?>