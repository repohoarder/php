<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?php echo $template['title'];?></title>
		<meta name="description" content="">
		<meta name="author" content="">

		<meta name="viewport" content="width=device-width,initial-scale=1">

		<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,800' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Lato:400,700,900' rel='stylesheet' type='text/css'>

	    <link rel="stylesheet" href="/resources/allphase/css/collapsible/style.css">
		<link rel="stylesheet" href="/resources/allphase/css/collapsible/responsive.css">
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css">

		<script src="/resources/allphase/js/modernizr.custom.09191.js"></script>
		<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery/jquery-1.8.2.min.js"></script>
		
		<script>window.jQuery || document.write('<script src="/resources/allphase/js/jquery-1.8.2.min.js"><\/script>')</script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
		<!-- data table -->
		<script src="/resources/allphase/js/datatables/js/jquery.dataTables.js"></script>
		<link rel="stylesheet" href="/resources/allphase/js/datatables/css/jquery.dataTables.css">
		<script>
			$(function(){
				
				$("#accordion").accordion({ active: 0, heightStyle: "content", collapsible: true });
				$(".date").datepicker();

				// Expanded nav functionality
				$(document).on("click", "#col-l:not(.condense) #nav-main a", function(e) {
					if($(this).parent().find('ul').length > 0){
						e.preventDefault();
						$(e.target).next('ul').siblings('ul').slideUp('fast');
						$(e.target).next('ul').slideToggle('fast');
					}
				});

				// Condensed nav functionality
				$(document).on("click", "#col-l.condense #nav-main a", function(e) {
					if($(this).parent().find('ul').length > 0){
						e.preventDefault();
					}
				});
				$(document).on("hover", "#col-l.condense #nav-main a", function(e) {
					if($(this).parent().find('ul').length > 0){
						$(e.target).next('ul').removeAttr('style');
					}
				});
				
				$('#tablesorter').dataTable(
					{"bPaginate": true,
                        "bLengthChange": false,
                        "bFilter": false,
                        "bSort": true,
                        "bInfo": true
					});

			});
		</script>
        <script src="/resources/allphase/js/jquery.validate.js"></script>
		<script src="/resources/allphase/js/script.js"></script>
		
		
		
		<script src="/resources/allphase/js/fancybox2.1.3/jquery.fancybox.js"></script>
		<link href="/resources/allphase/js/fancybox2.1.3/jquery.fancybox.css" rel="stylesheet" />
		
		
		<script type="text/javascript">
			$(document).ready(function() {
				$('.lightbox').fancybox({
					height: 600,
					width:  600
				});
				$('.bigbox').fancybox({
					height: 700,
					width:  950
				});
			});
		</script>

		<?php echo $template['metadata']; ?>
		
	</head>
	<body>