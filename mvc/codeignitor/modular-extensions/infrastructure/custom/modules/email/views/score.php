
<?php

// if error, display it
if (isset($error)) 	echo '<p style="font-weight:bold;font-size:18pt;color:red;">'.$error.'</p>';

?>

<form action="/email/score" method="POST">

	<table width="75%" align="center">
		<tr>
			<td>Email Message <small>(Including headers and content)</small></td>
		</tr>
		<tr>
			<td><textarea rows="20" cols="120" name="email"></textarea></td>
		</tr>
		<tr>
			<td align="center"><input type="submit" name="submit" value="Get My Score" /></td>
		</tr>
	</table>

</form>


<?php

// if we have score, display it
if (isset($score)) 	echo '<p style="font-weight:bold;font-size:18pt;color:green;">SCORE: '.$score.'</p>';

// if we have report and score, display it
if (isset($report)) echo $this->debug->show($report);
