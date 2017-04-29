// Global settings
settings = {  
	ajax_availability: 			'/ajax/domain/availability',
	ajax_suggestions: 			'/ajax/domain/suggestions',
	ajax_timeout: 				20000,
	fade_speed: 				500,
	error_id: 					'msg-error',
	loading_id: 				'loading',
	success_id: 				'pnl-success',
	failed_id: 					'pnl-failed',
	disabled_class: 			'disabled'
};

// Event handlers on DOM ready
$(function() {

	// Disable accordion clicks on disabled items
	$('#accordion h2').click(function(e) {
		if($(this).parent().hasClass(settings.disabled_class)) {
			e.stopImmediatePropagation();
			hide_cart();
		}
	});

	// Show cart on the billing pane
	$('#accordion .four h2').click(function(e) {
		if($(this).parent().hasClass(settings.disabled_class)) {
			hide_cart();
		}
	});

	// Update total on page load, and populate cart
	update_total();
	populate_cart();

	// Domain search click
	$(document).on('click', '#next-step1', function(e) {
		e.preventDefault();
		hide_cart();
		register_domain_click ($('input[name="radDomainType"]:checked', '#frmSetup').val(), $('#txtDomain').val(), $('#selTLD').val());
	});

	// Select a domain click
	$(document).on('click', '#next-step2', function(e) {
		e.preventDefault();
		hide_cart();
		show_panel ('three', 2);
	});

	// Search again click
	$(document).on('click', '#prev-step2', function(e) {
		e.preventDefault();
		hide_cart();
		show_panel ('one', 0);
	});

	// Select a domain click
	$(document).on('click', '#next-step3', function(e) {
		e.preventDefault();
		show_cart();
		show_panel ('four', 3);
	});

	// Select domain package click
	$(document).on('click', '#hosting-package li', function(e) {
		hide_cart();
		select_package_click($(this));
	});

	// Addon checkboxes click
	$(document).on('click', '#chkAddonsPrivacy, #chkAddonsBackup, #chkAddonsSecurity', function(e) {
		var item = $('#package-details label[for="'+$(this).attr('id')+'"] span').html();
		var price = $(this).attr('data-addon-price');
		var id = $(this).attr('id');
		var remove = true;

		if($(this).attr('checked') == 'checked') {
			add_cart_item(item, price, id, remove);
		} else {
			remove_items_click(id);
		}
		update_total();
	});

	// Remove items from cart
	$(document).on('click', '#total .remove', function(e) {
		e.preventDefault();
		remove_items_click($(this).parent().attr('data-option'));
	});
});

// Show error message
function show_error (selector, message) {
	$(selector).append('<p id="'+settings.error_id+'">'+message+'</p>');
}

// Hide error messages
function hide_error () {
	$('#'+settings.error_id).remove();
}

// Domain search button clicked
function register_domain_click (reg_type, sld, tld) {
	hide_error();
	$('#'+settings.failed_id).hide();
	$('#'+settings.success_id).hide();
	if ($("#frmSetup").valid()){
		register_domain_loading_on();
		$.ajax({
			url: settings.ajax_availability,
			type: "POST",
			context: document.body,
			timeout: settings.ajax_timeout,
			data: {'sld': sld, 'tld': tld},
			dataType: 'json',
			success: function(data) {
			    register_domain_success(data, reg_type, sld, tld);
			},
			error: function(x, t, m, data) {
				if(t==="timeout") {
					register_domain_loading_off();
					show_error('.one .panel', 'The request has timed out after '+(settings.ajax_timeout/1000)+' seconds. Please wait a while before trying again.');
				} else {
					register_domain_loading_off();
					show_error('.one .panel', 'An error has occured. Please try again, or contact customer support.');
				}
			}
		});
	}
}

// Show loading message
function register_domain_loading_on () {
	$('.one .panel').append('<span id="'+settings.loading_id+'"></span>');
	$('#loading').fadeIn(settings.fade_speed);
}

// Hide loading message
function register_domain_loading_off () {
	$('#loading').fadeOut(settings.fade_speed, function() {
		$(this).remove();
	});
}

// Domain search complete
function register_domain_success (data, reg_type, sld, tld) {
	if (data.success == true) {
		register_domain_loading_off();
		$('#pnl-success .msg-success p span').html(sld+'.'+tld);
		show_success_panel();
		show_panel ('two', 1);
	} else {
		$('#domain-suggestions .domain').remove();

		$.ajax({
			url: settings.ajax_suggestions,
			type: "POST",
			context: document.body,
			timeout: settings.ajax_timeout,
			data: {'sld': sld, 'tld': tld},
			dataType: 'json',
			success: function(suggestions) {
				update_suggestions(suggestions, sld, tld);
			},
			error: function(x, t, m, data) {
				if(t==="timeout") {
					register_domain_loading_off();
					show_error('.one .panel', 'The request has timed out after '+(settings.ajax_timeout/1000)+' seconds. Please wait a while before trying again.');
				} else {
					register_domain_loading_off();
					show_error('.one .panel', 'An error has occured. Please try again, or contact customer support.');
				}
			}
		});
	}
}

// Update domain suggestions
function update_suggestions(suggestions, sld, tld) {
	if (suggestions.success) {
		$.each(suggestions.data, function (key,value){
			$('#domain-suggestions').append('<div class="row half domain"><input type="radio" name="sld" id="sugg' + key + '" value="' + value + '" /> <label for="sugg' + key + '">' + value + '</label></div>');
			register_domain_loading_off();
			show_failed_panel();
		});
		$('#pnl-failed .msg-failed p span').html(sld+'.'+tld);
		show_panel ('two', 1);
	} else {
		register_domain_loading_off();
		show_error('.one .panel', 'An error has occured. Please try a valid domain name, or contact customer support.');
	}
}

