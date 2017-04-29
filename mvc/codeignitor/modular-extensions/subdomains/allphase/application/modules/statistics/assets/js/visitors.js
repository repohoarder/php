 $(function() {

 	$('#pnl-visitor-custom-date input[type="submit"]').on('click', function(e) {
 		e.preventDefault();
 		var start_date = $('#startdate').val();
 		var end_date = $('#enddate').val();
                if(start_date ==''){
                    start_date = $("#startdate").attr('placeholder');
                }
                 if(end_date ==''){
                    end_date = $("#enddate").attr('placeholder');
                }
 		get_visitors_custom_date_ranges(start_date, end_date);
 	});

});

 function get_visitors_custom_date_ranges(start_date, end_date) {
 	$('#pnl-visitor-stats').fadeIn(200);
        
 	$.getJSON('/ajax/statistics/visitors/?start_date=' + start_date + '&end_date=' + end_date, function(json) {
        $("#visitors_start_date").html(start_date);
        $("#visitors_end_date").html(end_date);
        $.each(json.data, function(key, val) {
           $("#visitors_" + key).html(val);
        });

      });
 }


