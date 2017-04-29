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
	<meta itemprop="currency" content="USD" />
	
	<?php /*
	<!-- Split Test Code -->
	<?php 
	// only show conversion page if order id is set
	if ($this->session->userdata('_id')): ?>
		<script type="text/javascript">
			// Put your tracking values below
			var track_options = {
				amount: "<?php echo (isset($split_amount)) ? $split_amount : 0 ;?>",
				order_id: <?php echo $this->session->userdata('_id'); ?>,
			};
		</script>
	<?php endif; ?>
	<script type="text/javascript" src="https://infrastructure.brainhost.com/resources/modules/split/assets/track/track.js"></script>
	<!-- END Split Test Code -->
	*/ ?>



	
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,800' rel='stylesheet' type='text/css'/>


	<!-- Start Visual Website Optimizer Asynchronous Code -->
	<script type='text/javascript'>
	var _vwo_code=(function(){
	var account_id=27951,
	settings_tolerance=2000,
	library_tolerance=1500,
	use_existing_jquery=false,
	// DO NOT EDIT BELOW THIS LINE
	f=false,d=document;return{use_existing_jquery:function(){return use_existing_jquery;},library_tolerance:function(){return library_tolerance;},finish:function(){if(!f){f=true;var a=d.getElementById('_vis_opt_path_hides');if(a)a.parentNode.removeChild(a);}},finished:function(){return f;},load:function(a){var b=d.createElement('script');b.src=a;b.type='text/javascript';b.innerText;b.onerror=function(){_vwo_code.finish();};d.getElementsByTagName('head')[0].appendChild(b);},init:function(){settings_timer=setTimeout('_vwo_code.finish()',settings_tolerance);this.load('//dev.visualwebsiteoptimizer.com/j.php?a='+account_id+'&u='+encodeURIComponent(d.URL)+'&r='+Math.random());var a=d.createElement('style'),b='body{opacity:0 !important;filter:alpha(opacity=0) !important;background:none !important;}',h=d.getElementsByTagName('head')[0];a.setAttribute('id','_vis_opt_path_hides');a.setAttribute('type','text/css');if(a.styleSheet)a.styleSheet.cssText=b;else a.appendChild(d.createTextNode(b));h.appendChild(a);return settings_timer;}};}());_vwo_settings_timer=_vwo_code.init();
	</script>
	<!-- End Visual Website Optimizer Asynchronous Code -->



	<?php $layout_style = (isset($layout_style)) ? $layout_style : '/resources/brainhost/css/style.css'; ?>

	<link rel="stylesheet" href="<?php echo $layout_style; ?>"/>
	    
	<script src="/resources/brainhost/js/libs/modernizr.h5bp.custom.js"></script>
    
    <!-- JQUERY -->
    <script src="/resources/brainhost/js/libs/jquery-1.8.3.min.js" type="text/javascript"></script>
        
    <!-- JQUERY VALIDATION -->
    <script src="/resources/brainhost/js/libs/jquery.validate.js" type="text/javascript"></script>

	<?php echo $template['metadata']; ?>

</head>

<body itemscope itemtype="http://schema.org/WebPage">