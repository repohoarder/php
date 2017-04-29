<?php 

?>

<br><br>
<a class="button blue" href="/pages/add" style="margin-left: 40px;">Add Page</a>

<table id="box-table-a" summary="Sales Funnel Pages">
    <thead>
    	<tr>
    		<th scope="col">ID</th>
        	<th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Slug</th>
            <th scope="col">URL</th>
            <th scope="col">Edit</th>
        </tr>
    </thead>
    <tbody>
		<?php 
		// iterate available pages
		foreach ($pages AS $key => $value):
		?>
		
	        <tr id="<?php echo $value['id']; ?>">
	        	<td><?php echo $value['id']; ?></td>
	            <td><?php echo $value['name']; ?></td>
	            <td width='250px'><?php echo $value['description']; ?></td>
	            <td><?php echo $value['slug']; ?></td>
	            <td><a href='http://setup.brainhost.com<?php echo $value['uri']; ?>' target='_blank'><?php echo $value['uri']; ?></a></td>
	            <td align='center'><a href="/pages/edit/<?php echo $value['id']; ?>" class="button small blue">Edit Page</a></td>
	        </tr>
		
		<?php 
		endforeach;
		?>
    </tbody>
</table>