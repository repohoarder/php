<?php 
// initialize varibales
$form_submission	= '';
$attributes			= array(
	'action'	=> '/password/forgot',
	'method'	=> 'POST'
);
$hidden_fields		= array();


if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>';
?>




<?php
// open the form
echo form_open(
	$form_submission,
	$attributes,
	$hidden_fields
);
?>
<h1>Partner Forgot Password</h1>
<fieldset>
	<div class="row">
		<label for="forgotusername">Username:</label>
		<?php
		echo form_input(
			array(
				'name'			=> 'forgotusername',
				'id'			=> 'forgotusername',
				'type'			=> 'text',
				'value'			=> '',
				'placeholder'	=> 'Username'
			)
		);
		?>
	</div>

	<p> --- OR --- </p>

	<div class="row">
		<label for="forgotemail">Email:</label>
		<?php

		echo form_input(
			array(
				'name'			=> 'forgotemail',
				'id'			=> 'forgotemail',
				'type'			=> 'text',
				'value'			=> '',
				'placeholder'	=> 'Email'
			)
		);
		?>
	</div>

	<div class="row" style="margin-left:-100px;">
			<br>
		<?php
		// submit button
		echo form_input(
			array(
				'name'			=> 'submit',
				'id'			=> '',
				'type'			=> 'submit',
				'class'			=> 'btn-contact',
				'value'			=> 'Retrieve Password'
			)
		);
		?>
	</div>
</fieldset>

<?php
// echo close
echo form_close();
