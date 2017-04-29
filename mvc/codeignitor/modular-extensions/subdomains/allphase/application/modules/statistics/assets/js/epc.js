$(function() {

  $('#pnl-epc-custom-date input[type="submit"]').on('click', function(e) {

    e.preventDefault();

    var start_date = $('#startdateepc').val();
    var end_date = $('#enddateepc').val();

    if(start_date ==''){
      start_date = $("#startdateepc").attr('placeholder');
    }

    if(end_date ==''){
      end_date = $("#enddateepc").attr('placeholder');
    }

    get_epc_custom_date_ranges(start_date, end_date);

  });


});



function get_epc_custom_date_ranges(start_date, end_date) {

  $('#pnl-epc-stats').fadeIn(200);

  $.getJSON(
  '/ajax/statistics/visitors/?start_date=' + start_date + '&end_date=' + end_date, 
  function(json) {

    $("#epc_start_date").html(start_date);
    $("#epc_end_date").html(end_date);
    
    $.each(json.data, function(key, val) {
      
      if ($("#epc_" + key).length > 0) {          
        $("#epc_" + key).html(val);
      }

    });

  });
}