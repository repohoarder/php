<table>
	<tr><th>ID</th><th>Domain</th><th>Nameserver</th><th>Date Added</th></tr>
	<?php foreach($domains as $domain): ?>
		<?php if ($domain['active']): ?>
			<tr>
				<td> <?php echo $domain['id']; ?> </td>
				<td> <a href="domain_list/run/<?php echo $domain['id']; ?>"><?php echo $domain['domain']; ?></a> </td>
				<td> <?php echo $domain['nameserver']; ?> </td>
				<td> <?php echo $domain['date_added']; ?> </td>
			</tr>
		<?php endif; ?>
	<?php endforeach; ?>
</table>