var api_url = '/platform/ajax/post';


$(document).ready(function() {
	// Create console.log if not available
	if (!window.console) console = {log: function() {}};
	
	// Get list of related domains
	$('#t-main').delegate('#btn-domain',
		'click', function(e) {
			e.preventDefault();
			
			// attempt to insert name & email
			_platform_insert_email($("#txtName").val(), $("#txtEmail").val());
			
			// hide any error messages
			$('#pnl-results').hide();
			
			// hide and clear custom domain name
			$('#pnl-choose-domain').hide();
			$('#txtCustomDomain').val('');
			
			var theText = 'the' + $('#txtTopic').val() + 'website';
			
			if (theText.length > 0) {
				$('#error').hide();
				$('#pnl-domain').hide();
				$('#pnl-loading').fadeIn();
				var theDomains = get_domains(theText);
			} else {
				$('#error').html('<p>Please fill out a Desired Website Topic</p>').fadeIn();
			}
		}
	);

	// Show custom domain panel
	$('#t-main').delegate('#ext-link',
		'click', function(e) {
			e.preventDefault();
			$('#pnl-domain').hide();
			$('#pnl-choose-domain').show();
	});

	$('#spinner_form').bind('keydown', function(e) {

	    if (e.keyCode == 13) {

	        e.preventDefault();
	        e.stopPropagation();

	    }

	});
	
	// Cycle through list of domains
	$('#t-main').delegate('#btn-spin',
		'click', function(e) {
			e.preventDefault();
			$.each(aryDomains, function(index, el) {
				if(index == domainCounter) {
					$('#txtDomain, #hdnDomain').val(aryDomains[index]);
				}
			});
			if (domainCounter + 1 >= aryDomains.length) {
				domainCounter = 0;
			} else {
				domainCounter++;
			}
		}
	);
	
	// Is domain taken?
	$('#t-main').delegate('#btn-custom-domain','click', function(e) {
		e.preventDefault();
		
		$('#pnl-available').hide();
		
		isCustomClicked = true;
		
		var theText = $('#txtCustomDomain').val();
		
		if (theText.length > 0) {
			$('#error').hide();
			$('#hdnDomain').val('');
			$('#pnl-domain').hide();
			$('#pnl-results').hide();
			$('#pnl-loading').fadeIn();
			var sld = $('#txtCustomDomain').val();
			var tld = $('#selTLD').val();
			response   = _is_available(sld, tld);
		} else {
			$('#error').html('<p>Please enter a domain name.</p>').fadeIn();
		}
	});
});

function get_domains_success (domains) {
	if(typeof domains != "undefined" && domains.length > 0) {
		aryDomains = domains;
		domainCounter = 1;
		$('#pnl-available').hide();
		$('#hdnDomain').val(aryDomains[0]);
		$('#txtDomain').val(aryDomains[0]);
		$('#pnl-loading').hide();
		$('#pnl-domain').fadeIn();
		$('#btn-spin').click();
		$('#signup').fadeIn();
	} else {
		$('#pnl-loading').hide();
		$('#signup').hide();
		$('#pnl-results').html('<p>Sorry, we were not able to find results based on your desired website topic.</p>').fadeIn();
	}
}

function domain_available_success(data) {
	if(data.data.availability == true) {
		$('#hdnDomain').val($('#txtCustomDomain').val() + '.' + $('#selTLD').val());
		$('#spanDomain').html($('#txtCustomDomain').val() + '.' + $('#selTLD').val());
		$('#pnl-loading').hide();
		$('#pnl-available').fadeIn();
		$('#signup').fadeIn();
	}
	else {		
		var sld     = $('#txtCustomDomain').val(),
			tld     = $('#selTLD').val(),
			response = _get_suggestions(sld, tld);
	}
}

function domain_suggestions_success(sld,tld) {
	var suggestions   = [],
		domain        = '',
		uniques       = [],
		func_response = {};
	
	if (tld_response.success) {

		for (i in tld_response.data.domains) {

			if (tld_response.data.domains[i]){

				suggestions.push(i);

				//suggestions[] = i;
			}

		}

	}

	if (sug_response.success){

		for (j in sug_response.data.suggestions) {

			domain = sug_response.data.suggestions[j];

			suggestions.push(domain);

			//suggestions[] = domain;

		}

	}

	suggestions.forEach(function(value) {
        if (uniques.indexOf(value) == -1) {
            uniques.push(value);
        }
    });

	func_response.success = (sug_response.success || tld_response.success);
	func_response.data    = [];
	func_response.error = [];


    if (func_response.success){
    	func_response.data.domains = uniques;
    }else {
    	func_response.error = new Array('Unable to get suggestions');
    }

	domainCounter = 0;
	aryDomains = suggestions;
	$('#pnl-loading').hide();
	$('#pnl-results').html('<p>Sorry, that domain is no longer available.</p>').fadeIn();
	$('#signup').show();
	$('#hdnDomain').val(aryDomains[0]);
	$('#btn-spin').html('Try Again');
	$('#txtDomain').val(aryDomains[0]);
	$('#pnl-domain').fadeIn();
}

