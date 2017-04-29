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

echo form_input(
	array(
		'name'			=> 'company',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('company'),
		'placeholder'	=> 'Company Name'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'website',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('website'),
		'placeholder'	=> 'Website'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'first_name',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('first_name'),
		'placeholder'	=> 'First Name'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'last_name',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('last_name'),
		'placeholder'	=> 'Last Name'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'email',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('email'),
		'placeholder'	=> 'Email'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'address',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('address'),
		'placeholder'	=> 'Address'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'city',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('city'),
		'placeholder'	=> 'City'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'state',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('state'),
		'placeholder'	=> 'State'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'zip',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('zip'),
		'placeholder'	=> 'Zip'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'country',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('country'),
		'placeholder'	=> 'Country'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'phone',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('phone'),
		'placeholder'	=> 'Phone'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'username',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> $this->input->post('username'),
		'placeholder'	=> 'Username'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'password',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> '',
		'placeholder'	=> 'Password'
	)
).'<br>';

echo form_input(
	array(
		'name'			=> 'confirm',
		'id'			=> '',
		'type'			=> 'text',
		'value'			=> '',
		'placeholder'	=> 'Confirm Password'
	)
).'<br>';

// submit button
echo form_input(array(
	'name'		=> 'submit',
	'type'		=> 'submit',
	'class'		=> '',
	'id'		=> '',
	'value'		=> 'Create Account',
	'onsubmit'	=> ''
));

// close the form
echo form_close();