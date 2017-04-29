<!doctype html>
<html lang="en-us" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title><?php echo $template['title']; ?></title>

		<!-- Meta Information -->
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
		<meta content="" name="keywords" />
		<meta content="" name="description" />
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
		<link rel="stylesheet" href="/resources/apmcsd/css/style.css" />		

		<!-- Load jQuery & jQuery UI from Google CDN -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script type="text/javascript" src="/resources/apmcsd/js/jquery-1.8.3.min.js"><\/script>')</script>

		<!-- Modernizr to make older browsers behave -->
		<script src="/resources/apmcsd/js/modernizr-2.6.2.min.js"></script>

		<!-- Flowplayer for video -->
		<link rel="stylesheet" type="text/css" href="http://releases.flowplayer.org/5.3.1/skin/minimalist.css" />
		<script src="http://releases.flowplayer.org/5.3.1/flowplayer.min.js"></script>

		<!-- Custom jQuery -->
		<script src="/resources/apmcsd/js/script.js"></script>
		<?php if(isset($exitpopup)) : ?>
		<script src="/resources/apmcsd/js/exit.js"></script>
		<?php endif; ?>
	</head>
	<body itemscope itemtype="http://schema.org/WebPage">