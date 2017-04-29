<?php

//$this->debug->show($services);

?>


<!--
<div class="row-fluid">

	<div class="span12">
-->
		<h3 class="heading">Available Services</h3>

		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">

			<table class="table table-striped table-bordered table-condensed dTableR uafix" id="service_table" aria-describedby="service_table_info">
				<thead>
					<tr class="nosort" role="row">
						<th><a href="/service/create"><i class="splashy-add clearfields"></i></a></th>
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">BSID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Plan ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Name</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Slug</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Variant</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1"># Months</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Price</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Setup Fee</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Cost</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody role="alert" aria-live="polite" aria-relevant="all">

					<?php
					// iterate services
					$i=0;
					foreach ($services AS $key => $value):

						// set odd/even class variable
						$class = ($i % 2 == 0)? 'even': 'odd';
					?>

						<tr id="record_<?php echo $value['service_id']; ?>" class="<?php echo $class; ?>">
							<td><a href="<?php echo $this->config->item('subdir'); ?>/service/create/<?php echo $value['brand_service_id'];?>"><i class="splashy-pencil img"></i></a></td>
							<!-- <td><input type="checkbox" name="row_sel" class="row_sel" /></td> -->
							<td><?php echo $value['service_id']; ?></td>
							<td><?php echo $value['brand_service_id']; ?></td>
							<td><?php echo $value['uber_plan_id']; ?></td>
							<td><?php echo $value['name']; ?></td>
							<td><?php echo $value['slug']; ?></td>
							<td><?php echo $value['variant']; ?></td>
							<td><?php echo $value['num_months']; ?></td>
							<td><?php echo $value['price']; ?></td>
							<td><?php echo $value['setup_fee']; ?></td>
							<td><?php echo $value['cost']; ?></td>
							<td><a href="<?php echo $this->config->item('subdir'); ?>/page/edit/<?php echo $value['service_id']; ?>">Edit</a></td>
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
