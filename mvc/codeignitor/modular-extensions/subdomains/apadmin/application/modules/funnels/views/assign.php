<?php


$drop='';
foreach($partners as $partner) :
	$s = $partner_id == $partner['id'] ? ' selected="selected"':'';
	$drop .="<option value='{$partner['id']}'>{$partner['company']}</option>";
endforeach;
?>
<p>This page will not remove funnels from a partner. To do so see your friendly database administrator.</p>
<form method="post" action="" id="frm" name="frm">
	<select name="partner_id" onChange="document.frm.submit();"><option value="1">Select</option>
		<?php echo $drop;?>
	</select>
	
</form>
<form method='post' action=''>
<?php

foreach($funnels as $funnel) :
	$c = empty($funnel['member']) ? '' : ' checked';
	echo "<input type='checkbox' name='funnels[]' value='{$funnel['id']}'$c>&nbsp;{$funnel['name']}<br>";
	
endforeach;

?>
	<input type="hidden" name="partner_id" value="<?php echo $partner_id;?>">
	<input type="submit" value="save">
</form>