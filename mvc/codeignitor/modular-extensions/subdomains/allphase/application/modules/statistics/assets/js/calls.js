 $(function() {

 	$('#pnl-calls-custom-date input[type="submit"]').on('click', function(e) {
 		e.preventDefault();
 		var start_date = $('#txtCustomFrom').val();
 		var end_date = $('#txtCustomTo').val();
                if(start_date ==''){
                    start_date = $("#txtCustomFrom").attr('placeholder');
                }
                 if(end_date ==''){
                    end_date = $("#txtCustomTo").attr('placeholder');
                }
 		get_calls_custom_date_ranges(start_date, end_date);

 	});

});

function get_calls_custom_date_ranges(start_date, end_date) {

	$('#pnl-custom-stats').fadeIn(200);
      
	$.getJSON('/ajax/statistics/calls/?start_date=' + start_date + '&end_date=' + end_date, function(json) {
    $("#result_start_date").html(start_date);
    $("#result_end_date").html(end_date);
    $.each(json.data, function(key, val) {
       $("#result_" + key).html(val);
    });

  });

}