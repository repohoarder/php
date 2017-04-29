$(function() {
	state_options = $('#state optgroup');

	updateTotal();

	update_states();
	$('#country').on("change", function(event){
		update_states()
	});

	/* IE placeholder fix */
	if ($.browser.msie) {

		$('#form-billing').find('input').each(function(){
			if($(this).val().length == 0) {
				$(this).val($(this).attr('placeholder'));
			}
		});

		$('#form-billing').submit(function() {
			$(this).find('input').each(function() {
				var that = $(this);
				if($(this).attr('placeholder') == $(this).val()) {
					that.val('');
				}
			});
		});
		$('#form-billing').find('input').bind({
			focusin: function() {
				if($(this).val() == $(this).attr('placeholder')) {
					$(this).val('');
				}
			},
			focusout: function() {
				if($(this).val().length < 1) {
					$(this).val($(this).attr('placeholder'));
				}
			}
		});
		$('#form-billing').find('input').focusout(function() {
			
		});
	}

	$('#accordion h2').click(function(e) {      
		if ($(this).parent().hasClass("ui-state-disabled")) {
			e.stopImmediatePropagation();
			return false;
		}
	});

	$('#trigger-step3').click(function (e){
		var test = $('#form-billing').validate().checkForm();
		e.preventDefault();
		if (test === true) {
			$('.three').removeClass('ui-state-disabled');
			$('.three h2').click();
		} else {
			$('#form-billing').submit();
		}
	});

	$('#trigger-step2').click(function (e){
		//$('#form-billing').validate();
		e.preventDefault();
		$('.two').removeClass('ui-state-disabled');
		$('.two h2').click();
	});

	$('#trigger-step4').click(function (e){
		$('#form-billing').validate();
		e.preventDefault();
		$('.four').removeClass('ui-state-disabled');
		$('.four h2').click();
	});

	$( "#accordion" ).accordion({heightStyle: "content"});

	$('.hosting-package').find('li').click(function() {
		$('.hosting-package').find('li').each(function() {
			$(this).removeClass('active');
		});
		$(this).addClass('active');
		$('#hosting_setup').html($(this).attr('data-setup-fee'));

		updateTotal();
	});

	

	update_select_package();
	$('select.hosting-package').change(function(){
		update_select_package();
	});


	$('#billing_upsell_domain_privacy, #billing_upsell_daily_backup, #billing_upsell_weblock').click(function() {
		updateTotal();
	});

	$('.hosting-package li[data-hosting-package="'+$('#selHosting').val()+'"]').click();

	$('#form-billing').submit(function(e) {
		if($('#tos_agreement').is(":checked")) {
			$('#must_agree').fadeOut();
			if($('#form-billing').validate().checkForm()){
				show_loading_dialog();
			} else {
				$('#form-billing').validate({
			        ignore: []
		    	 });
				$('input.error:first').parents('.accordion-level').find('h2').click();
			}
		}
			/*
				
			} else {
				$('input.error').parents('.accordion-level').find('h2').click();
			}
		}*/
		else {
			e.preventDefault();
			$('#must_agree').fadeIn();
		}
	});
        
    $(".collect").blur(function(){
        var field = $(this).attr('name');
        var value = $(this).val();
       if($(this).val() != '') {
            $.ajax({
                    type: "POST",
                    url: "/ajax/leads/partialleads",
                    data: field + "=" + value,
                    success: function(data)
                    {

                    }
            });
       }
        
    });


   /* Display graphic illustrating location of CVV2 code on credit cards */
	$('#cvv2_popup_click').click(function(){
		
		$('#cvv2_popup').fadeIn(); 
		
		/* Hide selects so they don't poke through the popup in IE6 */
		$('.info-payment select').css('visibility','hidden'); 
		
		return false;
		
	});
	
	/* Close CVV2 graphic. Counterpart to $('#cvv2_label').click */ 
	$('#cvv2_popup').click(function(){
		
		$(this).fadeOut();
		
		/* Bring back any selects that may have been hidden */
		$('.info-payment select').css('visibility','visible'); 
		
		return false;
		
	});


	// Countdown timer on billing page for free hosting
	if ($('#time_remaining').length > 0 && $('#total_deciseconds').length > 0) {
		
		total_deciseconds_remaining = $('#total_deciseconds').val();
		
		set_clock(total_deciseconds_remaining);
		
		if (total_deciseconds_remaining > 0) {
			clock_interval = setInterval("set_clock_interval()",100);
		}
	
	}



	var original_zip = $('#zipcode').val();
	$('#zipcode').change(function(){
		if (is_geoiped() && original_zip && $('#zipcode').val() != original_zip){
			unset_geoiped();
		}
	});

	var original_state = $('#state').val();
	$('#state').change(function(){
		if (is_geoiped() && $('#state').val() != original_state){
			$('#zipcode').val('');
			$('#city').val('');
			unset_geoiped();
		}
	});

	var original_city = $('#city').val();
	$('#city').change(function(){
		if (is_geoiped() && original_city && $('#city').val() != original_city){
			$('#zipcode').val('');
			unset_geoiped();
		}
	});

	var original_country = $('#country').val();
	$('#country').change(function(){
		if (is_geoiped() && original_country && $('#country').val() != original_country){
			$('#zipcode').val('');
			$('#city').val('');
			unset_geoiped();
		}
	});


    $('#js_total').show();

});


