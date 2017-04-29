<?php

//$this->debug->show($services);

?>


<!--
<div class="row-fluid">

	<div class="span12">
-->
		<h3 class="heading">Partner Pixel(s)</h3>

		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">

			<table class="table table-striped table-bordered table-condensed dTableR uafix" id="service_table" aria-describedby="service_table_info">
				<thead>
					<tr class="nosort" role="row">
						<th>&nbsp;</th>
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="5%">ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="10%">Partner ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="10%">Affiliate ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="10%">Offer ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Name</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Status</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Page</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Pixel(s)</th>
						<th>Action(s)</th>
					</tr>
				</thead>

				<tbody role="alert" aria-live="polite" aria-relevant="all">

					<?php
					// iterate services
					$i=0;
					foreach ($pixels AS $key => $value):

						// set odd/even class variable
						$class		= ($i % 2 == 0)? 'even': 'odd';

						// see if pixel is approved
						if ($value['approved'] == 1):

							$class 		.= ' active';
							$status 	= 'Approved';
							$link 		= '
							<a href="'.$this->config->item('subdir').'/partner/pixels/deactivate/'.$value['partner_id'].'/'.$value['id'].'" title="Deactivate Pixel #'.$value['id'].'" class="build" id="deactivate_'.$value['id'].'" onclick="return confirm_deactivate();">
								<i class="splashy-thumb_down"></i>
							</a>&nbsp;&nbsp;
							';

						else:	// pixel is not approved

							$class 		.= ' queued';
							$status 	= 'Waiting';							
							$link 		= '
							<a href="'.$this->config->item('subdir').'/partner/pixels/approve/'.$value['partner_id'].'/'.$value['id'].'" title="Approve Pixel #'.$value['id'].'" class="buildit" id="approve_'.$value['id'].'" onclick="return confirm_activate();">
								<i class="splashy-thumb_up"></i>
							</a>&nbsp;&nbsp;
							';

						endif;	// end seeing if pixel is approved

					?>
						<tr id="record_<?php echo $value['id']; ?>" class="<?php echo $class; ?>">
							<td><a href="/partner/pixels/edit/<?php echo $value['id'];?>/<?php echo $value['partner_id'];?>"><i class="splashy-pencil img"></i></a></td>
							<td class="id"><?php echo $value['id']; ?></td>
							<td><a href="<?php echo $this->config->item('subdir'); ?>/partner/view/<?php echo $value['partner_id']; ?>" target="_blank"><?php echo $value['partner_id']; ?></td>
							<td><?php echo ($value['affiliate_id'] == 0)? 'All': $value['affiliate_id']; ?></td>
							<td><?php echo ($value['offer_id'] == 0)? 'All': $value['offer_id']; ?></td>
							<td><?php echo $value['name']; ?></td>
							<td class="status"><?php echo $status; ?></td>
							<td><?php echo $value['type']; ?></td>
							<td>
								<a href="#notesmodel" class="notesview" id="view_<?php echo $value['id']; ?>" partner="view_<?php echo $value['partner_id']; ?>" title="View Pixel(s)" data-toggle="modal" data-backdrop="static">View</a></td>
							<td><?php echo $link; ?></td>
						</tr>


					<?php
						// increment counter
						$i++;

					endforeach;
					?>

				</tbody>
			</table>

		</div>


		<div class="modal hide" id="notesmodel">
			<div class="modal-header">
				<button class="close closemodel" data-dismiss="modal">×</button>
				<h3 id="noteheader">Modal header</h3>
			</div>
			<div class="modal-body" id="notesbody">
			</div>
			<div class="modal-footer">
				<a href="javascript:void(0);" class="btn close" data-dismiss="modal" class>Close</a>
			</div>
		</div>


<script type="text/javascript">

function confirm_deactivate()
{
	return confirm('Are you sure you want to deactivate pixel?');
}

function confirm_activate()
{
	return confirm('Are you sure you want to approve pixel?');
}

$(document).ready(function() {

	$(".notesview").click(function(){
		
		$("#notesbody").html("<center><img src='<?php echo $this->config->item('subdir');?>/resources/apadmin/img/346.gif'></center>");
		var heading = $(this).attr('title');
		$("#noteheader").html(heading);
		
		var id = $(this).attr('id').replace('view_','');
		var partner_id = $(this).attr('partner').replace('view_','');
		$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('subdir');?>/ajax/apadmin/partner/pixels",
			data: "id=" + id + "&partner_id=" + partner_id,
			  success: function(data){
					$("#notesbody").html(data);
				  }
		});
		
	});
});

</script>




