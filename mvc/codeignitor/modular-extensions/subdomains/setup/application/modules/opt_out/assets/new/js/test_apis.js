$(document).ready(function(){

	$('form#check_valid').submit(function(){

		var domain     = $(this).parent().find('input[name=domain]').val(),
			response   = _is_valid(domain);

		$(this).parent().find('textarea').html(jsonEcho(response)); 

		return false;

	});

	$('form#check_avail').submit(function(){

		var sld     = $(this).parent().find('input[name=sld]').val(),
			tld     = $(this).parent().find('input[name=tld]').val(),
			response   = _is_available(sld, tld);

		$(this).parent().find('textarea').html(jsonEcho(response)); 

		return false;

	});


	$('form#get_suggs').submit(function(){

		var sld     = $(this).parent().find('input[name=sld]').val(),
			tld     = $(this).parent().find('input[name=tld]').val(),
			response = _get_suggestions(sld, tld);

		$(this).parent().find('textarea').html(jsonEcho(response)); 

		return false;

	});


});



function _get_suggestions(sld, tld)
{

	var tld_response  = _get_all_tlds(sld),
		sug_response  = _get_dom_suggestions(sld, tld),
		suggestions   = [],
		domain        = '',
		uniques       = [],
		func_response = {};

	//console.log(tld_response);
	//console.log(sug_response);

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

	//console.log(func_response);

	return func_response;

}


function _get_all_tlds(sld)
{

	var api_method = '/registrars/domain/get_all_tlds/',
		response   = '';

	api_method += sld;

	response = _plat_request(api_method);

	return response;

}

function _get_dom_suggestions(sld, tld)
{

	var api_method = '/registrars/domain/get_suggestions/',
		response   = '';

	api_method += sld + '/' + tld; 

	response = _plat_request(api_method);

	return response;

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

	var api_url = 'http://setup.brainhost.com/platform/ajax/post',
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


function jsonEcho(json, level) {	

	if (typeof text === 'undefined'){
		text = '';
	}

	if (level == null) {
		level = 0;
	}
	for (prop in json) {
		// add padding
		for (a = 0; a < level; a++) {
			text += ' ';
		}
 
		// add property
		text += '"'+prop+'": ';
 
		// display property value in case its string
		if (typeof json[prop] == "string") {
			text += '"'+json[prop]+'"'+"\n";
		// else run the jsonEcho recursion
		} else {
			text += '{'+"\n";
			text = jsonEcho(json[prop], level+1, text);
			for (a = 0; a < level; a++) {
			  text += ' ';
			}
			text += '}'+"\n";
		}
	}
 
	return text;
}