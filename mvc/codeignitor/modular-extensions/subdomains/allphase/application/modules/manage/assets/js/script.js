/* 
 * script will recalculate totals for payout calculator
 * 
 */
$(function(){
  
   
    $('#revenuecalculator').validate();
    $(".revcalculate").each(function(){
        $(this).rules("add", {required: true,number : true})
    })
    $(".revcalculate").keyup(function(){
        if (!$('#revenuecalculator').valid()) {
                return;
        }
       var gross            = 0.00;
       var expenses         = 0.00;
       var net              = 0.00;
       var fees             = 0.00;
       var pricepoint       = 0.00;
       var salescount       = 0;
       var expensed         = 0.00;
       var processing_fee   = 12;
       
       var row          = $(this).attr('id').replace("pp",'').replace('cnt','');
       pricepoint       = $("#pp" + row).val();
       salescount       = $("#cnt" + row).val();
       expensed         = $("#expensehidden" + row).val();
       processing_fee   = $("#processing_fee").val();
     
       gross =  pricepoint * salescount ;
	   fees = Math.round( (processing_fee /  100  * gross ) * 100) / 100;
	   
	   expenses = ( expensed * salescount ) + fees;
	   net = gross - expenses ;
	   
       $("#gross" + row).html("$" + gross.toFixed(2));
       $("#expenses" + row).html("$" + expenses.toFixed(2));
       $("#net" + row).html("$" + net.toFixed(2));
    });
});






