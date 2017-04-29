		<?php
		// grab crm config item
		$crm 	= $this->config->item('crm');
		?>


		<div class="col-r">
			<div class="module signin">
				<h2>Sign In</h2>
				<?php echo form_open($crm['url'].'/signin',array('method' => 'POST', 'target'=>'_top'),array()); ?>
					<?php echo form_fieldset(); ?>
						<div class="row">
							<label for="login-username">Username</label>
							<?php

								$input = array(
									'name'		=> 'username',
									'id'		=> 'login-username',
									'type'		=> 'text'
								);

								if (isset($demo_account) && $demo_account):

									$input['value'] = 'Demo1234';

								endif;

								echo form_input($input);
							?>
						</div>
						<div class="row">
							<label for="login-password">Password</label>
							<?php
								$input = array(
									'name'		=> 'password',
									'id'		=> 'login-password',
									'type'		=> 'password'
								);

								if (isset($demo_account) && $demo_account):

									$input['value'] = 'Demo1234';

								endif;

								echo form_input($input);
							?>
						</div>
						<div class="row center">
							<?php
								// submit button
								echo form_input(array(
									'name'	=> 'submit',
									'type'	=> 'submit',
									'class' => 'btn-blue',
									'value'	=> 'Submit'
								));
							?>
						</div>
						<p class="center" style="padding:0;"><a href="#">Forgot Your Password?</a></p>
					<?php echo form_fieldset_close(); ?>
				<?php echo form_close(); ?>
			</div>
			<aside class="module features">
				<h2>Features</h2>
				<p>Join our partner program and create your own hosting company. Just sign up, choose your domain, and start advertising your company!</p>
				<hr />
				<ul>
					<li>Instant Activation</li>
					<li>Account Setup</li>
					<li>Host Unlimited Websites</li>
					<li>Unlimited Email Accounts</li>
					<li>Domain Registration</li>
					<li>Website Builder</li>
					<li>Unlimited Bandwidth</li>
					<li>24/7 Customer Support</li>
					<li>Domain Registration</li>
				</ul>
			</aside>
			<aside class="module companies">
				<p><strong>200,000+</strong> Companies Trust Us With Their Website</p>
			</aside>
		</div>