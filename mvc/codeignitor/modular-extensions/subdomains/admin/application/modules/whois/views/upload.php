<?php

// initialize variables
$sites_dropdown	= array();

// iterate each site to add to dropdown
foreach ($sites AS $key => $value):
	$sites_dropdown[$value]	= $value;
endforeach;



echo form_open_multipart('whois/upload',array('method' => "POST"),array());
?>

<br><br><br><br><br>
<table width='50%' align='center'>

	<?php
	if ($error):
	?>

		<tr>
			<td align='center' colspan='2' style='font-weight:bold;color:red;'><?php echo $error; ?></td>
		</tr>

	<?php
	endif;
	?>

	<tr>
		<td align='left'>Domain: </td>
		<td align='left'>
			<?php
			echo form_dropdown(
				'domain',
				$sites_dropdown
			);
			?>
		</td>
	</tr>

	<tr>
		<td align='left'>Name Server: </td>
		<td align='left'>
			<?php
			echo form_input(array(
				'name'			=> 'nameserver',
				'type'			=> 'text',
				'placeholder'	=> 'Name Server'
			));
			?>
		</td>
	</tr>

	<tr>
		<td align='left'>Upload CSV: </td>
		<td align='left'>
			<?php
			//echo form_input(array(
			//	'name'	=> 'csv',
			//	'type'	=> 'file'
			//));
			?>
			<input type="file" name="csv" />
		</td>
	</tr>

	<tr>
		<td align='center' colspan='2'>
			<?php
			echo form_input(array(
				'name'	=> 'submit',
				'type'	=> 'submit',
				'value'	=> 'Add Domains'
			));
			?>
		</td>
	</tr>

</table>
<br><br><br><br><br>

<?php
echo form_close();
?>