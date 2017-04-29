<?php

//$this->debug->show($services);

?>


<!--
<div class="row-fluid">

	<div class="span12">
-->
		<h3 class="heading">Page Actions Listing</h3>

		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">

			<table class="table table-striped table-bordered table-condensed dTableR uafix" id="service_table" aria-describedby="service_table_info">
				<thead>
					<tr class="nosort" role="row">
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Action ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Name</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Add Service Default</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Date Added</th>
						<th>Actions</th>
					</tr>
				</thead>

				<tbody role="alert" aria-live="polite" aria-relevant="all">

					<?php
					// iterate services
					$i=0;
					foreach ($actions AS $key => $value):

						// set odd/even class variable
						$class		= ($i % 2 == 0)? 'even': 'odd';
					?>
						<tr id="record_<?php echo $value['id']; ?>" class="<?php echo $class; ?>">
							<!-- <td><input type="checkbox" name="row_sel" class="row_sel" /></td> -->
							<td><?php echo $value['id']; ?></td>
							<td><?php echo $value['name']; ?></td>
							<td><?php echo $value['add_service_default']; ?></td>
							<td><?php echo $value['date_added']; ?></td>
							<td><!--<a href="<?php echo $this->config->item('subdir'); ?>/page/create/<?php echo $value['id']; ?>">Edit</a>--></td>
						</tr>


					<?php
						// increment counter
						$i++;

					endforeach;
					?>

				</tbody>
			</table>
			
			<form class="form-horizontal" id="loginsave_form" method="post">
				<fieldset>
					<div class="control-group formSep">
						<label for="name" class="control-label">Action Name</label>
						<div class="controls">
							<input type="text" id="name" name="name" class="input-xlarge" />
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<button class="btn btn-gebo" id="loginsave" type="submit">Add Action</button>
						</div>
					</div>
				</fieldset>
			</form>

		</div>
<!--
	</div>

</div>
-->
