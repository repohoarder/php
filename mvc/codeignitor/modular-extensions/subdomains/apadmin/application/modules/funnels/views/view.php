<?php

//$this->debug->show($list);

?>


<!--
<div class="row-fluid">

	<div class="span12">
-->
		<h3 class="heading">Partner Funnels Listing</h3>

		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">

			<table class="table table-striped table-bordered table-condensed dTableR uafix" id="service_table" aria-describedby="service_table_info">
				<thead>
					<tr class="nosort" role="row">
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">ID</th>
						<?php if ($partner_id): ?><th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Funnel ID</th><?php endif; ?>
						<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Affiliate ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Offer ID</th>-->
						<?php if (!$partner_id): ?><th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Name</th><?php endif; ?>
						<?php if (!$partner_id): ?><th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Funnel Type</th><?php endif; ?>
						<?php if (!$partner_id): ?><th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Default Page ID</th><?php endif; ?>
						<?php if (!$partner_id): ?><th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Is Default</th><?php endif; ?>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Added</th>
						<?php if ($partner_id): ?><th>Actions</th><?php endif; ?>
					</tr>
				</thead>

				<tbody role="alert" aria-live="polite" aria-relevant="all">

					<?php
					// iterate services
					$i=0;
					foreach ($list AS $key => $value):

						// set odd/even class variable
						$class		= ($i % 2 == 0)? 'even': 'odd';
					?>
						<tr id="record_<?php echo $value['id']; ?>" class="<?php echo $class; ?>">
							<!-- <td><input type="checkbox" name="row_sel" class="row_sel" /></td> -->
							<td class="id"><?php echo $value['id']; ?></td>
							<?php if ($partner_id): ?><td><?php echo $value['funnel_id']; ?></td><?php endif; ?>
							<!--<td><?php echo $value['affiliate_id']; ?></td>
							<td><?php echo $value['offer_id']; ?></td>-->
							<?php if (!$partner_id): ?><td><?php echo $value['name']; ?></td><?php endif; ?>
							<?php if (!$partner_id): ?><td><?php echo $value['funnel_type']; ?></td><?php endif; ?>
							<?php if (!$partner_id): ?><td><?php echo $value['default_page_id']; ?></td><?php endif; ?>
							<?php if (!$partner_id): ?><td><?php if ($value['is_default']): ?>Yes<?php else: ?>No<?php endif; ?></td><?php endif; ?>
							<td><?php echo date('m/d/Y',strtotime($value['date_added'])) ?></td>
							<?php if ($partner_id): ?><td><a href="<?php echo $this->config->item('subdir'); ?>/funnel/edit/<?php echo $value['funnel_id']; ?>">Edit</a> <a href="<?php echo $this->config->item('subdir'); ?>/funnels/prices/<?php echo $value['funnel_id']; ?>/<?php echo $value['partner_id']; ?>">Pricings</a></td><?php endif; ?>
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
