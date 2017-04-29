<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title></title>

	<?php $this->load->view('spinner/cleaned/header'); ?>

</head>
<body>


	<?php 

	$this->load->view(
		'spinner/cleaned/domain_form',
		array(
			'suggestions' => $suggestions
		)
	); 

	?>

	<img height="1" width="1" src="http://freeaffiliateclub.com/tracking/step_3_pixel.php" />

</body>
</html>