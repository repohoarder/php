<?php

// this function formats the domain name so that it upper cases the CITY within the name
function format_domain_stupidly($domain)
{
	/*
	$replace 	= array(
		'GREEN'		=> 'green',
		'OFFERS'	=> 'offers',
		'COM'		=> 'com',
		'NET'		=> 'net',
		'ORG'		=> 'org',
		'INFO'		=> 'info'
	);

	// upper the entire domain
	$domain 	= strtoupper($domain);

	// iterate all things to find/replace
	foreach ($replace AS $key => $value):

		// string replace variables
		$domain 	= str_replace($key,$value,$domain);

	endforeach;
	*/

	// return domain
	return $domain;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title></title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

	<style type="text/css">
		* {background:none;}
		input, select {padding:5px;background:#fff;border:1px solid #484848;}
		body {font-size:14px;font-family:Arial, Helvetica, sans-serif;}
		label {float:left;width:100%;}
		.custom_domain_toggle {display:none;}
		.custom_domain {display:block;}
		form {float:left;}
		.btn-submit {
			margin:20px 0 20px 20px;
			color: #F9EE06;
			padding: 10px 20px;
			font-weight:bold;
			background: -moz-linear-gradient(
				top,
				#95060A 0%,
				#95060A);
			background: -webkit-gradient(
				linear, left top, left bottom, 
				from(#95060A),
				to(#95060A));
			-moz-border-radius: 14px;
			-webkit-border-radius: 14px;
			border-radius: 14px;
			border: 1px solid #666666;
			-moz-box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 2px rgba(255,255,255,1);
			-webkit-box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 2px rgba(255,255,255,1);
			box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 2px rgba(255,255,255,1);
			text-shadow:
				0px -1px 0px rgba(000,000,000,0.2),
				0px 1px 0px rgba(255,255,255,0.4);
			cursor: pointer;
		}
		.btn-submit:hover {background:#F9EE06;color:#95060A;}
		.domain-name {font-family:Arial, "Helvetica Neue", Helvetica, sans-serif;font-size:30px;color:#212121;font-style:italic;}
			.domain-name strong {text-decoration:underline;font-style:normal;color:#ab0b0b;}
	</style>
</head>
<body>

		<?php $i = 0; ?>
		<?php foreach ($suggestions as $key => $value): ?>
			<label>
				<form action="https://orders.purelyhosting.com/signup" method="post" target="_top" id="form_<?php echo $key; ?>" style="text-align:center;float:left;width:100%;">
					<input type="hidden" name="signup[sugg_domain_name]" value="<?php echo $value; ?>" <?php if ($i == 0) { ?>checked="checked"<?php } ?> />
					
					<p style="float:left;width:100%;text-align:center;">
						<span class="domain-name"><?php echo format_domain_stupidly($value); ?> <strong>Is Available!</strong></span><br />
						<a href="javascript: void(0);" style="font-size:18px;" onclick='document.forms["form_<?php echo $key; ?>"].submit();'>Click Here Get This Domain and Website Now</a>
					</p>

					<input type="hidden" value="new" name="xfer_or_new"/>
					<input type="hidden" value="1" name="suggestion"/>
				</form>
			</label>
			<?php $i++; ?>
		<?php endforeach; ?>

		<form action="https:orders.purelyhosting.com/signup" style="text-align:center;float:left;width:100%;">
			<input type="image" style="padding:0;border:none;" value="Go Here To Choose Your Own Domain and Website Now!" src="/resources/modules/spinner/assets/click-here.png" />
		</form>

	<div style="float:left;width:100%;">

		<!-- <a href="#" class="custom_domain_toggle">No thanks, I want to choose a different domain.</a> -->
	</div>

	<form action="https://orders.purelyhosting.com/signup" method="post" class="custom_domain" target="_top">

		<input type="text" value="" name="signup[domain_name]"/>

		<select name="signup[domain_extension]">

			<option selected="selected" value="com">.com</option>
			<option value="net">.net</option>
			<option value="org">.org</option>
			<option value="biz">.biz</option>
			<option value="info">.info</option>

		</select>

		<input type="hidden" value="new" name="xfer_or_new"/>

		<input type="submit" class="btn-submit" value="Submit" style="margin:0 0 0 20px;" />

	</form>

	<script type="text/javascript">

		$(document).ready(function(){

			$('.custom_domain_toggle').show();
			$('.custom_domain').hide();

			$('.custom_domain_toggle').click(function(){

				$('.custom_domain_toggle').slideUp();
				$('.custom_domain').slideDown();

			});

		});

	</script>

</body>
</html>