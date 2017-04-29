var api_url = '/platform/ajax/post',
domains_module = 'registrars/domain';

// TLD prices - tld -> list(0) -> discount(1)
if( typeof prices === 'undefined') {
var prices = {
	'com' 	: ['14.95','14.95'],
	'net' 	: ['9.95','6.95'],
	'org' 	: ['12.95','8.95'],
	'info' 	: ['11.95','7.95'],
	'biz' 	: ['19.95','12.95']
}
}
// Global to count ajax calls
var ajaxCount = 0;

$(document).ready(function(){
	// Create console.log if not available
	if (!window.console) console = {log: function() {}};
	
	// Submit search form
	$('#frmDomainSearch').submit(function(e){
		e.preventDefault();
		var sld = $(this).find('#txtDomainName').val(),
		tld 	= $(this).find('#selZone').val();
				
		// Set loading animations
		$('#availability').stop(true).slideUp(500).delay(3000);
		$('#frmDomainSearch').stop(true).fadeOut(500).delay(3000);
		$('#img-loading').stop(true).fadeIn(500).delay(3000);
		
		// Get availability, suggestions, and related TLDs
		var chkAvailibility = _plat_get_availability(sld, tld);
		var chkSuggestions = _plat_get_suggestions(sld, tld, 10);
		var chkRelatedTLDs = _plat_get_tlds(sld);
		
		$(document).ajaxComplete(function() {
			ajaxCount += 1;
			if (ajaxCount == 3) {
				$('#availability').slideDown(500);
				$('#img-loading').fadeOut(500);
				$('#frmDomainSearch').fadeIn(500);
				ajaxCount = 0;
			}
		});
		
		//$(document).ajaxStop(function() {
		//	$(this).unbind("ajaxStop");
		//	console.log("Display animation stuff goes here.");
		//});
		
	});
	
	// Remove item from order
	$('#order-summary').delegate('.remove a',
		'click', function(e) {
			e.preventDefault();
			var row = $(this).parent().parent(),
			sld 	= row.find('input[type="hidden"]').attr('data-sld'),
			tld 	= row.find('input[type="hidden"]').attr('data-tld'),
			from 	= row.find('input[type="hidden"]').attr('data-from');
			
			removeFromOrder(sld,tld,from,row);
		}
	);
	
	// Large 'add' button
	$('#availability [data-domain]').delegate('.btn-add-large',
		'click', function(e) {
			e.preventDefault();
			var sld = $(this).parent().attr('data-sld'),
			tld 	= $(this).parent().attr('data-tld');
			
			addToOrder(sld,tld,'search');
		}
	);
	
	// Small 'add' button
	$('#suggested-domains').delegate('.btn-add',
		'click', function(e) {
			e.preventDefault();
			var rowSingle = $(this).parent(),
			rowDouble	 = $(this).parent().parent(),
			sld 	= $(rowSingle).attr('data-domain') ? $(rowSingle).attr('data-sld') : $(rowDouble).attr('data-sld'),
			tld 	= $(rowSingle).attr('data-domain') ? $(rowSingle).attr('data-tld') : $(rowDouble).attr('data-tld'),
			from 	= $(rowSingle).attr('data-domain') ? $(rowSingle).attr('data-from') : $(rowDouble).attr('data-from'),
			btnAdded = '<span class="btn-added" data-domain="'+sld+'.'+tld+'" data-sld="" data-tld="" data-from="'+from+'">Added</span>';
			
			$(this).replaceWith(btnAdded);
			addToOrder(sld,tld,from);
		}
	);
});

// Get list price
function getListPrice(tld) {
	return prices[tld][0];
}

// Get discount price
function getDiscountPrice(tld) {
	return prices[tld][1];
}

// Add item to order summary
function addToOrder(sld,tld,from) {
	if(checkOrderFor(sld,tld) == false) {
		var newRow = 	
			'<tr>'
			+ '<td>'+sld+'.'+tld+'</td>'
			+ '<td><em>$'+getListPrice(tld)+'</em><input type="hidden" name="domains_only['+tld+'_'+sld+']" value="'+getListPrice(tld)+'" data-domain="'+sld+'.'+tld+'" data-tld="'+tld+'" data-sld="'+sld+'" data-from="'+from+'" /></td>'
			+ '<td class="remove"><a href="#" title="Remove from Order"><img src="/resources/brainhost/img/icon-remove.png" alt="Remove from Order" /></a></td>'
			+ '</tr>';
	
		$('#order-summary tbody').append(newRow);
		alternateRows("#order-summary tr");
	}
}

