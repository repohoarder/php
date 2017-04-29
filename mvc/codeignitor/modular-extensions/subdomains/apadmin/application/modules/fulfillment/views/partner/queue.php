


<!--
<div class="row-fluid">

	<div class="span12">
-->
		<h3 class="heading">Partner Fulfillment Queue</h3>

		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">

			<table class="table table-striped table-bordered table-condensed dTableR uafix" id="service_table" aria-describedby="service_table_info">
				<thead>
					<tr class="nosort" role="row">
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Company</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Name</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Email</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Phone</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Username</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Password</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Date</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Status</th>
					</tr>
				</thead>

				<tbody role="alert" aria-live="polite" aria-relevant="all">

					<?php
					// iterate services
					$i=0;
					foreach ($queue AS $key => $value):

						// set odd/even class variable
						$class = ($i % 2 == 0)? 'even': 'odd';
					?>

						<tr id="record_<?php echo $value['id']; ?>" class="<?php echo $class; ?>">
							<!-- <td><input type="checkbox" name="row_sel" class="row_sel" /></td> -->
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
				            		<a href="<?php echo $this->config->item('subdir'); ?>/fulfillment/partner/deactivate/<?php echo $value['id']; ?>" class="button small blue">Deactivate</a>
				            	<?php
				            	else: 	// partner is not active
				            	?>
				            		<a href="<?php echo $this->config->item('subdir'); ?>/fulfillment/partner/activate/<?php echo $value['id']; ?>" class="button small blue">Activate</a>
				            	<?php
				            	endif;
				            	?>
				            </td>
						</tr>


					<?php
						// increment counter
						$i++;

					endforeach;
					?>

				</tbody>
			</table>

		</div>
<!--
	</div>

</div>
-->




























