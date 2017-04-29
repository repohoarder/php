<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login Stats</title>

	<style type="text/css">

		html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline}
		article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}
		body{line-height:1}
		ol,ul{list-style:none}
		blockquote,q{quotes:none}
		blockquote:before,blockquote:after,q:before,q:after{content:'';content:none}
		table{border-collapse:collapse;border-spacing:0}
	

		body {font-family:arial,helvetica,sans-serif;line-height:1.3em;}
		h1,h2 {font-size:1.8em;font-weight:700;display:block;line-height:2em;}
		h2 {font-size:1.4em;}

		h1 {border-bottom:1px solid #bbb;margin-top:20px;}

		#wrapper {width:1024px;margin:35px auto;}

		table {margin-left:30px;line-height:1.4em;}

		strong{font-weight:700;}

		td {padding:0 10px;}

	</style>

</head>
<body>

<div id="wrapper">

<form method="post">

	<input type="text" name="start_date" value="<?php echo $dates['start_date'];?>" />
	<input type="text" name="end_date" value="<?php echo $dates['end_date'];?>" />
	
	<input type="hidden" name="submitted" value="1" />
	<button>Go</button>

</form>

<?php foreach ($stats as $brand => $install_types): ?>

	<h1><?php echo $brand; ?></h1>

	<?php foreach ($install_types as $install_type => $nums): ?>
		
		<h2><?php echo $install_type; ?></h2>

		<table>
			<tr><td><strong>Number of Sites:</strong></td><td> <?php echo $nums['num_sites']; ?></td></tr>
			<tr><td><strong>Unique Login Users:</strong></td><td> <?php echo $nums['num_users']; ?> (<?php echo number_format(($nums['num_users'] / $nums['num_sites']) * 100,2);?>%)</td></tr>
			<tr><td><strong>Total Logins:</strong></td><td> <?php echo $nums['total_logins']; ?></td></tr>
			<tr><td><strong>Avg. Logins/Site:</strong></td><td> <?php echo $nums['avg_site_logins']; ?></td></tr>
			<tr><td><strong>Avg. Logins/User:</strong></td><td> <?php echo number_format(floatval($nums['avg_user_logins'])); ?></td></tr>
			<tr><td>&nbsp;</td><td></td></tr>
			<tr><td><strong>Users >= 2 logins:</strong></td><td> <?php echo $nums['twice_users']; ?> (<?php echo number_format(($nums['twice_users'] / $nums['num_sites']) * 100,2);?>%)</td></tr>
			<tr><td><strong>Users >= 3 logins:</strong></td><td> <?php echo $nums['thrice_users']; ?> (<?php echo number_format(($nums['thrice_users'] / $nums['num_sites']) * 100,2);?>%)</td></tr>
			<tr><td><strong>Users >= 4 logins:</strong></td><td> <?php echo $nums['quad_users']; ?> (<?php echo number_format(($nums['quad_users'] / $nums['num_sites']) * 100,2);?>%)</td></tr>
			<tr><td>&nbsp;</td><td></td></tr>
			<tr><td><strong>Dates:</strong></td><td> <?php echo date('m/d/Y',strtotime($nums['start_date'])); ?> to <?php echo date('m/d/Y',strtotime($nums['end_date'])); ?></td></tr>
		</table>

	<?php endforeach; ?>

<?php endforeach; ?>

</div>
</body>