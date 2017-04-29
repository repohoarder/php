<?php

//$this->debug->show($services);

?>


<!--
<div class="row-fluid">

	<div class="span12">
-->
		<h3 class="heading">Page Listing</h3>

		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">

			<table class="table table-striped table-bordered table-condensed dTableR uafix" id="service_table" aria-describedby="service_table_info">
				<thead>
					<tr class="nosort" role="row">
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Page ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Name</th>
						<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Description</th>-->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Type</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Slug</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">URI</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Plan Slug</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Term</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Variant</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Theme</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Layout</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Date Added</th>
						<th>Actions</th>
					</tr>
				</thead>

				<tbody role="alert" aria-live="polite" aria-relevant="all">

					<?php
					// iterate services
					$i=0;
					foreach ($pages AS $key => $value):

						// set odd/even class variable
						$class		= ($i % 2 == 0)? 'even': 'odd';
					?>
						<tr id="record_<?php echo $value['id']; ?>" class="<?php echo $class; ?>">
							<!-- <td><input type="checkbox" name="row_sel" class="row_sel" /></td> -->
							<td><?php echo $value['id']; ?></td>
							<td><?php echo $value['name']; ?></td>
							<!--<td><?php echo $value['description']; ?></td>-->
							<td><?php echo $value['type']; ?></td>
							<td><?php echo $value['slug']; ?></td>
							<td><?php echo $value['uri']; ?></td>
							<td><?php echo $value['plan_slug']; ?></td>
							<td><?php echo $value['term']; ?></td>
							<td><?php echo $value['variant']; ?></td>
							<td><?php echo isset($value['theme'])? $value['theme'] : '&nbsp;'; ?></td>
							<td><?php echo $value['layout']; ?></td>
							<td><?php echo $value['date_added']; ?></td>
							<td><a href="<?php echo $this->config->item('subdir'); ?>/page/create/<?php echo $value['id']; ?>">Edit</a> <a href="<?php echo $this->config->item('subdir'); ?>/page/actions/<?php echo $value['id']; ?>">Actions</a> <a href="<?php echo $this->config->item('subdir'); ?>/page/services/<?php echo $value['id']; ?>">Services</a></td>
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
