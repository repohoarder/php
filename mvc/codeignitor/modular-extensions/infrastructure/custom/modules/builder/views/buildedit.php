<?php


?>
<style>
	li.hideit{ display:none;}
</style>

<h1>Create New Build</h1>

<?php

// initialize varibales
$form_submission	= "builder/edit/$build_id";
$attributes			= array(
	'method'	=> 'POST',
	'id'	=> 'frm_builder',
);
$hidden				= array();


// show an error if there is one
if ($error) echo '<p class="error">'.$error.'</p>';

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
?><li><label for="build_name">Build Name: </label><?php
echo form_input(array(
	'name'			=> 'build_names',
	'type'			=> 'text',
	'id'			=> 'build_names',
	'placeholder'	=> 'Build Name',
	'value'			=> $build['name'],
	'disabled'		=> true
)). form_input(array(
	'name'			=> 'build_name',
	'type'			=> 'hidden',
	'id'			=> 'build_name',
	'placeholder'	=> 'Build Name',
	'value'			=> $build['name']
)).'</li>';
?>
<li><label for="build_name">Slug: </label><?php
echo form_input(array(
	'name'			=> 'slug',
	'type'			=> 'text',
	'id'			=> 'slug',
	'placeholder'	=> 'slug',
	'value'			=> $build['slug']
))
?>
<li><label for="build_name">Version: </label><?php
echo form_input(array(
	'name'			=> 'version',
	'type'			=> 'text',
	'id'			=> 'version',
	'placeholder'	=> 'version',
	'value'			=> $build['version']
))
?>
<li><label for="build_name">Build Changelog:</label><?php
echo form_textarea(array(
	'name'			=> 'changelog',
	'id'			=> 'changelog',
	'cols'			=> '30',
	'value'			=> ''
)).'</li>';
?>
<li><label for="build_name">Build Description:</label><?php
echo form_textarea(array(
	'name'			=> 'description',
	'id'			=> 'description',
	'cols'			=> '30',
	'value'			=> $build['description']
)).'</li>';
// Auto Build?
?><li><label>Auto Build?</label><?php
echo '<label for="auto_build_yes" class="radio">'.form_input(array(
	'name'			=> 'auto_build',
	'type'			=> 'radio',
	'id'			=> 'auto_build_yes',
	'checked'		=> $build['auto_build'] == 1 ? true : false ,
	'value'	=> '1'
)).' Yes</label><label for="auto_build_no" class="radio">'.form_input(array(
	'name'			=> 'auto_build',
	'type'			=> 'radio',
	'id'			=> 'auto_build_no',
	'checked'		=> $build['auto_build'] == 0 ? true : false ,
	'value'	=> '0'
)).'No</label>';

// FTP Host
?>
<li class="showit"><a href="javascript:void(0);" class="showit">Show Server Details</a></li>
<li class="hideit"><a href="javascript:void(0);" class="hidec">Hide Server Details</a></li>
<li class='hideit'><label for="ftp_host">FTP Host:</label><?php
echo form_input(array(
	'name'			=> 'ftp_host',
	'type'			=> 'text',
	'id'			=> 'ftp_host',
	'placeholder'	=> 'FTP Host',
	'value'			=> $build['ftp_host']
)).'</li>';

// FTP User
?><li class='hideit'><label for="ftp_username">FTP Username:</label><?php
echo form_input(array(
	'name'			=> 'ftp_username',
	'type'			=> 'text',
	'id'			=> 'ftp_username',
	'placeholder'	=> 'FTP Username',
	'value'			=> $build['ftp_user']
)).'</li>';

// FTP Password
?><li class='hideit'><label for="ftp_password">FTP Password:</label><?php
echo form_input(array(
	'name'			=> 'ftp_password',
	'type'			=> 'text',
	'id'			=> 'ftp_password',
	'placeholder'	=> 'FTP Password',
	'value'			=> $build['ftp_pass']
)).'</li>';

// FTP Password
?><li class='hideit'><label for="directory">FTP Directory:</label><?php
echo form_input(array(
	'name'			=> 'directory',
	'type'			=> 'text',
	'id'			=> 'directory',
	'placeholder'	=> 'FTP Directory',
	'value'			=> $build['ftp_dir']
)).'</li>';

// Database Host
?><li class='hideit'><label for="db_host">Database Host:</label><?php
echo form_input(array(
	'name'			=> 'db_host',
	'type'			=> 'text',
	'id'			=> 'db_host',
	'placeholder'	=> 'Database Host',
	'value'			=> $build['db_host']
)).'</li>';

// Database User
?><li class='hideit'><label for="db_username">Database Username:</label><?php
echo form_input(array(
	'name'			=> 'db_username',
	'type'			=> 'text',
	'id'			=> 'db_username',
	'placeholder'	=> 'Database Username',
	'value'			=> $build['db_user']
)).'</li>';

// Database Password
?><li class='hideit'><label for="db_password">Database Password:</label><?php
echo form_input(array(
	'name'			=> 'db_password',
	'type'			=> 'text',
	'id'			=> 'db_password',
	'placeholder'	=> 'Database Password',
	'value'			=> $build['db_pass']
)).'</li>';

// Database Name
?><li class='hideit'><label for="db_name">Database Name:</label><?php
echo form_input(array(
	'name'			=> 'db_name',
	'type'			=> 'text',
	'id'			=> 'db_name',
	'placeholder'	=> 'Database Name',
	'value'			=> $build['db_name']
)).'</li>';

?></ul></div>

<div id="col-r"><h2>Find and Replace Text</h2>
<ul>
	<?php 
	if(!empty($build['replace'])) : 
		
		$i=1;
		foreach($build['replace'] as $record) :?>
			<li>
			<div class="find"><label for="find_text1">Find Text:</label><?php
			echo form_input(array(
				'name'			=> 'find_text'.$i,
				'type'			=> 'text',
				'id'			=> 'find_text1'.$i,
				'placeholder'	=> 'Find Text',
				'value'			=> $record['replace_string']
			)).'';
			// Replace with?>
			</div>
			<div class="replaced"><label for="sel_replacewith1">Replace With:</label><?php
			echo form_dropdown(
				'sel_replacewith'.$i, 
				$dropdown, 
				$record['replace_with'], 
				'id="sel_replacewith'.$i.'"').'';
			// Find
			?>
			</div>
			<div class="remove"><a href="#">Remove</a></div>
			</li>
			<?php 
			$i++;
		endforeach;
		##  if the replace array is empty leave one set 
	else: ?>
		<li>
		<div class="find"><label for="find_text1">Find Text:</label><?php
		echo form_input(array(
			'name'			=> 'find_text1',
			'type'			=> 'text',
			'id'			=> 'find_text1',
			'placeholder'	=> 'Find Text',
			'value'			=> 'DB_NAME'
		)).'';

		// Replace with
		?>
		</div>
		<div class="replaced"><label for="sel_replacewith1">Replace With:</label><?php
		echo form_dropdown(
			'sel_replacewith1', 
			$dropdown, 
			'username', 
			'id="sel_replacewith1"').'';

		// Find
		?>
		</div>
		<div class="remove"><a href="#">Remove</a></div>
		</li>
	<?php endif; ?>
	
</ul>
<ol><li class="add-new"><a href="#">Add New Row</a></li></ol><?php

// Submit
echo form_input(array(
	'name'		=> 'buildit',
	'type'		=> 'submit',
	'id'		=> 'submit',
	'value'		=> 'Save'
));

// close the form
echo form_close();

?>
