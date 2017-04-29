var facebox_loaded = false;

new Image().src = '/resources/allphase/js/facebox/src/loading.gif';

$(document).ready(function(){

	if ( ! facebox_loaded)
	{

		$('head').append('<link href="/resources/allphase/js/facebox/src/facebox.css" media="screen" rel="stylesheet" type="text/css"/>');

		$.getScript(
			"/resources/allphase/js/facebox/src/facebox.min.js", 
			function(data, textStatus, jqxhr) {
				facebox_loaded = true;
			}
		);

		$('body').append(
			'<div style="position:absolute;left:-100000px"><img src="/resources/allphase/js/facebox/src/loading.gif" alt=""/></div>'
		);	

	}

});

function show_loading_dialog() {
	
	if ( ! facebox_loaded) 
	{
		return false;
	}

	$.facebox('\
		<div style="text-align:center;padding-bottom:50px;">\
			<div style="padding-top:50px;text-align:center;margin-bottom:15px;">\
				<img style="height:32px;width:32px;margin:0 auto;background:url(/resources/allphase/js/facebox/src/loading.gif) 0 0 no-repeat;" src="/resources/allphase/js/facebox/src/loading.gif"/>\
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