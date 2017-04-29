var api_url = 'http://setup.brainhost.com/platform/ajax/post';


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
});

function get_domains_success (domains) {
	if(domains.length > 0) {
		aryDomains = domains;
		domainCounter = 1;
		$('#hdnDomain').val(aryDomains[0]);
		$('#txtDomain').html(aryDomains[0]);
		$('#pnl-loading').hide();
		$('#pnl-domain').fadeIn();
	} else {
		$('#pnl-loading').hide();
		$('#pnl-results').html('<p>Sorry, we were not able to find results based on your desired website topic.</p>').fadeIn();
	}
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
		async: false,
		success: function(data) {

			response = data;

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

	setTimeout(function(){
		get_domains_success(suggestions);
	}, 5000);

}
