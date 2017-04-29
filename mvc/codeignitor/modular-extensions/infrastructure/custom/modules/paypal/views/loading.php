<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

	<title><?php echo $this->lang->line('paypal_loading_title');?></title>

	<style type="text/css">
		
		body {text-align:center;}
		body * {font-family:arial,helvetica,sans-serif;}

		#submit_paypal {border-style:none;background:none;cursor:pointer;color:#036;text-decoration:underline;}
		body.js_enabled #submit_paypal span {display:none;font-size:12px;}

		div {padding-top:30px;}

		strong {font-size:16px;font-weight:bold;}

		#loading {background:url('/resources/brainhost/img/loading.gif') 0 -150px no-repeat;height:90px;width:400px;display:block;margin:0 auto;}

	</style>

</head>

<body>

	<script type="text/javascript">

		document.body.className = "js_enabled";

	</script>

	<div>

		<strong><?php echo $this->lang->line('paypal_loading_title');?></strong>
		

		<span id="loading"></span>

		<form method="post" action="<?php echo $paypal['payment_url']; ?>" id="paypal">

			<input type="hidden" name="cmd" value="_xclick-subscriptions" />

			<input type="hidden" name="no_note" value="1" />
			<input type="hidden" name="rm" value="1" /> <?php # 0 for $_GET, 1 with redirect & no $_GET, 2 for $_POST ?>
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="src" value="1" />

			<input type="hidden" name="image_url" value="<?php echo $paypal['logo']; ?>" />
			<input type="hidden" name="notify_url" value="<?php echo $paypal['ipn_url']; ?>" />
			<input type="hidden" name="return" value="<?php echo $paypal['return_url']; ?>" />
			<input type="hidden" name="cancel_return" value="<?php echo $paypal['cancel_url']; ?>" />
			<input type="hidden" name="business" value="<?php echo $paypal['business_acct']; ?>" />



			<input type="hidden" name="a1" value="<?php echo $paypal['invoice_total']; ?>" />
			<input type="hidden" name="p1" value="<?php echo $paypal['hosting_months']; ?>" />
			<input type="hidden" name="t1" value="M" /> 


			<input type="hidden" name="a3" value="<?php echo $paypal['hosting_total'];?>" />
			<input type="hidden" name="p3" value="<?php echo $paypal['hosting_months']; ?>" />
			<input type="hidden" name="t3" value="M" />


			<input type="hidden" name="custom" value='<?php echo $paypal['custom_data']; ?>' />
			 
			<input type="hidden" name="first_name" value="<?php echo $paypal['first_name']; ?>" />
			<input type="hidden" name="last_name" value="<?php echo $paypal['last_name']; ?>" />

			<input type="hidden" name="address1" value="<?php echo $paypal['address']; ?>" />
			<input type="hidden" name="city" value="<?php echo $paypal['city']; ?>" />
			<input type="hidden" name="state" value="<?php echo $paypal['state']; ?>" />
			<input type="hidden" name="country" value="<?php echo $paypal['country']; ?>" />
			<input type="hidden" name="zip" value="<?php echo $paypal['zip']; ?>" />

			<input type="hidden" name="email" value="<?php echo $paypal['email']; ?>" />

			<input type="hidden" name="item_name" value="<?php echo $paypal['item_name']; ?>" />			

			<button id="submit_paypal" type="submit" name="authclick" value="Pay Now via PayPal">

				<span><?php echo $this->lang->line('paypal_redirection');?></span>

			</button>

		</form>

		<script src="/resources/brainhost/js/libs/jquery-1.8.1.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			
			$(document).ready(function(){

				$('#submit_paypal').click();

			});

		</script>

	</div>

</body>

</html>