function _platform_make_request(api_method, api_data)
{

	var response = '',
		request_params = {};

	if (typeof api_data === 'undefined') {
		api_data = [];
	}

	request_params['api_params'] = api_data;
	request_params['api_method'] = api_method;

	$.ajax({
		type: 'POST',
		url: api_url,
		dataType: 'json',
		data: request_params,
		async: true,
		success: function(data) {
			response = data.data.suggestions;
			get_domains_success(response);
		}
	});

	return response;

}

function _platform_get_suggestions(sld, tld, num_results)
{

	var api_method = '/registrars/domain/get_suggestions/',
		response = '';

	if (typeof tld === 'undefined') {
		tld = 'com';
	}
	if (typeof num_results === 'undefined') {
		num_results = 30;
	}

	api_method += sld + '/' + tld + '/' + num_results;

	response = _platform_make_request(api_method);

	if ( ! response.hasOwnProperty('success') || ! response.success) {
		return false;
	}

	if ( ! response.data.hasOwnProperty('suggestions')) {
		return false;
	}

	return response.data.suggestions;

}

function _platform_insert_email(name, email)
{

	var api_method = '/crm/cart/hack/',
		response = '';

	if (typeof name === 'undefined') {
		name = 'Friend';
	}
	
	if (typeof email === 'undefined' || email == '') {
		return false;
	}
	
	var data		= {};
	data['email']	= email;
	data['name']	= name;
	
	response = _platform_make_request(api_method, data);
	
	if ( ! response.hasOwnProperty('success') || ! response.success) {
		return false;
	}

	return response.data.suggestions;
}


function get_domains(theText) {

	var suggestions = [];

	suggestions = _platform_get_suggestions(theText);
	
}

function _platform_is_valid_domain(domain)
{

	var api_method = '/registrars/domain/is_valid/',
		response = '';

	api_method += domain;

	response = _platform_request(api_method);

	return response;

}


function _platform_request(api_method, api_data)
{

	var response = '',
		request_params = {};

	if (typeof api_data === 'undefined') {
		api_data = [];
	}

	request_params['api_params'] = api_data;
	request_params['api_method'] = api_method;

	$.ajax({
		type: 'POST',
		url: api_url,
		dataType: 'json',
		data: request_params,
		async: true,
		success: function(data) {
			domain_available_success(data);
		}
	});

	return false;

}

function _is_available(sld, tld)
{

	var api_method = '/registrars/domain/is_available/',
		response = '';

	api_method += sld + '/' + tld;

	response = _plat_request(api_method);

	return response;

}

function _is_valid(domain)
{

	var api_method = '/registrars/domain/is_valid/',
		response = '';

	api_method += domain;

	response = _plat_request(api_method);

	return response;

	/*
	if ( ! response.hasOwnProperty('success') || ! response.success) {
		return false;
	}


	if ( ! response.data.hasOwnProperty('suggestions')) {
		return false;
	}

	return response.data.suggestions;
	 */

}


function _plat_request(api_method, api_data)
{

	var api_url = '/platform/ajax/post',
		response = '',
		params = {};

	if (typeof api_data === 'undefined') {
		api_data = [];
	}

	params.api_params = api_data;
	params.api_method = api_method;

	$.ajax({
		type: 'POST',
		url: api_url,
		dataType: 'json',
		data: params,
		async: true,
		success: function(data) {
			domain_available_success(data);
		}
	});

}

function _get_suggestions(sld, tld)
{

	_get_all_tlds(sld, tld);
	//console.log(tld_response);
	//console.log(sug_response);

	

}


function _get_all_tlds(sld, tld)
{

	var api_method = '/registrars/domain/get_all_tlds/',
		response   = '';

	api_method += sld;
	
	var api_url = '/platform/ajax/post',
		response = '',
		params = {};

	if (typeof api_data === 'undefined') {
		api_data = [];
	}

	params.api_params = api_data;
	params.api_method = api_method;

	$.ajax({
		type: 'POST',
		url: api_url,
		dataType: 'json',
		data: params,
		async: true,
		success: function(data) {
			tld_response = data;
			_get_dom_suggestions(sld, tld)
		}
	});
}

function _get_dom_suggestions(sld, tld)
{

	var api_method = '/registrars/domain/get_suggestions/',
		response   = '';

	api_method += sld + '/' + tld; 

	var api_url = '/platform/ajax/post',
		response = '',
		params = {};

	if (typeof api_data === 'undefined') {
		api_data = [];
	}

	params.api_params = api_data;
	params.api_method = api_method;

	$.ajax({
		type: 'POST',
		url: api_url,
		dataType: 'json',
		data: params,
		async: false,
		success: function(data) {
			sug_response = data;
			domain_suggestions_success(sld,tld);
		}
	});
}

function _plat_request_sync(api_method, api_data)
{

	var api_url = '/platform/ajax/post',
		response = '',
		params = {};

	if (typeof api_data === 'undefined') {
		api_data = [];
	}

	params.api_params = api_data;
	params.api_method = api_method;

	$.ajax({
		type: 'POST',
		url: api_url,
		dataType: 'json',
		data: params,
		async: false,
		success: function(data) {
			
			response = data;

		}
	});

	return response;

}