function is_geoiped()
{
	return ($('#geoiped').length > 0 && $('#geoiped').val()=='1');
}

function unset_geoiped()
{
	$('#geoiped').val('0');
}

function pad_with_zeroes(number, length) {

	var str = '' + number;
	while (str.length < length) {
		str = '0' + str;
	}

	return str;
}

function set_clock(total_deciseconds) {
	
	total_deciseconds_remaining = total_deciseconds;
	
	if (total_deciseconds_remaining <= 0) {
		total_deciseconds_remaining = 0;
	}
	
	minutes_remaining = pad_with_zeroes(Math.floor(total_deciseconds_remaining/600),2);
	seconds_remaining = pad_with_zeroes(Math.floor((total_deciseconds_remaining%600)/10),2);
	deciseconds_remaining = pad_with_zeroes((total_deciseconds_remaining%60)%10,2);
	
	if ($('.remaining_minutes').length > 0) {
		$('.remaining_minutes').text(minutes_remaining);
	}
	
	if ($('.remaining_seconds').length > 0) {
		$('.remaining_seconds').text(seconds_remaining);
	}
	
	if ($('.remaining_deciseconds').length > 0) {
		$('.remaining_deciseconds').text(deciseconds_remaining);
	}

}

function set_clock_interval() {
	
	set_clock(total_deciseconds_remaining - 1);
	
	if (total_deciseconds_remaining <= 0) {
		clearInterval(clock_interval);
	}
	
}

function update_select_package()
{

	if ($('select.hosting-package').length > 0) {

		$('#trial_disc').slideUp();

		$('select.hosting-package').each(function(){

			var 
				opt_val = $(this).val(),
				$opt_selected = $(this).find('option[value="' + opt_val + '"]');

			$(this).find('option').removeClass('active');
			$opt_selected.addClass('active');

			$('#hosting_setup').html($opt_selected.data('setup-fee'));

			if (parseFloat($opt_selected.data('trial-discount')) > 0){

				alert(parseFloat($opt_selected.data('trial-discount')));

				$('#trial_disc span').html(parseFloat($opt_selected.data('trial-discount')));
				$('#trial_disc').slideDown();

			}

			updateTotal();

		});
	}
}

function update_states () {
	var cur_country = $('#country').val();
	var html = [];

	$(state_options).each(function(e) {
		if ($(this).attr('label') == cur_country) {
			html = $(this);
		}
	});

	$('#state').html($(html));

	$('#country option').each(function () {
		if ($(this).val() == $('#country').val()) {
			if ($(this).attr('data-req-zip') == 'no') {
				$('#zipcode').removeClass('required error');
			} else {
				$('#zipcode').addClass('required');
			}

			if ($(this).attr('data-req-state') == 'no') {
				$('#state').removeClass('required error');
			} else {
				$('#state').addClass('required');
			}
		}
	}); 

}

function updateTotal() {

	var hosting_pack = $('.hosting-package').find('.active'),
		hosting_package = hosting_pack.attr('data-hosting-package'),
		base_price = parseFloat(hosting_pack.attr('data-hosting-price')),
		setup_fee = parseFloat(hosting_pack.attr('data-trial-discount')),
		addon_price = 0;

	if (hosting_pack.length < 1){

		base_price = 0;
		setup_fee = 0;

	}

	// Upsells
	addon_price += $('#billing_upsell_domain_privacy').is(':checked') ? parseFloat($('#billing_upsell_domain_privacy').attr('data-addon-price')) : 0;
	addon_price += $('#billing_upsell_daily_backup').is(':checked') ? parseFloat($('#billing_upsell_daily_backup').attr('data-addon-price')) : 0;
	addon_price += $('#billing_upsell_weblock').is(':checked') ? parseFloat($('#billing_upsell_weblock').attr('data-addon-price')) : 0;
	addon_price -= setup_fee;

	// Setup fee for monthly subscriptions
	$('#hosting_options').find('input').each(function(e){

		if($(this).attr('data-hosting-package') == hosting_package) {
			addon_price += $(this).attr('data-setup').length > 0 ? parseFloat($(this).attr('data-setup')) : 0;
		}

	});

	if ($('#annual_price').length > 0) {
		// Domain registraion
		addon_price += $('#annual_price').html().length > 0 ? parseFloat($('#annual_price').html()) : 0;
	}

	var total = parseFloat(base_price + addon_price).toFixed(2);

	$('#js_total').find('span').html(total);
	$('#hosting_setup').html($('.hosting-package').find('.active').attr('data-setup-fee'));
	
	$('#selHosting').val(hosting_package);
}
