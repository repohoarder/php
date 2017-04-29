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
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Views</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Uniques</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Revenue</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">EPC</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Conversion %</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody role="alert" aria-live="polite" aria-relevant="all">

					<?php
					// iterate services
					$i=0;
					foreach ($statistics AS $key => $value):

						// set odd/even class variable
						$class = ($i % 2 == 0)? 'even': 'odd';
					?>

						<tr id="record_<?php echo $value['funnel_id']; ?>" class="<?php echo $class; ?>">
							<!-- <td><input type="checkbox" name="row_sel" class="row_sel" /></td> -->
							<td><?php echo $value['funnel_id']; ?></td>
							<td><?php echo $value['views']; ?></td>
							<td><?php echo $value['uniques']; ?></td>
							<td><?php echo $value['revenue']; ?></td>
							<td><?php echo $value['epc']; ?></td>
							<td><?php echo $value['conversion']; ?></td>
							<td>Edit</td>
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
