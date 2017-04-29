<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Affiliate Prices</title>

	<link rel="stylesheet" href="/resources/modules/affprice/assets/css/style.css" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

</head>
<body>
	
	<div id="container">

		<?php if (isset($errors) && ! empty($errors)): ?>

			<ul class="error">
				<?php foreach ($errors as $error): ?>
					<li><?php echo $error; ?></li>
				<?php endforeach; ?>
			</ul>

		<?php endif; ?>
		

		<form method="post" action="" id="auth">
			Who is the most phenomenal, extraordinary fellow?<br/>
			<input type="text" name="phrase" />
			<div id="button_container">
				<button type="submit">Submit</button>
			</div>
			<input type="hidden" name="popped" value="1" />
		</form>
	</div>

</body>
</html>