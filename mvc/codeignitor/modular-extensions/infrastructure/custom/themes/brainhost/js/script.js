$(document).ready(function() {
	if (!window.console) console = {log: function() {}};
	
	// Form validation
	$('#form-billing').delegate('.required', 'blur', function(e) {
	 	validateThis(this, e);
	});
	
	$('#form-billing').on('submit', function(e) {
		validateThis(this, e);
	});
	
});

// Validate
function validateThis(domEle, e){
	if(domEle.length > 1){
		var counter = 0;
		originalEvent = e;
		$(domEle).find('.required').each(function(e) {
			if($(this).val().length < 1) {
				$(this).addClass('error');
				counter++;
			}
			else if ($(this).attr('type') == "checkbox" && $(this).attr('checked') != "checked") {
				$(this).addClass('error');
				counter++;
			}
			else {
				$(this).removeClass('error');	
			}
		});

		if (counter > 0) {
			originalEvent.preventDefault();
		}
	} else {
		if($(domEle).val().length < 1) {
			$(domEle).addClass('error');
		} else {
			$(domEle).removeClass('error');	
		}
	}
}

// 10 minute countdown
if ($('.timer').length > 0) {
	$(function(){
		var countMil = $('#txtCounter').val().length ? $('#txtCounter').val().substr(6,2) : 0;
		var countSec = $('#txtCounter').val().length ? $('#txtCounter').val().substr(3,2) : 0;
		var countMin = $('#txtCounter').val().length ? $('#txtCounter').val().substr(0,2) : 10;
		countdown = setInterval(function(){
			countMil--;
			if (countMil < 0) {
				countMil = 9;
				countSec--;
				if (countSec < 0) {
					countSec = 59;
					countMin--;
					if (countMin < 0) {
						countMil = 0, countSec = 0, countMin = 0;
						clearInterval(countdown);
					}
				}
			}
			padSec = pad(countSec,2);
			padMin = pad(countMin,2);
			$(".timer").html(padMin+':'+padSec+':'+countMil+'0');
			$("#txtCounter").val(padMin+':'+padSec+':'+countMil+'0');
			
		}, 100);
	});
}

function pad(num, size) {
	var s = "0" + num;
	return s.substr(s.length-size);
}