// Remove from order summary, and adjust buttons
function removeFromOrder(sld,tld,from,row) {
	var btnAddLarge = '<a href="#" class="btn-add-large">Add</a>';
	var btnAdd = '<a href="#" class="btn-add">Add</a>';
	
	switch (from) {
		case 'search':
			if($('#availability .results-yes').attr('data-domain') == (sld+'.'+tld)) {
				$('#availability').find('[data-domain="'+sld+'.'+tld+'"] .btn-added-large').replaceWith(btnAddLarge);
			}
			row.remove();
			break;
		case 'all-tlds':
			$('#suggested-domains [data-domain="'+sld+'.'+tld+'"] .btn-added').replaceWith(btnAdd);
			row.remove();
			break;
		case 'suggestions':
			$('#suggested-domains [data-domain="'+sld+'.'+tld+'"] .btn-added').replaceWith(btnAdd);
			row.remove();
			break;
	}
	alternateRows('#order-summary tr');
}

function updateAvailability(sld,tld,data) {
	if (data.data.availability == true){
		if($('#order-summary [data-domain="'+sld+'.'+tld+'"]').length == 0) {
			addToOrder(sld,tld,'search');
		}
		showResultsYes(sld,tld);
	} else {
		console.log('hit');
		showResultsNo(sld,tld);
	}
}

// The domain is available :)
function showResultsYes(sld,tld) {
	var btnAdded = '<span class="btn-added-large" data-domain="'+sld+'.'+tld+'">Added</span>';
	
	$('#availability .results-yes').attr('data-domain',sld+'.'+tld);
	$('#availability .results-yes').attr('data-tld',tld);
	$('#availability .results-yes').attr('data-sld',sld);
	$('#availability .results-yes strong').html(sld+'.'+tld);
	$('#availability .results-yes em').html('$'+getDiscountPrice(tld)+'!');
	$('#availability .results-yes .btn-add-large').replaceWith(btnAdded);
	
	$('#availability .results-no').hide();
	$('#availability .results-yes').show();
}

// The domain is not available :(
function showResultsNo(sld,tld) {
	$('#availability .results-no strong').html(sld+'.'+tld);
	
	$('#availability .results-yes').hide();
	$('#availability .results-no').show();
}

//
function updateSuggested(sld,tld,data) {
	var injectHTML = '';
	for (i in data.data.suggestions) {
		var rowSplit = '';
		var altRow = '';
		var btnAdd = '';
		rowSplit = data.data.suggestions[i].split('.');
		
		if(i % 2 === 0) {
			altRow=' class="alt"';
		} else {
			altRow='';
		}
		
		if(checkOrderFor(rowSplit[0],rowSplit[1]) == false) {
			btnAdd = '<a href="#" class="btn-add">Add</a>';
		} else {
			btnAdd = '<span class="btn-added">Added</span>';
		}
		
		injectHTML += 
			'<tr'+altRow+' itemscope itemtype="http://schema.org/Product" data-domain="'+rowSplit[0]+'.'+rowSplit[1]+'" data-sld="'+rowSplit[0]+'" data-tld="'+rowSplit[1]+'" data-from="suggestions">'
			+'	<td>'+btnAdd+'</td>'
			+'	<td colspan="3" itemprop="name">'+data.data.suggestions[i]+'</td>'
			+'	<td itemprop="offers" itemscope itemtype="http://schema.org/Offer"><em>$'+getListPrice(rowSplit[1])+'</em></td>'
			+'</tr>';
	}
	$('#suggested-domains tbody').html(injectHTML);
}
//
function updateAllTLD(sld, data) {
	var injectHTML = '<tr>';
	for (i in data.data.domains) {
		var rowSplit = '';
		rowSplit = i.split('.');
		var btnAdd = '';
		
		if(checkOrderFor(rowSplit[0],rowSplit[1]) == false) {
			btnAdd = '<a href="#" class="btn-add">Add</a>';
		} else {
			btnAdd = '<span class="btn-added">Added</span>';
		}
		
		injectHTML += 
			'<td itemscope itemtype="http://schema.org/Product" data-domain="'+rowSplit[0]+'.'+rowSplit[1]+'" data-sld="'+rowSplit[0]+'" data-tld="'+rowSplit[1]+'" data-from="all-tlds">'
			+'	<strong itemprop="name">.'+rowSplit[1]+'</strong><br />'
			+'	<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">'
			+'	<em>'+getListPrice(rowSplit[1])+'</em><br />'
			//+'	<em itemprop="price">'+getDiscountPrice(rowSplit[1])+'</em>'
			+'	</span>';
			if (data.data.domains[i] == true) {
				injectHTML += btnAdd;
			} else {
				injectHTML += '	<span class="btn-taken">Taken</span>';
			}
			injectHTML +='</td>';
	}
	injectHTML += '</tr>';
	$('#suggested-domains thead').html(injectHTML);
}

