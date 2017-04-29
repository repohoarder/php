<?php

// open tde form
echo form_open(
	'',
	array('metdod' => 'POST'),
	array()
);

?>

<br><br>
<!--<a class="button blue" href="/pages/add" style="margin-left: 40px;">Add Page</a> -->
<p>Pull Affiliate Statistics</p>

<?php
// display errors
if ($error) echo '<p style="color:red;font-weight:bold;">'.$error.'</p>';
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<script>
$(function() {
	$( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
	$( "#datepicker2" ).datepicker({dateFormat: 'yy-mm-dd'});
});
</script>

<table id="box-table-a" summary="Sales Funnel Pages">
    <tbody>
    	<tr>
    		<td>Start:</small></td>
    		<td>
    			<input type="text" id="datepicker" name="start" />
    		</td>
        </tr>
    	<tr>
    		<td>End:</small></td>
    		<td>
    			<input type="text" id="datepicker2" name="end" />
    		</td>
        </tr>
        <tr>
        	<td colspan=2 align='center'>
    			<?php
					echo form_input(array(
						'type'		=> 'submit',
						'name'		=> 'submit',
						'value'		=> 'Submit',
						'onclick'	=> 'pop_leaving=false;'
					));
				?>
        	</td>
        </tr>
    </tbody>
</table>

<?php
// close the form
echo form_close();
?>