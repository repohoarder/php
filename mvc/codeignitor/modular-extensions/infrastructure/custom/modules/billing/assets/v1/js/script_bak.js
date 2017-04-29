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
	$(".remove_addon").click(function(){
		var rmver = $(this).attr('id').replace('addon','');
		$('#'+rmver).remove();
		updateTotal();
	});
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

});

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
	
	// add addon domains
	$(".addon_domains").each(function(){
		addon_price += parseFloat($(this).val());
	});
	// Domain registraion
	addon_price += $('#annual_price').html().length > 0 ? parseFloat($('#annual_price').html()) : 0;	

	var total = parseFloat(base_price + addon_price).toFixed(2);

	$('#js_total').find('span').html(total);
	$('#hosting_setup').html($('.hosting-package').find('.active').attr('data-setup-fee'));
	
	$('#selHosting').val(hosting_package);
}
