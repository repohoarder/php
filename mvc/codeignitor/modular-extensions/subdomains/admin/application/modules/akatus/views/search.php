<script>
$(function(){
	target= 'app_categories';

	$(".status").click(function(){
		var id = $(this).attr('id');
		$.ajax({
			  type: "POST",
			  url: "/ajax/akatus/status/getstatus",
			  data: 'tid=' + id,
			  success: function(data){
					$("#"+ id).html(data);
				  }

			}); 
	});
	});
</script>
<div style='min-height:400px;'>
<form method="post" action="/akatus/search">
	<input type="text" name="search">
	<input type="submit" value='search'>
</form>
	<div style="clear:both"></div>
<table style='font-size:.8em;width:100%;'>
	<tr>
		<th>Date</th>
		<th>First</th>
		<th>Last</th>
		<th>Phone</th>
		<th>Email</th>
		<th>Amount</th>
		<th>Order ID</th>
		<th>Transaction</th>
		<th>Status</th>
	</tr>
	<?php foreach ($data as $record) : ?>
	<tr>
		<td><?php echo date("m/d/y",strtotime($record['datetime']));?></td>
		<td><?php echo $record['first_name'];?></td>
		<td><?php echo $record['last_name'];?></td>
		<td><?php echo $record['phone'];?></td>
		<td><?php echo $record['email'];?></td>
		<td><?php echo $record['amount'];?></td>
		<td><?php echo $record['uber_orderid'];?></td>
		<td><?php echo $record['transaction'];?></td>
		<td nowrap="nowrap"><a href='javascript:void(0);' class='status' id="<?php echo $record['transaction'];?>"><?php echo $record['response'];?></a></td>
	</tr>
	<?php endforeach; ?>
</table>
	</div>
