<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

	<title><?php echo $template['title']; ?></title>
	
	<meta name="description" content=""/>
	<meta name="author" content=""/>
	<meta name="viewport" content="width=device-width,initial-scale=1"/>	
	
	<?php
	// if user doesn't have frame=1 in the URL, show iframe killer code
	if (strstr($_SERVER['QUERY_STRING'],'frame=1') === FALSE):
	?>
	<!-- Frame Killer -->
	<style> html{display : none ; } </style>
	<script>
	   if( self == top ) {
	       document.documentElement.style.display = 'block' ; 
	   } else {
	       top.location = self.location ; 
	   }
	</script>
	<?php 
	endif;
	?>
	<link href="/resources/reports/css/ui-lightness/jquery-ui-1.10.2.custom.css" rel="stylesheet">
	<?php $layout_style = (isset($layout_style)) ? $layout_style : '/resources/reports/css/style.css'; ?>
	
	<link rel="stylesheet" href="<?php echo $layout_style; ?>"/>
	
	<!--[if lt IE 9]>
        <script src="js/libs/modernizr.h5bp.custom.js"></script>
    <![endif]-->
	
	<?php echo $template['metadata']; ?>
	
</head>

<body itemscope itemtype="http://schema.org/WebPage">