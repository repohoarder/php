var facebox_loaded = false;

new Image().src = '/resources/brainhost/js/facebox/src/loading.gif';

$(document).ready(function(){

	// Load Facebox for loading
	$("#domain_form").validate();
	if ( ! facebox_loaded) {
		$('head').append('<link href="/resources/brainhost/js/facebox/src/facebox.css" media="screen" rel="stylesheet" type="text/css"/>');
		$.getScript(
			"/resources/brainhost/js/facebox/src/facebox.min.js", 
			function(data, textStatus, jqxhr) {
				facebox_loaded = true;
			}
		);
		$('body').append(
			'<div style="position:absolute;left:-100000px"><img src="/resources/brainhost/js/facebox/src/loading.gif" alt=""/></div>'
		);	
	}

	// hide the domain transfer fields (tld and submit button)
	$("#transfer_form").hide();

	// hide the domain suggestions form
	$("#suggestions_form").hide();

	// onchange of radio button, show proper form
	$("input:radio[name=type]").change(function() {
	    
	    // initialize variable
	    var value = $(this).val();

	    // see fi value is register or transfer & show/hide proper forms
	    if (value == 'register'){

			$("#transfer_form").hide();	// hide transfer form
			$("#register_form").show();	// show register form

	    } else {

			$("#register_form").hide();	// hide register form
			$("#transfer_form").show();	// show transfer form

	    }
	});

	// onclick of search button, check availability
	$("#search").click(function(e){
		e.preventDefault();
		// show loading modal
		show_loading_dialog();

		// remove current suggestions from show_suggestions form
		$("#show_suggestions").empty();

		// initialize variables
		var type 	= $("input:radio[name=type]").val();
		var sld 	= $("#sld").val();
		var tld 	= $("#tld").val();

		// ajax check availability
		$.post('/ajax/domain/availability',"type=" + type + "&sld=" + sld + "&tld=" + tld, function(data){

			// parse response
			data 	= jQuery.parseJSON(data);

			// if response was unsuccessful, we need to show suggestions
			if ( ! data.success)
			{
				// we need to grab domain suggestions to display
				$.post('/ajax/domain/suggestions',"sld=" + sld + "&tld=" + tld, function(suggestions){

					// parse response
					suggestions 	= jQuery.parseJSON(suggestions);

					// see if grabbing suggestions was successful
					if (suggestions.success) {

						// add company name as hidden field within domain suggestions form
						$("#suggestion_form").append('<input type="hidden" name="company" value="' + $("#company").val() + '" />');

						// Create the list
						$('#show_suggestions').append('<ul></ul>');
						$('#show_suggestions ul').html('');

						// display suggestions
						$.each(suggestions.data, function (key,value){

							// append suggestions to form
							$('#show_suggestions ul').append('<li><input type="radio" name="sld" id="sugg' + key + '" value="' + value + '" /><label for="sugg' + key + '">' + value + '</label></li>');

						});

					}

					// show suggestions form
					$("#suggestions_form").show();
					hide_loading_dialog();
				});

			} else {	// else if availability response was successful

				// submit the form
				$("#domain_form").get(0).submit();
			}

		});

	});

	// on click of transfer button, check valid transfer domain
	$("#transfer").click(function(){

		// insert company name into hidden field
		$("#hiddencompany_transfer").val($("#company").val());

		// submit domain form
		$("#domain_form_transfer").submit();
	});

	// on click of buy button, submit the domain suggestions form
	$("#buy").click(function(){

		// show loading modal
		show_loading_dialog();

		// insert company name into hidden field
		$("#hiddencompany").val($("#company").val());
		
		// submit suggestions form
		$("#suggestion_form").submit();
	});


});

function show_loading_dialog() {
	
	if ( ! facebox_loaded) 
	{
		return false;
	}

	$.facebox('\
		<div style="text-align:center;padding-bottom:50px;">\
			<div style="padding-top:50px;text-align:center;margin-bottom:15px;">\
				<img style="height:32px;width:32px;margin:0 auto;background:url(/resources/brainhost/js/facebox/src/loading.gif) 0 0 no-repeat;" src="/resources/brainhost/js/facebox/src/loading.gif"/>\
			</div>\
			Loading...\
		</div>\
	');

	$('#facebox .close').hide();
	$('#facebox_overlay').unbind('click');

	setTimeout(disable_links_and_inputs, 1000);
	//setTimeout(hide_loading_dialog, 30000);

}

function hide_loading_dialog() {
		

	if ( ! facebox_loaded) 
	{
		return false;		
	}

	$('#facebox_overlay').click(function() { 
		$(document).trigger('close.facebox'); 
	});	

	$(document).trigger('close.facebox');	
	$('#facebox .close').show();

	reenable_links_and_inputs();

}

function disable_links_and_inputs() {

	$('a, input[submit], button').addClass('disable_clicking');
	$('.disable_clicking').live('click',function(e){
		e.preventDefault();
	});

	$('form').addClass('disable_submission');
	$('.disable_submission').live('submit', function(e){
        e.preventDefault();
	});

}

function reenable_links_and_inputs() {

	$('.disable_clicking').removeClass('disable_clicking');
	$('.disable_submission').removeClass('disable_submission');
}