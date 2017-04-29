<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	
	<title><?php echo $template['title']; ?></title>
	
	<meta name="description" content="">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1" />
    
    <link rel="shortcut icon" href="images/favicon.ico" />

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Cuprum" />
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Bree+Serif"/>
	
    <link rel="stylesheet" href="/resources/whitedream/assets/css/style.css" />
    <link rel="stylesheet" href="/resources/whitedream/assets/css/autosuggest_inquisitor.css" />
    <link rel="stylesheet" href="/resources/whitedream/assets/css/fancybox.css" media="screen"  />
    <link rel="stylesheet" href="/resources/whitedream/assets/css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="/resources/whitedream/assets/css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="/resources/whitedream/assets/lib/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="/resources/whitedream/assets/lib/editor/jquery.wysiwyg.css" media="screen" />
    <link rel="stylesheet" href="/resources/whitedream/assets/lib/editor/default.css" media="screen" />
    <link rel="stylesheet" href="/resources/whitedream/assets/lib/player/css/style.css"/>
    <link rel="stylesheet" href="/resources/whitedream/assets/css/tipTip.css" media="screen" />
    <link rel="stylesheet" href="/resources/whitedream/assets/css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="/resources/whitedream/assets/css/colorpicker.css" type="text/css" />
    <link rel="stylesheet" href="/resources/whitedream/assets/css/tables.css" media="screen"  />
    <link rel="stylesheet" href="/resources/whitedream/assets/css/jquery.jgrowl.css" media="screen"  />
    <link rel="stylesheet" href="/resources/whitedream/assets/css/sweet-tooltip.css"/>
    <link rel="stylesheet" href="/resources/whitedream/assets/css/jquery.jscrollpane.css"/>
    
    <!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/resources/whitedream/assets/lib/excanvas.min.js"></script><![endif]-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script src="/resources/whitedream/assets/lib/jquery-ui-1.8.16.custom.min.js"></script>
    
    <script type="text/javascript" src="/resources/whitedream/assets/lib/ddaccordion.js"></script>
	<script type="text/javascript" src="/resources/whitedream/assets/lib/jquery.flot.min.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/jquery.flot.pie.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/jquery.flot.orderBars.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/jquery.flot.resize.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/graphtable.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/fancybox/fancybox.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/fullcalendar.min.js"></script>
    <script src="/resources/whitedream/assets/lib/elfinder/js/elfinder.min.js" charset="utf-8"></script>
    <script src="/resources/whitedream/assets/lib/editor/jquery.wysiwyg.js" charset="utf-8"></script>
    <script src="/resources/whitedream/assets/lib/editor/wysiwyg.image.js" charset="utf-8"></script>
	<script src="/resources/whitedream/assets/lib/editor/default.js" charset="utf-8"></script>
    <script src="/resources/whitedream/assets/lib/editor/wysiwyg.link.js" charset="utf-8"></script>
    <script src="/resources/whitedream/assets/lib/editor/wysiwyg.table.js" charset="utf-8"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/player/jquery-jplayer/jquery.jplayer.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/player/ttw-video-player-min.js"></script>
    <script src="/resources/whitedream/assets/lib/jquery.tipTip.minified.js"></script>
    <script src="/resources/whitedream/assets/lib/forms.js"></script>
    <script src="/resources/whitedream/assets/lib/chosen.jquery.min.js"></script>
    <script src="/resources/whitedream/assets/lib/autoresize.jquery.min.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/colorpicker.js"></script>
	<script type="text/javascript" src="/resources/whitedream/assets/lib/validation.js"></script>
    <script src="/resources/whitedream/assets/lib/jquery.dataTables.min.js"></script>
    <script src="/resources/whitedream/assets/lib/jquery.jgrowl_minimized.js"></script>
    <script src="/resources/whitedream/assets/lib/slidernav-min.js"></script>
    <script src="/resources/whitedream/assets/lib/jquery.alerts.js" type="text/javascript"></script>
    <script src="/resources/whitedream/assets/lib/formToWizard.js"></script>
	<script>$(document).ready(function(){ $("#SignupForm").formToWizard({ submitButton: 'SaveAccount' }) });</script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/AutoSuggest_2.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/jquery.jscrollpane.min.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/tabs.js"></script>
    <script src="/resources/whitedream/assets/lib/hover.zoom.js"></script>
    <script type="text/javascript" src="/resources/whitedream/assets/lib/jquery.reveal.js"></script>
    <script src="/resources/whitedream/assets/lib/jquery.tzCheckbox.js"></script>
    <script src="/resources/whitedream/assets/lib/cookie.js" type="text/javascript"></script>
	<script src="/resources/whitedream/assets/lib/core.js" type="text/javascript"></script>

    <script type="text/javascript" src="/resources/whitedream/assets/lib/functions.js"></script>
    
	<?php echo $template['metadata']; ?>
    
</head>
<body class="layout_<?php //echo $this->template->get_layout(); ?>">