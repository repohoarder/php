var facebox_loaded = false;

new Image().src = '/resources/brainhost/js/facebox/src/loading.gif';

$(document).ready(function(){

	if ( ! facebox_loaded)
	{

		$('head').append('<link href="/resources/brainhost/js/facebox/src/facebox.css" media="screen" rel="stylesheet" type="text/css"/>');

		$.getScript(
			"/resources/brainhost/js/facebox/src/facebox.min.js", 
			function(data, textStatus, jqxhr) {
				facebox_loaded = true;
			}
		);

	}

});

function show_loading_dialog() {
	
	if ( ! facebox_loaded) 
	{
		return false;
	}

	$.facebox('Loading');

	$('#facebox .close').hide();
	$('#facebox_overlay').unbind('click');

	i = 0;
	text = "Loading";
	setInterval(function() {
	    $("#facebox .content").html(text+Array((++i % 4)+1).join("."));
	   		if (i===10) text = "Loading";
		}, 500);

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