<?php

// grab partner info
$partner_info	= $this->session->userdata('partner_info');

// grab partner funne info
$partner_funnel_info	= $this->session->userdata('partner_funnel_info');

// grab company
$company		= (isset($partner_info['website']['company_name']) AND ! empty($partner_info['website']['company_name']))? $partner_info['website']['company_name']: 'All Phase Web Hosting, LLC';

?>
<!doctype html>
<html lang="en-us" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title><?php echo $company; ?></title>

		<!-- Meta Information -->
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
		<meta content="" name="keywords" />
		<meta content="" />
		<meta name="robots" content="index,follow">

		<!-- Always force latest IE rendering engine & Chrome Frame -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<!-- Mobile First -->
		<meta name="viewport" content="width=device-width,initial-scale=1">

		<!-- Icons -->
		<link href="favicon.ico" rel="shortcut icon" />
		<link href="favicon.png" rel="icon" type="image/png" />

		<?php if (isset($partner_funnel_info['white_label']) && $partner_funnel_info['white_label']): ?><link rel="stylesheet" href="/resources/allphase_funnel/css/no-branding.css" /><?php endif; ?>
		
		
		<!-- Stylesheet with IE speed hack -->
		<!--[if IE]><![endif]-->
		<link rel="stylesheet" href="/resources/allphase_funnel/css/style.css" />
		

		<!-- Load jQuery & jQuery UI from Google CDN -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script type="text/javascript" src="/resources/allphase_funnel/js/jquery-1.8.3.min.js"><\/script>')</script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script>window.jQuery.ui || document.write('<script type="text/javascript" src="/resources/allphase_funnel/js/jqueryui01.9.2.min.js"><\/script>')</script>

		<!-- Modernizr to make older browsers behave -->
		<script src="/resources/allphase_full_funnel/js/modernizr-2.6.2.min.js"></script>

		<!-- Validation -->
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/additional-methods.min.js"></script>

		<!-- Custom jQuery -->
		<script src="/resources/allphase_funnel/js/script.js"></script>
		<script type="text/javascript" src="/resources/brainhost/js/loading.js"></script>
		
		<?php echo $template['metadata']; ?>
		<!-- Remove these later -->
		<link rel="stylesheet" href="/resources/brainhost/js/jquery-ui.css"/>
		<style>
		#accordion ul.hosting-package li strong {bottom: -24px;left: 0;}
			h1 {color: #1D4D87;text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);text-align: center;padding: 15px 20px;float: left;width: 100%;margin:0;font-weight: normal;font-size: 22px;border: 1px solid #8FB9D0;padding: 15px 20px;background:#D1F1F8 url(../assets/img/bg-steps.png) 0 0 repeat-y;}
			html, body, h2, h3, h4, h5, h6 {margin: 0;padding: 0;}
		</style>

		<!-- Plugin initialization -->
		<script>
			$(function() {
				// Initialize accordion (jQuery UI)
				$('#accordion').accordion({
					active: 0,
					header: 'h2',
					heightStyle: 'content'
				});

				// Initialize form validation
				$("#frmSetup").validate({
				    errorPlacement: function(error,element) {
                       return true;
                    }
				});
			});
		</script>
		<link rel="stylesheet" href="/resources/allphase_funnel/css/override.css" />
		
		<?php if (isset($partner_funnel_info['exit_pop']) && $partner_funnel_info['exit_pop']): ?><script type="text/javascript" src="/resources/allphase_funnel/js/exitpop.js"></script><?php endif; ?>
	</head>
	<body itemscope itemtype="http://schema.org/WebPage">