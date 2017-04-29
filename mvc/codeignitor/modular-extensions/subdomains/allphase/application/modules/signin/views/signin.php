<?php 
// initialize varibales
$form_submission	= '';
$attributes			= array(
	'method'	=> 'POST'
);
$hidden_fields		= array();


if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>';


// open the form
echo form_open(
	$form_submission,
	$attributes,
	$hidden_fields
);

?>
<h1>Partner Log In</h1>
<fieldset>
	<div class="row">
		<label for="username">Username:</label>
		<?php
			echo form_input(
				array(
					'name'			=> 'username',
					'id'			=> 'username',
					'type'			=> 'text',
					'value'			=> $this->input->post('username'),
					'placeholder'	=> 'Username'
				)
			);
		?>
	</div>
	<div class="row">
		<label for="password">Password:</label>
		<?php
			echo form_input(
				array(
					'name'			=> 'password',
					'id'			=> 'password',
					'type'			=> 'password',
					'value'			=> '',
					'placeholder'	=> 'Password'
				)
			);
		?>
	</div>
	<div class="row" style="margin-left:-75px;">
		<?php
			// submit button
			echo form_input(
				array(
					'name'			=> 'submit',
					'id'			=> '',
					'class'			=> 'btn-contact',
					'type'			=> 'submit',
					'value'			=> 'Sign In'
				)
			);
		?>
	</div>
	<div class="row">
		<p><a href="/pass/forgot">Forgot your password?</a></p>
	</div>
</fieldset>

<?php
// echo close
echo form_close();