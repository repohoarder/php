
$(function() {
	
$(".saveprice").click(function(){
	var id = $(this).attr('id').replace('sub','');
	var cost = $("#cost" + id).val();
	var price = $("#price" + id).val();
	var setup_fee = $("#setup_fee" + id).val();
	$.getJSON(
  '/ajax/admin/update_default_pricing/?price=' + price + '&cost=' + cost+ '&setup_fee=' + setup_fee + '&id=' + id, 
  function(json) {
	  
  });
});

});


