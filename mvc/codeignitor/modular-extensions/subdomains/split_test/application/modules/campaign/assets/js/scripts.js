$(document).ready(function(){
	
	$('a.add_item').click(function(e){
		e.preventDefault();
		//alert('sup');
		parent_fieldset = $(this).closest('fieldset');
		new_item = parent_fieldset.find('li:last').clone();
		new_item.find('input').each(function(){
			input = $(this);
			input.val('');
			
			original_name = input.attr('name');
			num = original_name.match(/[(\d+)]/);
			var incrementedValue = parseInt(num)+1; 
			new_name = original_name.replace(/[(\d+)]/, incrementedValue); 
			input.attr('name',  new_name);
			
			input.removeClass('required');
				
		});
		
		new_item.appendTo(parent_fieldset.find('ul:first'));
		
		return false;
	});
	
	
	$('button').click(function(){
		
		var total_percent = 0;
		
		$('.percent').each(function(){
			
			if($(this).val() > 0){
			total_percent += parseInt($(this).val());	
			}
		});
		
		if(total_percent != 100){
			alert('Percents must total 100% - current total : ' + total_percent + '%');
			return false;
		}
		
		$('form#camp').submit();
		
	});
	
	$('.confirm').click(function(e) {
	
		specified_text = $(this).attr('title');
		if(!specified_text){
			specified_text = "Are you sure?";
		}
	
		return confirm(specified_text);
		
	});
	

    $("form#camp").validate({
		errorPlacement: function(error,element) {
                       element.addClass('error');

                    }
	});

  
  

});