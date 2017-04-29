<?php

//$this->debug->show($services);

?>


<!--
<div class="row-fluid">

	<div class="span12">
-->
		<h3 class="heading">Partner Listing</h3>

		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">

			<table class="table table-striped table-bordered table-condensed dTableR uafix" id="service_table" aria-describedby="service_table_info">
				<thead>
					<tr class="nosort" role="row">
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Status</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Company</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Name</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Username</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Password</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Added</th>
						<th>Actions</th>
					</tr>
				</thead>

				<tbody role="alert" aria-live="polite" aria-relevant="all">

					<?php
					// iterate services
					$i=0;
					foreach ($list AS $key => $value):

						// set odd/even class variable
						$class		= ($i % 2 == 0)? 'even': 'odd';
						if ($value['uber_client_id']>0)
						{
							$class	.= ' registered';
							$status	 = 'Registered';
						}
						elseif($value['active']==1)
						{
							$class	.= ' active';
							$status	 = 'Activated';
						}
						else
						{
							$class	.= ' queued';
							$status	 = 'Queued';
						}
					?>
						<tr id="record_<?php echo $value['id']; ?>" class="<?php echo $class; ?>">
							<!-- <td><input type="checkbox" name="row_sel" class="row_sel" /></td> -->
							<td class="id"><?php echo $value['id']; ?></td>
							<td class="status"><?php echo $status; ?></td>
							<td><?php echo $value['company']; ?></td>
							<td><?php echo $value['first_name'].' '.$value['last_name']; ?></td>
							<td><?php echo $value['username']; ?></td>
							<td><?php echo $this->password->decrypt($value['password'],$value['password_salt']); ?></td>
							<td><?php echo date('m/d/Y',strtotime($value['date_added'])) ?></td>
							<td><a href="<?php echo $this->config->item('subdir'); ?>/partner/edit/<?php echo $value['id']; ?>">Edit</a> <a href="<?php echo $this->config->item('subdir'); ?>/partner/funnels/<?php echo $value['id']; ?>">Funnels</a></td>
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