function checkOrderFor(sld,tld) {
	if($('#order-summary [data-domain="'+sld+'.'+tld+'"]').length > 0) {
		return true;
	} else {
		return false;
	}
}

// Color alternate rows, and check we actually have items in order summary
function alternateRows(el) {
	if ($(el).length === 1) {
		$('#order-summary tbody').append('<tr class="empty"><td colspan="3"><p>Please add an item to your order.</p></td></tr>');
	} else {
		$('#order-summary tbody').find('.empty').remove();
	}

	$(el).each(function (index) {
		if(index % 2 === 0) {
			$(this).addClass("alt");
		} else {
			$(this).removeClass("alt");
		}
	});
}

/**
 * Platform API wrapper
 * @param  {string} api_method What API to hit
 * @param  {array} api_data   What $_POST parameters to pass
 * @return {array}            Response array containing success, errors, data
 */
function _plat_make_request(sld, tld, api_method, api_data) {

	var response = '',
		request_params = {};

	if (typeof api_data === 'undefined') {
		api_data = [];
	}

	request_params['api_method'] = api_method;
	request_params['api_params'] = api_data;

	$.ajax({
		type: 'POST',
		url: api_url,
		dataType: 'json',
		data: request_params,
		async: true,
		success: function(data) {
			response = data;
			if (typeof response.data['suggestions'] !== 'undefined') {
				updateSuggested(sld,tld,data);
			}
			else if (typeof response.data['domains'] !== 'undefined'){
				updateAllTLD(sld,data);
			}
			else if (typeof response.data['availability'] !== 'undefined'){
				updateAvailability(sld,tld,data);
			}
		}
	});
	return response;
}

function _plat_get_tlds(sld, tld) {
	var api_method = domains_module + '/get_all_tlds',
		response = '';

	api_method += '/' + sld;
	response = _plat_make_request(sld, tld, api_method);

	console.log(response);

	if ( ! response.hasOwnProperty('success') || ! response.success) {
		return false;
	}

	if ( ! response.data.hasOwnProperty('domains')) {
		return false;
	}
	return response.data;
}

function _plat_get_availability(sld, tld) {
	var api_method = domains_module + '/is_available',
		response = '';

	if (typeof tld === 'undefined') {
		tld = 'com';
	}

	api_method += '/' + sld + '/' + tld;
	response = _plat_make_request(sld, tld, api_method);

	if ( ! response.hasOwnProperty('success') || ! response.success) {
		return false;
	}

	if ( ! response.data.hasOwnProperty('availability')) {
		return false;
	}
	return response.data;
}

function _plat_get_suggestions(sld, tld, num_results) {

	var api_method = domains_module + '/get_suggestions',
		response = '';

	if (typeof tld === 'undefined') {
		tld = 'com';
	}
	if (typeof num_results === 'undefined') {
		num_results = 30;
	}

	api_method += '/' + sld + '/' + tld + '/' + num_results;
	response = _plat_make_request(sld, tld, api_method);

	if ( ! response.hasOwnProperty('success') || ! response.success) {
		return false;
	}

	if ( ! response.data.hasOwnProperty('suggestions')) {
		return false;
	}
	return response.data;
}