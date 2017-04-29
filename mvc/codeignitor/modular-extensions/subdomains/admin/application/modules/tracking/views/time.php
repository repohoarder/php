<?php

//$this->debug->show($queue);

?>
<script>
	$(function(){
		$("#datepicker").datepicker();
	});
	</script>
<br><br>

<?php

// if error, show it
if ($error) echo '<center><p style="font-weight:bold;color:red;">'.$error.'</p></center>';

// open the form
echo form_open('',array("id" => "form", "method" => "POST"),array());
?>

<center>
<table id="box-table-a" summary="Project Management Time Tracking">

    <tbody>

    	<tr>
    		<td>User:</td>
    		<td>
    			<?php
    			echo form_dropdown('user',$users,$username);
    			?>
    		</td>
    	</tr>

    	<tr>
    		<td>Brand:</td>
    		<td>
    			<?php
    			echo form_dropdown('brand',$brands);
    			?>
    		</td>
    	</tr>

        <!--
    	<tr>
    		<td>Type:</td>
    		<td>
    			<?php
    			echo form_dropdown('type',$types);
    			?>
    		</td>
    	</tr>
    -->

    	<tr>
    		<td>Hours: <small>(eg: 1.25, 3.50, 4.00)</small></td>
    		<td>

                <input type="hidden" name="type" value="scheduled" />

    			<?php
    			echo form_input(array(
    				'name'			=> 'hours',
    				'id'			=> 'hours',
    				'placeholder'	=> '1.00'
    			));
    			?>
    		</td>
    	</tr>
	<tr>
    		<td>Date: <small></small></td>
    		<td>


    			<?php
    			echo form_input(array(
    				'name'			=> 'date',
    				'id'			=> 'datepicker',
    				'value'	=> date('Y-m-d',time())
    			));
    			?>
    		</td>
    	</tr>
    </tbody>
</table>
</center>

<center><input type="submit" class="button small blue" value="Add Time"></center>

<?php
echo form_close();
?>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br>