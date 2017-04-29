<?php
// set boolean to show/hide the layout
$show_layout 	= (isset($_SERVER['QUERY_STRING']) AND $_SERVER['QUERY_STRING'] == 'frame=1')
	? FALSE 
	: TRUE;
?>

<?php 
// show head
echo $this->template->load_view('head.php'); 
?>


<header id="t-branding" role="banner" class="pre-login">
	<div class="wrap">
		<div class="pad">
			<img src="/resources/allphase/img/logo.png" alt="All Phase Hosting | Partners" />
		</div>
	</div>
</header>

<div id="t-main" role="main">
	<div class="wrap">
		<div id="col-full">
			<?php echo $template['body']; ?>
		</div>
	</div>
</div>

<?php 
// make sure we need to show the footer
if ($show_layout) echo $this->template->load_view('footer.php'); 