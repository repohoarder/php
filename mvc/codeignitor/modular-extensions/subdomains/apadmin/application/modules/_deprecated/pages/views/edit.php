<?php 
// if no actions for this page, default variable to empty array
if ( ! isset($actions) OR empty($actions))	$actions 	= array();
?>


<br><br>

<p style="margin-left:50px;font-weight:bold;font-size: 18pt;">Page Details</p>
<table id="box-table-a" summary="Page Description">
    <thead>
    	<tr>
    		<th scope="col">ID</th>
        	<th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Slug</th>
            <th scope="col">URI</th>
            <th scope="col">Plan ID</th>
            <th scope="col">Layout</th>
            <th scope="col">Added</th>
        </tr>
    </thead>
    <tbody>
    	<tr>
    		<td><?php echo $page['id']; ?></td>
    		<td><?php echo $page['name']; ?></td>
    		<td><?php echo $page['description']; ?></td>
    		<td><?php echo $page['slug']; ?></td>
    		<td><?php echo $page['uri']; ?></td>
    		<td><?php echo $page['plan_id']; ?></td>
    		<td><?php echo $page['layout']; ?></td>
    		<td><?php echo date("F j, Y", strtotime($page['date_added'])); ?></td>
        </tr>
    </tbody>	
</table>

<p style="margin-left:50px;font-weight:bold;font-size: 18pt;">Page Actions</p>

<table width="100%">
	<tr>
		<td>
			<table id="box-table-a" summary="Page Actions">
			    <thead>
			    	<tr>
			    		<th scope="col">ID</th>
			        	<th scope="col">Name</th>
			            <th scope="col">Added</th>
			        </tr>
			    </thead>
			    <tbody>
					<?php 
					// iterate available pages
					foreach ($actions AS $key => $value):
					?>
					
				        <tr id="<?php echo $value['id']; ?>">
				        	<td><?php echo $value['id']; ?></td>
				            <td><?php echo $value['name']; ?></td>
				            <td><?php echo date("F j, Y", strtotime($value['date_added'])); ?></td>
				        </tr>
					
					<?php 
					endforeach;
					?>
			    </tbody>
			</table>
		</td>
		<td>
			<?php
			// initialize variables
			$action 	= 'pages/edit';
			$attributes	= array('method'	=> 'post');
			$hidden		= array('page_id'	=> $page['id']);

			// open the form
			echo form_open($action,$attributes,$hidden);
			?>
			<table summary="Add Action Form">
				<tr>
					<td colspan='2' align='center' style='font-weight:bold;font-size:16pt;'>Add Page Action</td>
				</tr>

				<?php
				// if there's an error, show it
				if($error):
				?>
					<tr>
						<td colspan='2' align='center' style='font-weight:bold;color:red;'><?php echo urldecode($error); ?></td>
					</tr>
				<?php
				endif;
				?>

				<tr>
					<td>Name</td>
					<td>
						<?php
						echo form_input(array(
							'type'	=> 'text',
							'name'	=> 'name',
							'value'	=> ''
						));
						?>
					</td>
				</tr>
				<tr>
					<td colspan='2' align='center'>
						<?php
						echo form_input(array(
							'type'	=> 'submit',
							'name'	=> 'submit',
							'value'	=> 'Add Action',
							'id'	=> 'button blue'
						));
						?>
					</td>
				</tr>
			</table>
			<?php
			echo form_close();
			?>
		</td>
	</tr>
</table>

<?php
// open form
echo form_open();
?>



<?php

// submit button

// close form
echo form_close();
?>