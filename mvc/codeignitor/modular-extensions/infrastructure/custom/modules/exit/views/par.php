<?php if (isset($success)): ?>
	<p>Thank you for downloading our Money Making Guide! Please check your email.</p>
<?php else: ?>
	<div id="exit-pop-par">
		<form action="#" method="post">
			<input type="text" name="email" placeholder="email address" />
			<input type="submit" alt="Go" />
		</form>
	</div>
<?php endif; ?>