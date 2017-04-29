<?php

echo form_open('');

echo '<p>'.form_input(
	array(
		'name'			=> 'email',
		'placeholder'	=> 'Email Address'
	)
).' (client\'s email address)</p>';

echo '<p>'.form_input(
	array(
		'name'			=> 'build',
		'placeholder'	=> 'Build Type'
	)
).' (eg: wp_base2)</p>';

echo '<p>'.form_input(
	array(
		'name'			=> 'domain',
		'placeholder'	=> 'Domain Name'
	)
).' (eg: thompson.com)</p>';

echo '<p>'.form_input(
	array(
		'name'			=> 'hostname',
		'placeholder'	=> 'FTP Host'
	)
).' (client\'s domain name)</p>';

echo '<p>'.form_input(
	array(
		'name'			=> 'username',
		'placeholder'	=> 'FTP Username'
	)
).' (client\'s username)</p>';

echo '<p>'.form_input(
	array(
		'name'			=> 'password',
		'placeholder'	=> 'FTP Password'
	)
).' (client\'s password)</p>';

echo '<p>'.form_input(
	array(
		'name'			=> 'path',
		'placeholder'	=> 'FTP Path'
	)
).' (typically: /public_html)</p>';

echo '<hr>';

echo '<p>'.form_input(
	array(
		'name'			=> 'db_name',
		'placeholder'	=> 'Database Name'
	)
).' (client\'s username_wp)</p>';

echo '<p>'.form_input(
	array(
		'name'			=> 'db_hostname',
		'placeholder'	=> 'DB Host'
	)
).' (typically: localhost)</p>';

echo '<p>'.form_input(
	array(
		'name'			=> 'db_username',
		'placeholder'	=> 'DB Username'
	)
).' (client\'s username)</p>';

echo '<p>'.form_input(
	array(
		'name'			=> 'db_password',
		'placeholder'	=> 'DB Password'
	)
).' (client\'s password)</p>';

echo '<p>'.form_input(
	array(
		'type'			=> 'submit',
		'name'			=> 'submit',
		'value'			=> 'Submit'
	)
).'</p>';

echo form_close();