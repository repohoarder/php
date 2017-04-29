$(document).ready(function(){
	
	var 
		elems,
		today       = new Date(),
		d           = today.getDate(),
		m           = today.getMonth(),
		y           = today.getFullYear(),
		next_date   = new Date(y+1, m, d),
		bill_inputs = {
			first_name : 'Testerbean [TEST]',
			last_name : 'McTesterson [TEST]',
			email : 'testorders@brainhostdemo.com', 
			phone : '330-867-5309',
			address : '4000 Embassy Parkway',
			city : 'Akron',
			zipcode : '44312',
			country : 'US',
			state : 'OH',
			cc_num : '4111111111111111',
			cc_exp_mo : '01',
			cc_exp_yr : next_date.getFullYear().toString(),
			cc_security : '123',
			core_sld : 'test',
			core_tld : 'com',
			core_domain : 'test.com',
			core_type : 'transfer'
		};

	$('#copyright').css('cursor', 'pointer').click(function(){

		for (i in bill_inputs) {

			elems = $('input[name="'+i+'"], select[name="'+i+'"]');

			if (elems.length > 0) {

				elems.val(bill_inputs[i]);

			}

		}

		$('#tos_agreement').attr('checked','checked');

		if ($('#debugger').length < 1) {
			$('body').append('<div id="debugger" style="width:799px;margin:0 auto;"></div>');
		}

		$.ajax({
			url: '/debugger', 
			type: 'POST',
			dataType: 'text'
		})
		.success(function(data){
			
			$('#debugger').html(data);
			
		});

	});

});