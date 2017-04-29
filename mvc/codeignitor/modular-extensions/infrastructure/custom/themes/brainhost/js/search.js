var api_url = 'http://domains.brainhost.com/platform/ajax/post',
domains_module = 'registrars/domain';

// TLD prices - tld -> list -> discount
var prices = {
	'com' : ['14.95','9.95'],
	'net' : ['9.95','6.95'],
	'org' : ['12.95','8.95'],
	'info' : ['11.95','7.95'],
	'biz' : ['19.95','12.95']
}

$(document).ready(function(){
	// Add searched domain to order, and change button
	$('#availability [data-domain]').delegate( ".btn-add-large", "click", function(e) {
		e.preventDefault();
		
		var domainName = $(this).parent().parent().attr('data-domain') ? $(this).parent().parent().attr('data-domain') : $(this).parent().attr('data-domain');
		var btnAdded = '<span class="btn-added-large" data-domain="'+domainName+'">Added</span>';
		var priceDiscount = $(this).parent().parent().find('em').html();
		var removeButton = '<a href="#" title="Remove from Order"><img src="/resources/brainhost/img/icon-remove.png" alt="Remove from Order" /></a>';
		var newRow =  '<tr>'
						+ '<td>'+domainName+'</td>'
						+ '<td><em>'+priceDiscount+'</em><input type="hidden" name="domains[]" data-domain="'+domainName+'" /></td>'
						+ '<td class="remove">'+removeButton+'</td>'
					+ '</tr>';

		$('#order-summary tbody').append(newRow);
		
		$(this).replaceWith(btnAdded);
		//$(this).remove();
		
		alternateRows("#order-summary tr");
	});
	
	// Add suggested domains to order, and change button
	$('#availability [data-domain]').delegate( ".btn-add", "click", function(e) {
		e.preventDefault();
		
		var domainName = $(this).parent().parent().attr("data-domain") ? $(this).parent().parent().attr('data-domain') : $(this).parent().attr('data-domain');
		var btnAdded = '<span class="btn-added" data-domain="'+domainName+'">Added</span>';
		var priceDiscount = $(this).parent().find('em').html() ? $(this).parent().find('em').html() : $(this).parent().parent().find('em').html();
		var priceOriginal = $(this).parent().find('del').html() ? $(this).parent().find('del').html() : $(this).parent().parent().find('del').html();
		var removeButton = '<a href="#" title="Remove from Order"><img src="/resources/brainhost/img/icon-remove.png" alt="Remove from Order" /></a>';
		var newRow =  '<tr>'
						+ '<td>'+domainName+'</td>'
						+ '<td><del>'+priceOriginal+'</del> <em>'+priceDiscount+'</em><input type="hidden" name="domains[]" data-domain="'+domainName+'" /></td>'
						+ '<td class="remove">'+removeButton+'</td>'
					+ '</tr>';

		$('#order-summary tbody').append(newRow);
		
		$(this).replaceWith(btnAdded);
		//$(this).remove();
		
		alternateRows("#order-summary tr");
	});
	
	// Remove from order summary, and change buttons back
	$('#order-summary').delegate('.remove a', 'click', function(e) {
		e.preventDefault();
		
		var domainName = $(this).parent().parent().find('input[type="hidden"]').attr('data-domain');
		var btnAdd = '<a href="#" class="btn-add">Add</a>';
		var btnAddLarge = '<a href="#" class="btn-add-large">Add</a>';
		
		$('#availability').find('[data-domain="'+domainName+'"] .btn-added-large').replaceWith(btnAddLarge);
		$('#availability').find('[data-domain="'+domainName+'"] .btn-added').replaceWith(btnAdd);
		$(this).parent().parent().remove();
		
		alternateRows("#order-summary tr");

	});
	
	// Suggested domains
	$('#asdfhdfh').submit(function(){
		
		
		var 
			prnt        = $(this).parent(),
			output_elem = prnt.find('#availability .column'),
			sld         = prnt.find('input[name=sld]').val(),
			tld         = prnt.find('input[name=tld]').val(),
			num_results = prnt.find('input[name=num_results]').val(),					
			data        = _plat_get_suggestions(sld, tld, num_results);

		//output_elem.html('');

		if (data && data.suggestions.length > 0) {
			for (i in data.suggestions) {
				output_elem.append(data.suggestions[i] + "\n");
			}
		} else {
			// error goes here
			alert('error!');
		}
		return false;
	});

	// Check availability on submit
	$('#frmDomainSearch').submit(function(){
		
		var 
			prnt        = $(this).parent(),
			output_elem = prnt.find('pre'),
			sld         = prnt.find('input[name="txtDomainName"]').val(),
			tld         = prnt.find('select[name=selZone]').val(),				
			data        = _plat_get_availability(sld, tld);

		if (data){
			if (data.availability) {
				//alert('Yarp');
				$('#availability .results-no').hide();
				$('#availability .results-yes strong').html(sld+'.'+tld);
				$('#availability .results-yes em').html('$'+prices[tld][1]+'!');
				$('#availability .results-yes').show();
			} else {
				//alert('Noop');
				$('#availability .results-yes').hide();
				$('#availability .results-no strong').html(sld+'.'+tld);
				$('#availability .results-no').show();
			}

		} else {
			// error goes here
			alert('error!');
		}
		$('#img-loading').hide();
		$(this).show();
		return false;
	});

	// Get TLDs on submit
	$('#tlds form').submit(function(){
		var 
			prnt        = $(this).parent(),
			output_elem = prnt.find('pre'),
			sld         = prnt.find('input[name=sld]').val(),		
			data        = _plat_get_tlds(sld);
		output_elem.html('');
		if (data) {
			for (i in data.domains) {
				output_elem.append(i+': '+data.domains[i]+"\n");
			}
		} else {
			// error goes here
			alert('error!');
		}
		return false;
	});
});



/**
 * Platform API wrapper
 * @param  {string} api_method What API to hit
 * @param  {array} api_data   What $_POST parameters to pass
 * @return {array}            Response array containing success, errors, data
 */
function _plat_make_request(api_method, api_data) {
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
		async: false,
		success: function(data) {
			response = data;
		}
	});
	return response;
}

function _plat_get_tlds(sld) {
	var api_method = domains_module + '/get_all_tlds',
		response = '';
	api_method += '/' + sld;
	response = _plat_make_request(api_method);
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
	response = _plat_make_request(api_method);

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
	response = _plat_make_request(api_method);

	if ( ! response.hasOwnProperty('success') || ! response.success) {
		return false;
	}

	if ( ! response.data.hasOwnProperty('suggestions')) {
		return false;
	}
	return response.data;
}

// Color alternate rows
function alternateRows(domEle) {
	if ($(domEle).length === 1) {
		$('#order-summary tbody').append('<tr class="empty"><td colspan="3"><p>Please add an item to your order.</p></td></tr>');
	} else {
		$('#order-summary tbody').find('.empty').remove();
	}

	$(domEle).each(function (index) {
		if(index % 2 === 0) {
			$(this).addClass("alt");
		} else {
			$(this).removeClass("alt");
		}
	});
}