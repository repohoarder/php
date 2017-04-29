<?php
// fix for subdirectories
$subdir 	= ($this->config->item('subdir'))? $this->config->item('subdir'): '';
?>

<!doctype html>
<html lang="en-us" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>All Phase Hosting</title>

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

		<!-- Stylesheet with IE speed hack -->
		<!--[if IE]><![endif]-->
		<link rel="stylesheet" href="<?php echo $subdir; ?>/resources/allphase_full_funnel/css/style.css" />

		<!-- Load jQuery & jQuery UI from Google CDN -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script type="text/javascript" src="<?php echo $subdir; ?>/resources/allphase_full_funnel/js/jquery-1.8.3.min.js"><\/script>')</script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script>window.jQuery.ui || document.write('<script type="text/javascript" src="<?php echo $subdir; ?>/resources/allphase_full_funnel/js/jqueryui01.9.2.min.js"><\/script>')</script>

		<!-- Modernizr to make older browsers behave -->
		<script src="<?php echo $subdir; ?>/resources/allphase_full_funnel/js/modernizr-2.6.2.min.js"></script>

		<!-- Validation -->
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
		<script>$(window).validate() || document.write('<script src="<?php echo $subdir; ?>/resources/allphase_full_funnel/js/jquery.validate.min.js"><\/script>')</script>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/additional-methods.min.js"></script>
		<script>$(window).validate() || document.write('<script src="<?php echo $subdir; ?>/resources/allphase_full_funnel/js/jquery.validate.additional.min.js"><\/script>')</script>

		<!-- Custom jQuery -->
		<script src="<?php echo $subdir; ?>/resources/allphase_full_funnel/js/script.js"></script>
		<?php echo $template['metadata']; ?>

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

		<!-- Custom theme values -->
		<style>
			.custom-border1 {border-color:#243f65 !important;}
			.custom-bg1 {background:#243f65 !important;}
			.custom-color1 {color:#243f65 !important;}
		</style>
	</head>
	<body itemscope itemtype="http://schema.org/WebPage">