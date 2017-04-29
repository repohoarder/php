<?php

?>

<form action="/commissions/add" method="POST">

<table width="50%" align="left">

	<?php
	if ($error):
	?>

	<tr>
		<td colspan="2"><p style="color:red;font-weight:bold;"><?php echo $error; ?></p></td>
	</tr>

	<?php
	endif;
	?>

	<tr>
		<td>Affiliate ID: </td>
		<td><input type="text" name="affiliate_id" value=""></td>
	</tr>

	<tr>
		<td>Payment Term: </td>
		<td><select name="type"><option value="rev_share" selected>Revenue Share</option><option value="cpa">CPA</option><option value="net_cpa">Net CPA</option></select></td>
	</tr>

	<tr>
		<td>Amount: <small>(% if rev share, $ if cpa)</small> </td>
		<td><input type="text" name="amount" value="75"></td>
	</tr>

	<tr>
		<td>Days in Arrears: </td>
		<td><input type="text" name="arrears" value="45"></td>
	</tr>

	<tr>
		<td>Start Day: </td>
		<td>
			<select name="start">
				<option value="Sunday">Sunday</option>
				<option value="Monday" selected>Monday</option>
				<option value="Tuesday">Tuesday</option>
				<option value="Wednesday">Wednesday</option>
				<option value="Thursday">Thursday</option>
				<option value="Friday">Friday</option>
				<option value="Saturday">Saturday</option>
			</select>
		</td>
	</tr>

	<tr>
		<td>End Day: </td>
		<td>
			<select name="end">
				<option value="Sunday" selected>Sunday</option>
				<option value="Monday">Monday</option>
				<option value="Tuesday">Tuesday</option>
				<option value="Wednesday">Wednesday</option>
				<option value="Thursday">Thursday</option>
				<option value="Friday">Friday</option>
				<option value="Saturday">Saturday</option>
			</select>
		</td>
	</tr>

	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Add Payment Term"></td>
	</tr>

</table>

</form>


<br><br><br>
<br><br><br>
<br><br><br>

<table width="51%" align="left">

	<tr style="font-weight:bold;">
		<td>Affiliate ID</td>
		<td>Type</td>
		<td>Amount</td>
		<td>Arrears</td>
		<td>Start</td>
		<td>End</td>
	</tr>

	<?php
	// iterate all current terms
	foreach ($terms AS $key => $value):
	?>

		<tr>
			<td><?php echo $value['affiliate_id']; ?></td>
			<td><?php echo $value['type']; ?></td>
			<td><?php echo $value['amount']; ?></td>
			<td><?php echo $value['arrears']; ?></td>
			<td><?php echo $value['start_day']; ?></td>
			<td><?php echo $value['end_day']; ?></td>
		</tr>

	<?php
	endforeach;
	?>

</table>