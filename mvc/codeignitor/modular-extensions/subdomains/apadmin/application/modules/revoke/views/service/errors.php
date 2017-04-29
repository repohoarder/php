<?php

//$this->debug->show($services);

?>


<!--
<div class="row-fluid">

	<div class="span12">
-->
		<h3 class="heading">Revoke Errors</h3>

		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">

			<table class="table table-striped table-bordered table-condensed dTableR uafix" id="service_table" aria-describedby="service_table_info">
				<thead>
					<tr class="nosort" role="row">
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="5%">Error ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="5%">Pack ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Error</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="10%">Added</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="10%">Actions</th>
					</tr>
				</thead>

				<tbody role="alert" aria-live="polite" aria-relevant="all">

					<?php
					// iterate services
					$i=0;
					foreach ($errors AS $key => $value):

						// set odd/even class variable
						$class = ($i % 2 == 0)? 'even': 'odd';
					?>

						<tr id="record_<?php echo $value['id']; ?>" class="<?php echo $class; ?>">
							<!-- <td><input type="checkbox" name="row_sel" class="row_sel" /></td> -->
							<td><?php echo $value['id']; ?></td>
							<td><?php echo $value['_id']; ?></td>
							<td title="<?php echo $value['error']; ?>"><?php echo $value['error']; ?></td>
							<td><?php echo date('m/d/Y',strtotime($value['error_inserted'])); ?></td>
							<td>
								<a href="<?php echo $this->config->item('subdir'); ?>/revoke/service/dismiss/<?php echo $value['id']; ?>" onclick="return confirm_dismiss();">
									<img src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/img/gCons/edit.png" title="Dismiss the Error">
								</a>
								&nbsp;&nbsp;
								<a href="<?php echo $this->config->item('subdir'); ?>/revoke/service/close/<?php echo $value['id']; ?>" onclick="return confirm_close();">
									<img src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/img/gCons/delete-item.png" title="Close the Error">
								</a>
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

<script type="text/javascript">

function confirm_dismiss()
{
	return confirm('Are you sure you want to dismiss the error?');
}

function confirm_close()
{
	return confirm('Are you sure you want to close the error?');
}

</script>