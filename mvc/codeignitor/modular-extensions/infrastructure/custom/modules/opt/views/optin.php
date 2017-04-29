<?php if (isset($errors) && is_array($errors)): ?>

	<ul>
		<?php foreach ($errors as $error): ?>
			<li class="error"><?php echo $error; ?></li>
		<?php endforeach; ?>
	</ul>

<?php endif; ?>

<div class="optin-bg optin">
	<h1>Congratulations on keeping your domain for another year!</h1>
	<p>Brainhost will be happy to renew your domain registration for you.</p>
	<p>For your security, and to avoid unauthorized access to your account, a valid email address is <strong>REQUIRED</strong> to confirm your renewal:</p>

	<form action="" method="post">
		<label for="txtEmail">Email Address:</label>
		<input type="email" id="txtEmail" name="email" placeholder="Email Address" />
		<input type="submit" value="Confirm" />
	</form>
</div>
<br><br><br>
