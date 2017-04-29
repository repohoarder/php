 <table class="table table-striped table-bordered table-condensed dTableR uafix" data-rowlink="a">
		<thead>
			<tr>
				<th>Date</th>
				<th>Note</th>
				<th>Admin</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($notes as $note): ?>
			<tr>
				<td><?php echo date('m/d/y',strtotime($note['date_added']));?></td>
				<td><?php echo $note['message'];?></td>
				<td><?php echo $note['name'];?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
 </table>
