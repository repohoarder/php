<h1>Current Build List</h1><br><br>
<table style="width:100%;text-align:left;">
	<tr>
		<th>Build</th>
		<th>Description</th>
		<th>Added</th>
		<th>Action</th>
	</tr>
<?php
if(isset($list)) :
	
	foreach($list as $k=>$record):
		echo "
		<tr>
			<td><a href='/builder/edit/".$record['id']."'>".$record['name']. "</a></td>
			<td>".$record['description']. "</td>
			<td>".date('Y-m-d',strtotime($record['date_added'])). "</td>
			<td>
				<form method='post' action='/builder/install/{$record['name']}'>".$record['id']. "
				Client:<input type='text' name='client_id' value=''>
				<br>Partner id<input type='text' name='partner_id' value=''>
				<br>Domain<input type='text' name='domain' value=''>
				<br><input type='submit' value='build' name='buildit'>
			</form></td>
		</tr>";
	endforeach;
	//print_r($list);
endif;
?>
</table>
