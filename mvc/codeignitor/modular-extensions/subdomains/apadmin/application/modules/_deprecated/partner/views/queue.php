<?php

//$this->debug->show($queue);

?>

<br><br>
<!--<a class="button blue" href="/pages/add" style="margin-left: 40px;">Add Page</a> -->

<table id="box-table-a" summary="Sales Funnel Pages">
    <thead>
    	<tr>
    		<th scope="col">ID</th>
    		<th scope="col">Company</th>
        	<th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col">Added</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
		<?php 
		// iterate available pages
		foreach ($queue AS $key => $value):
		?>
		
	        <tr id="<?php echo $value['id']; ?>">
	        	<td><?php echo $value['id']; ?></td>
	            <td><?php echo $value['company']; ?></td>
	            <td><?php echo $value['first_name'].' '.$value['last_name']; ?></td>
	            <td><?php echo $value['email']; ?></td>
	            <td><?php echo $value['phone']; ?></td>
	            <td><?php echo $value['username']; ?></td>
	            <td><?php echo $this->password->decrypt($value['password'],$value['password_salt']); ?></td>
	            <td><?php echo date('m/d/Y',strtotime($value['date_added'])); ?></td>
	            <td>
	            	<?php
	            	// determine if we need to show Activate or Deactivate button based on partner avtice status
	            	if ($value['active'] == 1):
	            	?>
	            		<a href="queue/deactivate/<?php echo $value['id']; ?>" class="button small blue">Deactivate</a>
	            	<?php
	            	else: 	// partner is not active
	            	?>
	            		<a href="queue/activate/<?php echo $value['id']; ?>" class="button small blue">Activate</a>
	            	<?php
	            	endif;
	            	?>
	            </td>
	        </tr>
		
		<?php 
		endforeach;
		?>
    </tbody>
</table>