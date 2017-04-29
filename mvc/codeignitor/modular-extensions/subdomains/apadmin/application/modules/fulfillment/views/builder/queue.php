<?php

?>


<!--
<div class="row-fluid">

	<div class="span12">
--> 
		<h3 class="heading">Fulfillment Builder Queue</h3>

		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">

			<table class="table table-striped table-bordered table-condensed dTableR uafix" id="service_table" aria-describedby="service_table_info">
				<thead>
					<tr class="nosort" role="row">
						<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="5%">ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="10%">Client ID</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1">Domain</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="10%">Build Slug</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="5%">Version</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="5%">Auto</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="10%">Status</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="10%">Spun</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="15%">Added</th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="service_table" rowspan="1" colspan="1" width="15%">Actions</th>
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
							<td><?php echo $value['id']; ?></td>
							<td><a href="http://my.hostingaccountsetup.com/admin/clientmgr/client_profile.php?clientid=<?php echo $value['client_id']; ?>" target="_blank"><?php echo $value['client_id']; ?></a></td>
							<td><a href="http://<?php echo $value['domain']; ?>" target="_blank"><?php echo $value['domain']; ?></td>
							<td><?php echo $value['slug']; ?></td>
							<td><?php echo $value['version_id']; ?></td>
							<td><?php echo ($value['auto_build'] == 1)? 'auto': 'manual'; ?></td>
							<td><?php echo $value['status']; ?></td>
							<td id="spun<?php echo $value['id'];?>"><?php if ($value['content_spinner'] == 1 ) : echo $value['spin_content'] == 1 ? 'yes' : 'no';  else: echo "---" ;endif;?>
							<td><?php echo $value['date_added']; ?></td>
							<td>
								
								<a data-toggle="modal" data-backdrop="static" href="#notesmodel" title="Notes for <?php echo $value['domain'];?>" class="notesview" id="notes_<?php echo $value['id'];?>">
									<i class="splashy-document_copy"></i>
								</a>&nbsp;&nbsp;
								<a data-toggle="modal" data-backdrop="static" href="#addnote" title="Adding Notes for <?php echo $value['domain'];?>" class="notesadd" id="addnotes_<?php echo $value['id'];?>">
								<i class="splashy-document_letter_add"></i></a>&nbsp;&nbsp;

								<a data-toggle="modal" data-backdrop="static" href="#notesmodel" title="Building for <?php echo $value['domain'];?>" class="buildit" id="buildit_<?php echo $value['id'];?>">
									<i class="splashy-thumb_up"></i>
								</a>&nbsp;&nbsp;
								<?php if ($value['content_spinner'] == 1 ) : ?>
								<a data-toggle="modal" data-backdrop="static" href="#notesmodel" rel="http://<?php echo $value['domain']; ?>/import.php?agent=<?php echo $this->session->userdata('login_id');?>" title="Spin Content for <?php echo $value['domain'];?>" class="spinit" id="spinit_<?php echo $value['id'];?>">

								<i class="splashy-folder_classic_edit"></i>
								</a>&nbsp;&nbsp;
								<?php endif; ?>
								<!--
								<a href="<?php echo $this->config->item('subdir').'/fulfillment/builder/build/'.$value['id']; ?>" title="Build <?php echo $value['domain'];?>" class="build" id="build_<?php echo $value['id'];?>" onclick="return confirm_build();">
									<i class="splashy-thumb_up"></i>
								</a>&nbsp;&nbsp;

								<a href="http://<?php echo $value['domain']; ?>" title="View <?php echo $value['domain'];?>" class="view" id="view_<?php echo $value['id'];?>" target="_blank">
									<i class="splashy-document_letter_add"></i>
								</a>&nbsp;&nbsp; -->

								<a href="<?php echo $this->config->item('subdir').'/fulfillment/builder/close/'.$value['id']; ?>" title="Close <?php echo $value['domain'];?> Ticket" class="build" id="close_<?php echo $value['id'];?>" onclick="return confirm_close();">
									<i class="splashy-thumb_down"></i>
								</a>&nbsp;&nbsp;

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
<div class="modal hide" id="addnote">
	<form method="post" action="">
			<div class="modal-header">
				<button class="close closemodel" data-dismiss="modal">×</button>
				<h3 id="noteadd">Add Note</h3>
			</div>
			<div class="modal-body" id="addbody">
			
			<div class="formSep">
				<div class="row-fluid">
					<div class="span8">
						<label>Note <span class="f_req">*</span></label>
						<textarea id="newnote" name="newnote"></textarea>
						<span class="help-block" id="helplink_text"></span>
					</div>
				</div>
					
			</div>
			</div>
			<div class="modal-footer">
					<input type="hidden" id="queue_id" name="queue_id" value=''>

					<button class="btn btn-danger closemodal" type="button">Close</button>	
					<button class="btn btn-inverse" id="savenote" type="button" role="button">Add Note</button>
			</div>
		</form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	
	$(".notesview").click(function(){
		
		$("#notesbody").html("<center><img src='<?php echo $this->config->item('subdir');?>/resources/apadmin/img/346.gif'></center>");
		var heading = $(this).attr('title');
		$("#noteheader").html(heading);
		
		var id = $(this).attr('id').replace('notes_','');
		$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('subdir');?>/ajax/apadmin/sitebuilder/notes",
			data: "id=" + id ,
			  success: function(data){
					$("#notesbody").html(data);
				  }
		});
		
	});
	
	$(".spinit").click(function(){
		var ifrm = $(this).attr('rel');
		var heading = $(this).attr('title');
		var id = $(this).attr('id').replace('spinit_',"");
		$("#noteheader").html(heading);
		$("#notesbody").html('<button class="btn btn-danger" onClick="markspun('+ id + ');">Mark content as spun</button><p id="spuner">Note: spin content below first.</p><iframe src="'+ifrm+'" width="500" height="403" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>')
	});
	$(".buildit").click(function(){
		
		$("#notesbody").html("<center><img src='<?php echo $this->config->item('subdir');?>/resources/apadmin/img/346.gif'></center>");
		var heading = $(this).attr('title');
		$("#noteheader").html(heading);
		
		var id = $(this).attr('id').replace('buildit_','');
		
		$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('subdir');?>/ajax/apadmin/sitebuilder/build",
			data: "id=" + id ,
			  success: function(data){
					$("#notesbody").html(data);
				  }
		});
	});
	$(".notesadd").click(function(){
		
		var heading = $(this).attr('title');
		$("#noteadd").html(heading);
		$("#helplink_text").html('');
		var id = $(this).attr('id').replace('addnotes_','');
		$("#queue_id").val(id);
		
	});
	$("#savenote").click(function(e){
		e.preventDefault();
		var id = $("#queue_id").val();
		var notes = $("#newnote").val();
		$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('subdir');?>/ajax/apadmin/sitebuilder/addnote",
			data: "id=" + id + "&notes=" + notes,
			  success: function(data){
					$("#helplink_text").html(data);
					$("#newnote").val('');
					$("#queue_id").val('');
					//setTimeout(function(){$(".modal").modal('hide');},5000)
				  }
		});
	});
	$(".close").click(function(){
		$(".modal").modal('hide');
	});
});
function confirm_close()
{
	return confirm('Are you sure you want to close this ticket?');
}

function confirm_build()
{
	return confirm('Are you sure you want to build this site?');
}
function markspun(id){
	$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('subdir');?>/ajax/apadmin/sitebuilder/markspun",
			data: "id=" + id ,
			  success: function(data){
					$("#spun" + id).html('yes');
					$("#spuner").html(data);
				  }
		});
}
</script>