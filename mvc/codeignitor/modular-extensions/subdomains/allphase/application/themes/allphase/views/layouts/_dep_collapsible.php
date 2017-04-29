<?php

// set boolean to show/hide the layout
$show_layout 	= (isset($_SERVER['QUERY_STRING']) AND $_SERVER['QUERY_STRING'] == 'frame=1')
	? FALSE 
	: TRUE;


// show head
echo $this->template->load_view('collapsible/head.php'); 


// make sure we need to show layout - if so 
if ($show_layout) echo $this->template->load_view('collapsible/header.php'); 

if(!isset($nav_collapsed)) $nav_collapsed = false;
?>

<div id="t-main" role="main">
	<div class="wrap">
		<div id="col-r"<?php echo ($nav_collapsed) ? ' class="expand" ': ''; ?>>
			<?php echo $template['body']; ?>
		</div>
		<nav id="col-l"<?php echo ($nav_collapsed) ? ' class="condense" ': ''; ?>>
			<?php echo $this->template->load_view('collapsible/nav.php'); ?>
		</nav>
	</div>
</div>

<?php 
// make sure we need to show the footer
if ($show_layout) echo $this->template->load_view('collapsible/footer.php'); 