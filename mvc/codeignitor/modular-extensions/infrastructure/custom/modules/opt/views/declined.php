<?php if (isset($errors) && is_array($errors)): ?>

	<ul>
		<?php foreach ($errors as $error): ?>
			<li class="error"><?php echo $error; ?></li>
		<?php endforeach; ?>
	</ul>

<?php endif; ?>

<div class="optin-bg optin">
	<h1>We're sorry to see you go!</h1>
	<p>At Brainhost, we understand that circumstances and interests change, and that you may no longer feel that our services are right for you.</p>
	<p>To ensure that we're providing the best possible service to current and future clients, please take a moment to let us know why you have chosen not to renew your services by selecting from the options below.</p>
	<div class="col-l">
		<form action="" method="post">
			<fieldset>
				<div class="row">
					<input type="radio" name="reason" id="reasonIncome" value="income" /> <label for="reasonIncome">I am not making/did not make any income from my website</label>
				</div>
				<div class="row">
					<input type="radio" name="reason" id="reasonUse" value="use" /> <label for="reasonUse">I am not using my website</label>
				</div>
				<div class="row">
					<input type="radio" name="reason" id="reasonAffiliate" value="affiliate" /> <label for="reasonAffiliate">I am no longer using the affiliate program that referred me</label>
				</div>
				<div class="row">
					<input type="radio" name="reason" id="reasonInterest" value="interest" /> <label for="reasonInterest">I have no interest in making money online</label>
				</div>
				<div class="row">
					<input type="radio" name="reason" id="reasonOther" value="other" /> <label for="reasonOther">Other</label>
				</div>
			</fieldset>
			<p>For your security, and to avoid unauthorized access to your account, a valid email address is <strong>REQUIRED</strong> to confirm your cancellation:</p>
			<label for="txtEmail">Email Address:</label>
			<input type="email" id="txtEmail" name="email" placeholder="Email Address" />
			<input type="submit" value="Confirm" />
		</form>
	</div>
	<div class="col-r">
		<img src="/resources/brainhost/img/img-phone.png" alt="1-800-888-5555" />
		<div class="module fact">
			<h2>Did You Know?</h2>
			<p>The longer you keep your site running, the higher it will rank with search engines?</p>
		</div>
		<div class="module question">
			<h2>Have A Question?</h2>
			<p>Can't find what you're looking for? Search within the knowledge base.</p>
			<a href="#" class="btn-green">Go To The Help Section</a>
		</div>
	</div>
</div>
