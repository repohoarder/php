<?php


?>
<div id="datacontainer">
	<?php 
	echo "<form method='post' action='/partner/pricing/edit' name='brndform' id='brndform'>
		<select name='brandid' onChange='document.brndform.submit();'>
			<option value=''>Select Brand</option>";
	foreach($brands as $brandid=>$brnd) :
		$c = $brand_id == $brandid ? " selected='selected'": '';
		echo "<option value='$brandid'$c>{$brnd['name']}</option>";
	endforeach;
	echo "</select>
		</form><div style='clear:both;'></div>";
	
	?>
	<table style='width:920px;'>
		<tr>
			<th>Service</th>
			<th>Variant</th>
			<th>Term</th>
			<th>Price</th>
			<th>Setup Fee</th>
			<th>Cost</th>
			<th>&nbsp;</th>
		</tr>
		<?php 
		$mod =1;
		foreach($pricing as $k=>$record) : 
			$class = $mod % 2 == 1 ? "class='even'" : "class='odd'";
			$mod++;
			?>
			<tr <?php echo $class;?>>
			<td><?php echo $record['name'];?></td>
			<td><?php echo $record['variant'];?></td>
			<td><?php echo $record['num_months'];?></td>
			<td><input type="text" name="price" id="price<?php echo $record['default_id'];?>" value="<?php echo $record['price'];?>"></td>
			<td><input type="text" name="setup_fee" id="setup_fee<?php echo $record['default_id'];?>" value="<?php echo $record['setup_fee'];?>"></td>
			<td><input type="text" name="cost"  id="cost<?php echo $record['default_id'];?>" value="<?php echo $record['cost'];?>"></td>
			<td><input type="submit" class='saveprice'  id="sub<?php echo $record['default_id'];?>" value="Save"></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>


