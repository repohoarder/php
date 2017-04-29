$(document).ready(function() {
	// Clone a row to be injected later
	new_row = '<li>'+$('#col-r ul li:first-child').html()+'</li>';
	$(".showit").click(function(){
		$(".hideit").show();
		$(".showit").hide();
	});
	$(".hidec").click(function(){
		$(".hideit").hide();
		$(".showit").show();
	});
	// Set all IDs and names on inputs and labels when page loads
	update_form_ids();

	$('.add-new').find('a').live('click', function(e){    
		e.preventDefault();
		update_add($(this), new_row);
	});

	// Remove button clicked
	$('.remove').find('a').live('click', function(e){    
		e.preventDefault();
		console.log('hit');
		$(this).parent().parent().fadeOut(200, function() {
			$(this).remove();
			update_form_ids();
		});
	});
});

function update_form_ids () {
	var update_selects = $('.replaced').find('select');
	var update_inputs = $('.find').find('input');

	$.each(update_inputs, function(index) {
		$(this).attr('id', 'find_text'+index);
		$(this).attr('name', 'find_text'+index);
		$(this).parent().find('label').attr('for', 'find_text'+index);
	});

	$.each(update_selects, function(index) {
		$(this).attr('id', 'sel_replacewith'+index);
		$(this).attr('name', 'sel_replacewith'+index);
		$(this).parent().find('label').attr('for', 'sel_replacewith'+index);
	});
}

function update_add (el, new_row) {
	$(new_row).appendTo('#col-r ul').hide();
	$('#col-r ul li:last-child').fadeIn(200);
	update_form_ids();
}

function update_remove (el) {
	$(this).parent().parent().fadeOut(200, function() {
		$(this).remove();
		update_form_ids();
	});
}