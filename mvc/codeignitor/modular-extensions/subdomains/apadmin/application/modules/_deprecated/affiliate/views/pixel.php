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
<p>Add an affiliate tracking pixel</p>

<?php
// display errors
if ($error) echo '<p style="color:red;font-weight:bold;">'.$error.'</p>';
?>

<table id="box-table-a" summary="Sales Funnel Pages">
    <tbody>

    	<tr>
    		<td>Partner ID:</small></td>
    		<td>
    			<?php
					echo form_dropdown(
						'partner_id',
						$partners,
						'1'
					);
				?>
    		</td>
        </tr>
    	<tr>
    		<td>Affiliate ID: </td>
    		<td>
    			<?php
					echo form_input(array(
						'name'	=> 'affiliate_id'
					));
				?>
    		</td>
        </tr>
    	<tr>
    		<td>Offer ID: <small>(optional)</small></td>
    		<td>
    			<?php
					echo form_input(array(
						'name'	=> 'offer_id'
					));
				?>
    		</td>
        </tr>
    	<tr>
    		<td>Page:</small></td>
    		<td>
    			<?php
					echo form_dropdown(
						'type',
						array('thank_you' => 'Thank You Page'),
						'thank_you'
					);
				?>
    		</td>
        </tr>
    	<tr>
    		<td>Pixel(s): </td>
    		<td>
    			<?php
					echo form_textarea(array(
						'name'	=> 'pixel',
						'rows'	=> 5,
						'cols'	=> 80
					));
				?>
    		</td>
        </tr>
        <tr>
        	<td colspan=2 align='center'>
    			<?php
					echo form_input(array(
						'type'	=> 'submit',
						'name'	=> 'submit',
						'value'	=> 'Submit'
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