<?php

?>


<h1>Create New Build</h1>

<?php
// initialize varibales
$form_submission	= 'builder/create';
$attributes			= array(
	'method'	=> 'POST',
	'id'	=> 'frm_builder',
);
$hidden		 		= array();

// show an error if there is one
if (isset($build_dir)):
	echo '<p class="error">'.$error.'</p>';
	
		 echo "<div style='width:470px;float:left;'>Build Directory : $build_dir ";
		?>
		<form method='post' action="builder/create" enctype="multipart/form-data">
			<ul style='margin:0;list-style:none outside none;float:left;'>
				<li style='margin:0;list-style:none outside none;'><input type="file" name="sqlfile"></li>
				<li style='margin:0;list-style:none outside none;'><input type="hidden" name="build_dir" value="<?php echo $build_dir;?>">
				<input type='submit' value='Upload SQL File' name="uploadsql"></li>
				</ul>
		</form>
	</div>
	<?php
	
endif;

// open the form
echo form_open(
	$form_submission,
	$attributes,
	$hidden
);

?><div id="col-l">
<h2>Site Information</h2>
<ul><?php

// Build Name
?><li><label for="build_name">Build Name:</label><?php
echo form_input(array(
	'name'			=> 'build_name',
	'type'			=> 'text',
	'id'			=> 'build_name',
	'placeholder'	=> 'Build Name'
)).'</li>';
?>
<li><label for="build_name">Slug: </label><?php
echo form_input(array(
	'name'			=> 'slug',
	'type'			=> 'text',
	'id'			=> 'slug',
	'placeholder'	=> 'slug',
	'value'			=> ''
))
?>
<li><label for="build_name">Build Description:</label><?php
echo form_textarea(array(
	'name'			=> 'description',
	'id'			=> 'description',
	'cols'			=> '30'
)).'</li>';
// Auto Build?
?><li><label>Auto Build?</label><?php
echo '<label for="auto_build_yes" class="radio">'.form_input(array(
	'name'			=> 'auto_build',
	'type'			=> 'radio',
	'id'			=> 'auto_build_yes',
	'value'	=> 'yes'
)).' Yes</label><label for="auto_build_no" class="radio">'.form_input(array(
	'name'			=> 'auto_build',
	'type'			=> 'radio',
	'id'			=> 'auto_build_no',
	'value'	=> 'no'
)).'No</label>';

// FTP Host
?><li><label for="ftp_host">FTP Host:</label><?php
echo form_input(array(
	'name'			=> 'ftp_host',
	'type'			=> 'text',
	'id'			=> 'ftp_host',
	'placeholder'	=> 'FTP Host'
)).'</li>';

// FTP User
?><li><label for="ftp_username">FTP Username:</label><?php
echo form_input(array(
	'name'			=> 'ftp_username',
	'type'			=> 'text',
	'id'			=> 'ftp_username',
	'placeholder'	=> 'FTP Username'
)).'</li>';

// FTP Password
?><li><label for="ftp_password">FTP Password:</label><?php
echo form_input(array(
	'name'			=> 'ftp_password',
	'type'			=> 'text',
	'id'			=> 'ftp_password',
	'placeholder'	=> 'FTP Password'
)).'</li>';

// FTP Password
?><li><label for="directory">FTP Directory:</label><?php
echo form_input(array(
	'name'			=> 'directory',
	'type'			=> 'text',
	'id'			=> 'directory',
	'placeholder'	=> 'FTP Directory'
)).'</li>';

// Database Host
?><li><label>
		Use SCP?
	</label>
<input type="checkbox" name="usescp" checked value="1">
</li>
<li><label for="db_host">Database Host:</label><?php
echo form_input(array(
	'name'			=> 'db_host',
	'type'			=> 'text',
	'id'			=> 'db_host',
	'placeholder'	=> 'Database Host'
)).'</li>';

// Database User
?><li><label for="db_username">Database Username:</label><?php
echo form_input(array(
	'name'			=> 'db_username',
	'type'			=> 'text',
	'id'			=> 'db_username',
	'placeholder'	=> 'Database Username'
)).'</li>';

// Database Password
?><li><label for="db_password">Database Password:</label><?php
echo form_input(array(
	'name'			=> 'db_password',
	'type'			=> 'text',
	'id'			=> 'db_password',
	'placeholder'	=> 'Database Password'
)).'</li>';

// Database Name
?><li><label for="db_name">Database Name:</label><?php
echo form_input(array(
	'name'			=> 'db_name',
	'type'			=> 'text',
	'id'			=> 'db_name',
	'placeholder'	=> 'Database Name'
)).'</li>';

?></ul></div><div id="col-r"><h2>Find and Replace Text</h2><ul><?php

// Find
?><li><div class="find"><label for="find_text1">Find Text:</label><?php
echo form_input(array(
	'name'			=> 'find_text1',
	'type'			=> 'text',
	'id'			=> 'find_text1',
	'placeholder'	=> 'Find Text',
	'value'			=> 'DB_NAME'
)).'</div>';

// Replace with
?><div class="replaced"><label for="sel_replacewith1">Replace With:</label><?php
echo form_dropdown(
	'sel_replacewith1', 
	$dropdown, 
	'username', 
	'id="sel_replacewith1"').'</div><div class="remove"><a href="#">Remove</a></div></li>';

// Find
?><li><div class="find"><label for="find_text2">Find Text:</label><?php
echo form_input(array(
	'name'			=> 'find_text2',
	'type'			=> 'text',
	'id'			=> 'find_text2',
	'placeholder'	=> 'Find Text',
	'value'			=> 'CLIENT_DB_USERNAME'
)).'</div>';

// Replace with
?><div class="replaced"><label for="sel_replacewith2">Replace With:</label><?php
echo form_dropdown(
	'sel_replacewith2', 
	$dropdown, 
	'username', 
	'id="sel_replacewith2"').'</div><div class="remove"><a href="#">Remove</a></div></li>';

// Find
?><li><div class="find"><label for="find_text3">Find Text:</label><?php
echo form_input(array(
	'name'			=> 'find_text3',
	'type'			=> 'text',
	'id'			=> 'find_text3',
	'placeholder'	=> 'Find Text',
	'value'			=> 'CLIENT_DB_PASSWORD'
)).'</div>';

// Replace with
?><div class="replaced"><label for="sel_replacewith3">Replace With:</label><?php
echo form_dropdown(
	'sel_replacewith3', 
	$dropdown, 
	'password', 
	'id="sel_replacewith3"').'</div><div class="remove"><a href="#">Remove</a></div></li>';

// Find
?><li><div class="find"><label for="find_text4">Find Text:</label><?php
echo form_input(array(
	'name'			=> 'find_text4',
	'type'			=> 'text',
	'id'			=> 'find_text4',
	'placeholder'	=> 'Find Text',
	'value'			=> 'nowhere@brainhost.com'
)).'</div>';

// Replace with
?><div class="replaced"><label for="sel_replacewith4">Replace With:</label><?php
echo form_dropdown(
	'sel_replacewith4', 
	$dropdown, 
	'email', 
	'id="sel_replacewith4"').'</div><div class="remove"><a href="#">Remove</a></div></li>';

?>
</ul>
<ol><li class="add-new"><a href="#">Add New Row</a></li></ol><?php

// Submit
echo form_input(array(
	'name'		=> 'buildit',
	'type'		=> 'submit',
	'id'		=> 'submit',
	'value'		=> 'Build'
));

// close the form
echo form_close();

?>