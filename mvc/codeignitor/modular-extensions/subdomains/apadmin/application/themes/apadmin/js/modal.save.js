

/* These functions implement ajax-enabled add/edit/delete of records
 * listed in a jQuery.tablesorter table. An identifier for the table
 * whose records are being listed should be set on the parent page
 * and contained in the 'target' variable. 
 */	

	/**
	 * Removes jQuery validation, clears form field values, and
	 * resets editing id.
	 */
	function openIcon(id){
	$("#id").val(id);
	$("#icon_dialog").modal('show');
}
function chooseIcon(iconid,icon){
	var id = $("#id").val();
	$.ajax({
        type: "POST",
        url: config_dir + "/ajax/apadmin/savemodal/menuicon",
        data: "id=" + id + "&iconid=" + iconid,
	      success: function(data){
	      		var htm = "<i class='" + icon + "'></i> <a href='javascript:void(0);' onClick=\"openIcon('"+ id +"');\">Edit</a>";
	      		$("#exicon"+id).html(htm);
	      		$(".modal").modal('hide');
	          }
  	}); 
}
function doAssociate(cell, type,header) {
				cell = $(cell);
				var id = cell.parent().attr('id').replace('record_', '');
	        	//$('#assoc_dialog').data('title.dialog', 'Associate ' + type.charAt(0).toUpperCase()+ type.substr(1)  + ' to Roles');
	        	$("#assocLabel").html(header);
	        	$('#assoc_dialog').modal('show');
	        	$('#assoc_dialog > table').hide();
	        	if (type != 'menutoroles') {
					$.getJSON(config_dir + '/ajax/apadmin/assoc/' + type + '/?id=' + id,
				        function(data){
				        	if (!data.error) {
				        		$('#assoc_grid').empty();
					        	$('#assoc_dialog > table').show();
					        	$('#assoc_type').val(type);
				        		$('#assoc_id').val(id);
	
				        		var columns = 3;
				        		
								var ctr = columns;
					        	for (var record_id in data.records) {
									if (ctr++ % columns == 0) {
										var tr = document.createElement('tr');
										$(tr).attr('class', 'choicerow');
									}
									var td = document.createElement('td');
									
									var cb = document.createElement('input');
									$(cb).attr('type', 'checkbox');
									$(cb).attr('class', 'assoc_checked');
									$(cb).attr('id', 'assoc_' + data.records[record_id].id);
									if (data.records[record_id].member >= '1') {
										$(cb).attr('checked', 'checked');
									}
									$(td).append(cb);
									$(tr).append(td);
									
									td = document.createElement('td');
									$(td).text(data.records[record_id].name);
									$(td).attr('class', 'role choice');
									$(tr).append(td);
	
									if (ctr % columns == 0) {
										$('#assoc_grid').append(tr);
									}
					        	}
					        	// add last row if necessary
								if (ctr % columns != 0) {
									$('#assoc_grid').append(tr);
								}
				        	} else {
					        	alert(data.error);
					        	$('#assoc_dialog').dialog('close');
				        	}
				        }
			        ); 
	        	} else {
					$.get(config_dir + '/ajax/apadmin/assoc/' + type + '/?id=' + id,
					        function(data){
					        	if (!data.error) {
					        		$('#assoc_grid').empty();
						        	$('#assoc_dialog > table').show();
						        	
						        	
						        	$('#assoc_type').val(type);
					        		$('#assoc_id').val(id);
					        		
									var tr = document.createElement('tr');
									$(tr).attr('class', 'choicerow');
									
									var td = document.createElement('td');

									$(td).html(data);
									
									$(tr).append(td);
									$('#assoc_grid').append(tr);
					        	}
				        	}
					);		        	
				}
			}
	function clearFormFields() {
		 $("#" + target + "_form").closest('form').find("input[type=text], textarea").val("");
		// reset target record id 
		$('#id').val('0');
	}
	
	/**
	 * Opens dialog for adding a new record.
	 */
	function doAdd() {
    	$('#' + target + '_dialog').dialog('open');
    	$('#' + target + '_dialog .loading').hide();
    	$('#' + target + '_dialog form.validate').fadeIn();
    	clearFormFields();
	}

	/**
	 * Opens dialog for editing an existing record and loads
	 * the appropriate data for editing.
	 * @param img : dom object whose id is used to identify which record to load
	 */
	function doEdit(img) {
		// open dialog
		clearFormFields();
		$('#' + target + '_dialog').modal('show');	
		// get id of record to edit
		img = $(img);
		var id = img.attr('id').replace('edit_', '');
		$('#error' + target).hide();
		
		// send ajax request for data
		$.getJSON(config_dir + '/ajax/apadmin/recordload?id=' + id + '&target=' + target,
	        function(data){
	        	if (!data.error) {
	        		// data received, hide loading graphic and show form
		    		//clearFormFields();
		    	
	        		// set values of input fiels in edit form (input field 
	        		// id matches identifier used in json response) 
		        	for (var field in data.record[0]) {
		        		
		        		if ($('#' + field)) {
		        			$('#' + field).val(data.record[0][field]);
		        		}
		        	}
		        	if("#pass_check"){
		        		$("#pass_check").val('');
		        	}
	        	} else {
	        		// alert if error
		        	alert(data.record.error);
		        	$('#' + target + '_dialog').modal('hide');
	        	}
	        }
	    ); 
	}
	
	/**
	 * Processes delete request.
	 * @param img : dom object whose id is used to identify which record to delete
	 */
	function doDelete(img) {
		// get id of record to delete
		img = $(img);
		var id = img.attr('id').replace('delete_', '');
	
		// confirm delete then send ajax request
		if (confirm('Are you sure you want to delete this record, any other records tied to this will be removed as well?')) {
			img.parent().parent().remove();
			$.post(config_dir + '/ajax/apadmin/delete/' + target , { id: id });
		}
	}
	/**
	 * Processes make inactive request.
	 * @param img : dom object whose id is used to identify which record to delete
	 */
	function doInActive(img) {
		// get id of record to delete
		img = $(img);
		var id = img.attr('id').replace('delete_', '');
	
		// confirm delete then send ajax request
		if (confirm('Are you sure you want to delete this record, any other records tied to this will be removed as well?')) {
			img.parent().parent().remove();
			$.post(config_dir + 'ajax/apadmin/' + target + '.inactive.php', { id: id });
		}
	}
	/**
	 * Processes make inactive request.
	 * @param img : dom object whose id is used to identify which record to delete
	 */
	function doActive(img) {
		// get id of record to delete
		img = $(img);
		var id = img.attr('id').replace('active_', '');
	
		// confirm delete then send ajax request
		if (confirm('Are you sure you want to make this record active?')) {
			
			$.post(config_dir + 'ajax/apadmin/' + target + '.active.php', { id: id });
			alert('Record Updated');
		}
	}
	/**
	 * Processes restore request.
	 * @param img : dom object whose id is used to identify which record to restore
	 */
	function doRestore(img) {
		// get id of record to restore
		img = $(img);
		var id = img.attr('id').replace('restore_', '');
	
		// confirm delete then send ajax request
		if (confirm('Are you sure you want to restore this record?')) {
			img.parent().parent().remove();
			$.post(config_dir + 'ajax/apadmin/' + target + '.restore.php', { id: id });
			
		}
	}
	
	/**
	 * Handles click on cancel button when adding or editing a record.
	 */
	function doCancel() {
    	$('.dialog').dialog('close');
    	clearFormFields(); 
	}
	
	/**
	 * Returns a dom td element with the appropriate parameters for
	 * editing a record. Used when creating a new row on record add.
	 * @param id : id of record
	 */
	function doUpload(id,imgtourl) {
		//alert(imgtourl);
		$('#' + target + '_imageupload').dialog('open');
		$('#' + target + '_imageupload .loading').hide();
    	$('#' + target + '_imageupload form.validate').fadeIn();
    	clearFormFields();
    	
    	$("#upimgid").val(id);
    	$("#imgurlcurr").val(imgtourl);
    	$("#imgholder").html("Current Image<br><img src='" + imgtourl + "'>");
    	
	}
	function constructEditCell(id) {
		var td = document.createElement('td');
		var img = document.createElement('i');
		
		img.role = 'button';
		img.className = 'splashy-pencil img';
		img.id = 'edit_' + id;
		$(img).click(function() { doEdit(this); });
		td.className = 'img';
		$(td).append(img);
		
		return td;
	}
	
	/**
	 * Returns a dom td element with the appropriate parameters for
	 * deleting a record. Used when creating a new row on record add.
	 * @param id : id of record
	 */
	function constructDeleteCell(id) {
		var td = document.createElement('td');
		var img = document.createElement('i');
		
		img.role = 'button'
		img.alt = 'Delete Record';
		img.title = 'Delete Record';
		img.className = 'splashy-remove img';
		img.id = 'delete_' + id;
		$(img).click(function() { doDelete(this); });
		td.className = 'img';
		$(td).append(img);
		
		return td;
	}