// Show next accordion panel
function show_panel (panel, index) {
	$('.'+panel).removeClass('disabled');
	$( "#accordion" ).accordion( "option", "active", index );
}

// Domain search success
function show_success_panel () {
	$('#'+settings.failed_id).hide();
	$('#'+settings.success_id).show();
}

// Domain search unsuccessful
function show_failed_panel () {
	$('#'+settings.success_id).hide();
	$('#'+settings.failed_id).show();
}

function select_package_click(that) {
	$('#hosting-package li').removeClass('active');
	$(that).addClass('active');

	var item = "";
	var price = "";
	var id = "";
	var remove = false;

	remove_items_click('data-setup');
	if (parseFloat($('#hosting-package .active').attr('data-setup-fee')) != 0) {
		// Setup fee for monthly subscriptions
		var item = "Setup Fee";
		var price = $('#hosting-package').find('.active').attr('data-setup-fee');
		var id = "data-setup";
		add_cart_item(item, price, id, remove);
	}
	remove_items_click('data-hosting-price');
	if (parseFloat($('#hosting-package .active').attr('data-hosting-price')) != 0) {
		// Base hosting price
		var item = "Hosting Price";
		var price = $('#hosting-package').find('.active').attr('data-hosting-price');
		var id = "data-hosting-price";
		add_cart_item(item, price, id, remove);
	}
	remove_items_click('data-trial-discount');
	if (parseFloat($('#hosting-package .active').attr('data-trial-discount')) != 0) {
		// Deduction for trial discounts
		var item = "Trial Discount";
		var price = "-"+$('#hosting-package .active').attr('data-trial-discount');
		var id = "data-trial-discount";
		add_cart_item(item, price, id, remove);
	}
	remove_items_click('data-registration-fee');
	if (parseFloat($('#hosting-package .active').attr('data-registration-fee')) != 0) {
		// Domain registraion
		var item = "Registration Fee";
		var price = $('#hosting-package .active').attr('data-registration-fee');
		var id = "data-registration-fee";
		add_cart_item(item, price, id, remove);
	}
	update_total();
}

function remove_items_click(id) {
	$('#package-details #'+id).attr('checked', false);
	$('#total [data-option="'+id+'"]').remove();
	
	update_total();
}

function update_total() {
	var hosting_package = $('#hosting-package').find('.active').attr('data-hosting-package');
	var base_price = parseFloat($('#hosting-package').find('.active').attr('data-hosting-price'));
	var addon_price = 0;

	// Upsells
	addon_price += $('#chkAddonsPrivacy').is(':checked') ? parseFloat($('#chkAddonsPrivacy').attr('data-addon-price')) : 0;
	addon_price += $('#chkAddonsBackup').is(':checked') ? parseFloat($('#chkAddonsBackup').attr('data-addon-price')) : 0;
	addon_price += $('#chkAddonsSecurity').is(':checked') ? parseFloat($('#chkAddonsSecurity').attr('data-addon-price')) : 0;

	// Setup fee for monthly subscriptions
	addon_price += parseFloat($('#hosting-package').find('.active').attr('data-setup-fee'));

	// Deduction for trial discounts
	addon_price -= parseFloat($('#hosting-package .active').attr('data-trial-discount'));

	// Domain registraion
	addon_price += parseFloat($('#hosting-package').find('.active').attr('data-registration-fee'));	

	// Sum total
	var total = parseFloat(base_price + addon_price).toFixed(2);

	// Update cart
	$('#total').find('strong span').html(total);

	// Update price on hosting options
	$('#hosting-setup-fee').html($('.hosting-package').find('.active').attr('data-setup-fee'));
	$('#registration-fee').html($('.hosting-package').find('.active').attr('data-registration-fee'));
	
	$('#selHosting').val(hosting_package);
}

function add_cart_item(item, price, id, remove){
	if(remove == true) {
		$('#total ul').append('<li data-option="'+id+'"><a href="#" class="remove">Remove</a> '+item+' <span>$'+price+'</span></li>');
	} else {
		$('#total ul').prepend('<li data-option="'+id+'">'+item+' <span>$'+price+'</span></li>');
	}
}

function show_cart () {
	$('#total').fadeIn(settings.fade_speed);
}

function hide_cart () {
	$('#total').fadeOut(settings.fade_speed);
}

function populate_cart() {
	if (parseFloat($('#hosting-package .active').attr('data-trial-discount')) != 0) {
		add_cart_item('Trial Discount', '-'+$('#hosting-package .active').attr('data-trial-discount'), 'data-trial-discount', false);
	}
	if (parseFloat($('#hosting-package .active').attr('data-setup-fee')) != 0) {
		add_cart_item('Setup Fee', $('#hosting-package .active').attr('data-setup-fee'), 'data-setup', false);
	}
	if (parseFloat($('#hosting-package .active').attr('data-registration-fee')) != 0) {
		add_cart_item('Domain Registration Fee', $('#hosting-package .active').attr('data-registration-fee'), 'data-registration-fee', false);
	}
	add_cart_item('Hosting Price', $('#hosting-package .active').attr('data-hosting-price'), 'data-hosting-price', false);
	add_cart_item('Domain Privacy', $('#chkAddonsPrivacy').attr('data-addon-price'), 'chkAddonsPrivacy', true);
	add_cart_item('Automated Dialy Backup', $('#chkAddonsBackup').attr('data-addon-price'), 'chkAddonsBackup', true);
	add_cart_item('Weblock Security', $('#chkAddonsSecurity').attr('data-addon-price'), 'chkAddonsSecurity', true);
}